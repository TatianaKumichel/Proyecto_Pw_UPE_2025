<?php
require_once '../../inc/auth.php';
requierePermisoAPI('gestionar_juegos');

require_once '../../inc/connection.php';

$data = json_decode(file_get_contents("php://input"), true);
// id de juego
$id = $data['id'] ?? null;
// estado 1 = publicado, 0 = despublicado
$estado = $data['estado'] ?? null;

if (!$id || !is_numeric($id)) {
    echo json_encode(['success' => false, 'error' => 'ID inválido']);
    exit;
}

if ($estado != 0 && $estado != 1) {
    echo json_encode(['success' => false, 'error' => 'Estado inválido']);
    exit;
}

try {
    // 1. Verificar existencia y estado actual
    $stmt = $conn->prepare("SELECT titulo, publicado FROM JUEGO WHERE id_juego = :id");
    $stmt->execute([':id' => $id]);
    $juego = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$juego) {
        echo json_encode(['success' => false, 'error' => 'El juego no existe']);
        exit;
    }

    // 2. Actualizar estado
    $stmt = $conn->prepare("UPDATE JUEGO SET publicado = :estado WHERE id_juego = :id");
    $stmt->execute([
        ':estado' => $estado,
        ':id' => $id
    ]);

    // 3. Notificar la publicacion del juego
    // Solo si se está publicando (estado=1) Y antes no estaba publicado (publicado=0)
    if ($estado == 1 && $juego['publicado'] == 0) {
        enviarNotificaciones($conn, $id, $juego['titulo']);
    }

    echo json_encode([
        'success' => true,
        'message' => ($estado == 1 ? "Juego publicado." : "Juego despublicado.")
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

// Enviar notificaciones
function enviarNotificaciones($conn, $idJuego, $tituloJuego) {
    // 1. Obtener géneros del juego
    $stmt = $conn->prepare("SELECT id_genero FROM JUEGO_GENERO WHERE id_juego = :id");
    $stmt->execute([':id' => $idJuego]);
    $generosJuego = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($generosJuego)) return;

    // 2. Consulta para encontrar usuarios interesados.
    // Esta consulta selecciona usuarios que tienen alguno de los géneros del juego
    // dentro de sus top 2 géneros favoritos.
    // Crear placeholders para la cláusula IN (?,?,?)
    $placeholders = implode(',', array_fill(0, count($generosJuego), '?'));

    $sql = "
        SELECT DISTINCT u.id_usuario, u.email, u.username
        FROM USUARIO u
        JOIN (
            SELECT id_usuario, id_genero
            FROM (
                SELECT f.id_usuario, jg.id_genero, COUNT(*) as cnt,
                       RANK() OVER (PARTITION BY f.id_usuario ORDER BY COUNT(*) DESC) as rnk
                FROM favorito f
                JOIN juego_genero jg ON f.id_juego = jg.id_juego
                GROUP BY f.id_usuario, jg.id_genero
            ) ranked
            WHERE rnk <= 2
        ) top_genres ON u.id_usuario = top_genres.id_usuario
        WHERE top_genres.id_genero IN ($placeholders)
    ";

    $stmtUsers = $conn->prepare($sql);
    $stmtUsers->execute($generosJuego);
    $usuarios = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);

    // 3. Insertar notificaciones
    if (!empty($usuarios)) {
        $sqlInsert = "INSERT INTO envio_notificacion (id_usuario, id_juego, email_destinatario, asunto, mensaje) VALUES (:uid, :jid, :email, :asunto, :mensaje)";
        $stmtInsert = $conn->prepare($sqlInsert);

        foreach ($usuarios as $user) {
            $asunto = "Nuevo juego publicado: " . $tituloJuego;
            $mensaje = "Hola " . $user['username'] . ", se ha publicado \"" . $tituloJuego . "\", que coincide con tus géneros favoritos.";
            
            $stmtInsert->execute([
                ':uid' => $user['id_usuario'],
                ':jid' => $idJuego,
                ':email' => $user['email'],
                ':asunto' => $asunto,
                ':mensaje' => $mensaje
            ]);
        }
    }
}
?>
