<?php
require_once "../../inc/connection.php";

header('Content-Type: application/json');


try {
    $stmt = $conn->query("SELECT pregunta, respuesta FROM faq");
    $preguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['status' => 'success', 'data' => $preguntas]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
