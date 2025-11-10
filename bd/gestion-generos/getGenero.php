<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../inc/connection.php';

$stmt = $conn->prepare("SELECT * FROM genero ORDER BY id_genero ASC");
$stmt->execute();
$generos = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($generos);