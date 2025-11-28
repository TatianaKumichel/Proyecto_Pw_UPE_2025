<?php
require_once '../../inc/auth.php';
requierePermisoAPI('gestionar_generos');

require_once __DIR__ . '/../../inc/connection.php';

$stmt = $conn->prepare("SELECT * FROM genero ORDER BY id_genero ASC");
$stmt->execute();
$generos = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($generos);