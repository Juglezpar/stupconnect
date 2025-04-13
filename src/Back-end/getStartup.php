<?php
require_once '../Back-end/DAO.php';

if (!isset($_GET['id'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'No se proporcionó el ID de la startup']);
    exit;
}

$id = (int) $_GET['id'];
$dao = new DAO();

// Consulta para obtener la startup por ID
$sql = "SELECT idst, st_name, sector, rewards, descr, place, skills, vacantes FROM startup WHERE idst = :id";
$result = $dao->ExecuteQuery($sql, ['id' => $id]);

header('Content-Type: application/json');
if (empty($result)) {
    echo json_encode(['error' => 'Startup no encontrada']);
} else {
    echo json_encode($result[0]);
}
?>
