<?php
ob_start();
session_start();
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    try {
        // Preparar la consulta
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->bindValue(1, $username, PDO::PARAM_STR);
        $stmt->execute();

        // Obtener el resultado como un array asociativo
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user["password"])) {
            $_SESSION["user"] = $username;
            header("Location:welcome.php");
            exit(); // IMPORTANTE: Finalizar el script después del header
        } else {
            $_SESSION["error"] = "Usuario o contraseña incorrectos.";
            header("Location:login.php"); // Redirigir a la página de login con error
            exit();
        }

    } catch (PDOException $e) {
        $_SESSION["error"] = "Error en la base de datos.";
        header("Location:login.php");
        exit();
    }

   $stmt=null;
   $conn=null;
}
ob_end_flush();
?>