<?php
require_once '../Back-end/DAO.php';
session_start();
header('Content-Type: application/json');

// Usamos POST en lugar de GET
if (!isset($_POST['id'])) {
    echo json_encode(['success' => false, 'error' => 'No se proporcionó el ID de la startup']);
    exit;
}

$id = (int) $_POST['id'];
$dao = new DAO();

try {
    //Eliminamos la relación en la tabla "own" si existe (en caso de que no se tenga ON DELETE CASCADE)
    $sql_own = "DELETE FROM own WHERE IdStart = :id";
    $dao->ExecuteQuery($sql_own, ['id' => $id]);

    //Eliminamos la startup de la tabla "startup"
    $sql = "DELETE FROM startup WHERE idst = :id";
    $dao->ExecuteQuery($sql, ['id' => $id]);

    // 3. Incrementamos en 1 el número de startups que puede publicar el usuario (campo "remaining")
    // Obtenemos los datos del usuario desde la sesión
    $user = json_decode($_SESSION['UserG']);
    if (!isset($user->email)) {
        throw new Exception("Información del usuario no encontrada en la sesión");
    }

    // Buscamos el registro del usuario en la base de datos utilizando el email
    $sql_user = "SELECT id, remaining FROM users WHERE email = :email";
    $user_result = $dao->ExecuteQuery($sql_user, ['email' => $user->email]);
    if (empty($user_result)) {
        throw new Exception("Usuario no encontrado en la base de datos");
    }
    $userId = $user_result[0]['id'];

    // Actualizamos el campo remaining sumándole 1
    $sql_update = "UPDATE users SET remaining = remaining + 1 WHERE id = :id";
    $dao->ExecuteQuery($sql_update, ['id' => $userId]);

    // Si todo sale bien, devolvemos un JSON indicando éxito
    echo json_encode(['success' => true]);
} catch (Exception $ex) {
    // En caso de error, devolvemos el mensaje de error
    echo json_encode(['success' => false, 'error' => $ex->getMessage()]);
}
?>
