<?php
require_once '../../inc/auth.php';
requierePermisoAPI('gestionar_faq');
require_once '../../inc/connection.php';

try {
    $stmt = $conn->prepare("SELECT id_faq, pregunta, respuesta FROM faq ");
    $stmt->execute();

    $preguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($preguntas);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

