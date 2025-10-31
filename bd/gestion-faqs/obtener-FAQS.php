<?php

include '../../inc/connection.php';
header('Content-Type: application/json');

try {
    $stmt = $conn->prepare("SELECT id_faq, pregunta, respuesta FROM faq ");
    $stmt->execute();

    $preguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($preguntas);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

