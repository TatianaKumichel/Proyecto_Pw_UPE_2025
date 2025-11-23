<?php
include '../../inc/connection.php';
header('Content-Type: application/json');

$nombre = trim($_POST['nombre'] ?? "");
$action = $_POST['action'] ?? null;
$id = $_POST['id'] ?? null;

if ($nombre === "" || !$action) {
    echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
    exit;
}

try {
    if ($action === "create") {

        $stmt = $conn->prepare("INSERT INTO PLATAFORMA(nombre) VALUES(:nombre)");
        $stmt->execute(['nombre' => $nombre]);

        echo json_encode([
            'success' => true,
            'message' => 'Plataforma creada correctamente'
        ]);
        exit;
    }

    if ($action === "update") {

        if (!$id) {
            echo json_encode(['success' => false, 'error' => 'ID faltante']);
            exit;
        }

        $stmt = $conn->prepare("UPDATE PLATAFORMA SET nombre = :nombre WHERE id_plataforma = :id");
        $stmt->execute(['nombre' => $nombre, 'id' => $id]);

        echo json_encode([
            'success' => true,
            'message' => 'Plataforma actualizada correctamente'
        ]);
        exit;
    }

    // Si llega acá, acción incorrecta
    echo json_encode(['success' => false, 'error' => 'Acción desconocida']);
    exit;

} catch (PDOException $e) {

    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
    exit;
}
