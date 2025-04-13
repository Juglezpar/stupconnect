<?php
session_start();
require __DIR__ . '/DAO.php';

try {
    $dao = new DAO();

    // Verifica que el usuario esté autenticado y tenga un email válido
    if (empty($_SESSION['UserG'])) {
        throw new Exception('User not logged in.');
    }
    $user = json_decode($_SESSION['UserG']);
    if (!$user || !isset($user->email) || empty($user->email)) {
        throw new Exception('Invalid user session.');
    }
    $email = $user->email;

    // Verifica que se envíe el ID de la startup
    if (!isset($_POST['startup_id']) || empty($_POST['startup_id'])) {
        throw new Exception('Startup ID missing.');
    }
    $startup_id = $_POST['startup_id'];

    // Verifica las solicitudes disponibles del usuario
    $sql_user = "SELECT solicitudes_disponibles FROM users WHERE email = :email";
    $result = $dao->ExecuteQuery($sql_user, ['email' => $email]);
    if (empty($result)) {
        throw new Exception('User not found.');
    }
    $solicitudes = (int)$result[0]['solicitudes_disponibles'];
    if ($solicitudes < 1) {
        throw new Exception('No available requests.');
    }

    // Actualiza el usuario: resta 1 a solicitudes_disponibles
    $sql_update_user = "UPDATE users SET solicitudes_disponibles = solicitudes_disponibles - 1 WHERE email = :email";
    $updateUser = $dao->InsertData($sql_update_user, ['email' => $email]);
    if ($updateUser < 0) {
        throw new Exception('Failed to update user requests.');
    }

    // Actualiza la startup: incrementa el número de aplicaciones (se asume que existe el campo "applications")
    $sql_update_startup = "UPDATE startup SET applications = applications + 1 WHERE idst = :startup_id";
    $updateStartup = $dao->InsertData($sql_update_startup, ['startup_id' => $startup_id]);
    if ($updateStartup < 0) {
        //In case of error revert the user's request
        $sql_revert_user = "UPDATE users SET solicitudes_disponibles = solicitudes_disponibles + 1 WHERE email = :email";
        $dao->InsertData($sql_revert_user, ['email' => $email]);
        throw new Exception('Failed to update startup applications.');
    }

    // Obtener el correo del publicante usando la tabla "own" y "users"
    $sql_publisher = "SELECT u.email 
                      FROM users u 
                      JOIN own o ON u.id = o.idUser 
                      WHERE o.IdStart = :startup_id";
    $publisherResult = $dao->ExecuteQuery($sql_publisher, ['startup_id' => $startup_id]);
    if (empty($publisherResult)) {
        //In case of non finding the publisher revert the user's request and the startup application
        $sql_revert_user = "UPDATE users SET solicitudes_disponibles = solicitudes_disponibles + 1 WHERE email = :email";
        $dao->InsertData($sql_revert_user, ['email' => $email]);
        $sql_revert_startup = "UPDATE startup SET applications = applications - 1 WHERE idst = :startup_id";
        $dao->InsertData($sql_revert_startup, ['startup_id' => $startup_id]);
        throw new Exception('Publisher not found.');
    }
    $publisher_email = $publisherResult[0]['email'];

    echo json_encode(['success' => true, 'publisher_email' => $publisher_email]);
} catch (Exception $ex) {
    echo json_encode(['success' => false, 'error' => $ex->getMessage()]);
}
?>
