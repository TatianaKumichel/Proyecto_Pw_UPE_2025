<?php
require_once "connection.php";
session_start();

$errores = [];

// ----------------------------------------------------
// 1. VERIFICAR EMAIL
// ----------------------------------------------------
if (isset($_POST["verificar_email"])) {

    $email = trim($_POST["email"]);

    // Validación
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores["email"] = "Formato de email inválido.";
    }

    if (empty($errores)) {
        // Buscar usuario
        $stmt = $conn->prepare("SELECT id_usuario FROM usuario WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() === 0) {
            $errores["email"] = "ingrese un mail valido.";
        } else {
            $_SESSION["email_recuperar"] = $email;
            $_SESSION["mostrar_pass"] = true;
            header("Location: recuperar.php");
            exit;
        }
    }

    $_SESSION["errores"] = $errores;
    header("Location: recuperar.php");
    exit;
}



// ----------------------------------------------------
// 2. ACTUALIZAR CONTRASEÑA
// ----------------------------------------------------
if (isset($_POST["actualizar_pass"])) {

    $password = $_POST["password"];
    $password2 = $_POST["password2"];

    if (strlen($password) < 6) {
        $errores["password"] = "La contraseña debe tener al menos 6 caracteres.";
    }

    if ($password !== $password2) {
        $errores["password2"] = "Las contraseñas no coinciden.";
    }

    if (!isset($_SESSION["email_recuperar"])) {
        $errores["general"] = "Sesión inválida. Volvé a comenzar.";
    }

    if (empty($errores)) {

        $email = $_SESSION["email_recuperar"];
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE usuario SET password_hash=? WHERE email=?");
        $stmt->execute([$hash, $email]);

        unset($_SESSION["email_recuperar"]);

        $_SESSION["exito"] = "Tu contraseña fue actualizada correctamente.";
        header("Location: recuperar.php");
        exit;
    }

    $_SESSION["errores"] = $errores;
    $_SESSION["mostrar_pass"] = true;
    header("Location: recuperar.php");
    exit;
}

?>
