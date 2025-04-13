<?php
require_once '../Back-end/DAO.php';
session_start();

header('Content-Type: application/json');

// Validamos que se hayan enviado todos los datos requeridos
if (!isset($_POST['id'], $_POST['descr'], $_POST['sector'], $_POST['rewards'], $_POST['location'], $_POST['Spots'])) {
    echo json_encode(['success' => false, 'error' => 'Faltan parámetros requeridos.']);
    exit;
}

$id = (int) $_POST['id'];
$descr = trim($_POST['descr']);
$sector = trim($_POST['sector']);
$rewards = trim($_POST['rewards']);
$skills = trim($_POST['skills']);
$place = trim($_POST['location']);
$vacantes = (int) $_POST['Spots'];

$dao = new DAO();

$sql = "UPDATE startup 
        SET descr = :descr, 
            sector = :sector, 
            rewards = :rewards, 
            skills = :skills,
            place = :place, 
            vacantes = :vacantes 
        WHERE idst = :id";

$params = [
    'descr'     => $descr,
    'sector'    => $sector,
    'rewards'   => $rewards,
    'skills'    => $skills,
    'place'  => $place,
    'vacantes'  => $vacantes,
    'id'        => $id
];

try {
    $dao->ExecuteQuery($sql, $params);
    echo json_encode(['success' => true]);
} catch (Exception $ex) {
    echo json_encode(['success' => false, 'error' => $ex->getMessage()]);
}
?>
