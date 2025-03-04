<?php
include 'database.php';  

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); 

    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    
    $stmt->execute([$username, $email, $password]);

    if ($stmt->rowCount() > 0) {
        echo '
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Registro Exitoso</title>
            <style>
                /* Fondo de la página */
                body {
                    font-family: Arial, sans-serif;
                    text-align: center;
                    background-image: url("imagen1.avif"); /* Cambia la imagen por la ruta de tu imagen */
                    background-size: cover;
                    background-position: center;
                    color: #fff;
                    height: 100vh;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    margin: 0;
                }

                /* Estilos para el contenedor principal */
                .container {
                    background: rgba(0, 0, 0, 0.7); /* Fondo semitransparente para mejorar la legibilidad del texto */
                    padding: 40px;
                    border-radius: 10px;
                    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
                    max-width: 500px;
                    width: 100%;
                }

                h1 {
                    color: #4CAF50; /* Color verde para el título */
                    font-size: 2.5em;
                    margin-bottom: 20px;
                }

                p {
                    font-size: 18px;
                    color: #fff;
                    margin-bottom: 20px;
                }

                a {
                    color: #007bff;
                    text-decoration: none;
                    font-weight: bold;
                    padding: 10px 20px;
                    background-color: #fff;
                    border-radius: 5px;
                    transition: background-color 0.3s;
                }

                a:hover {
                    background-color: #4CAF50;
                    color: #fff;
                }

                /* Estilos para el botón */
                .btn {
                    display: inline-block;
                    background-color: #4CAF50;
                    color: white;
                    text-decoration: none;
                    padding: 12px 30px;
                    border-radius: 5px;
                    font-size: 1.2em;
                    margin-top: 20px;
                    transition: background-color 0.3s ease;
                }

                .btn:hover {
                    background-color: #45a049;
                }

            </style>
        </head>
        <body>
            <div class="container">
                <h1>¡Registro Exitoso!</h1>
                <p>Tu cuenta ha sido creada correctamente. Ahora puedes iniciar sesión.</p>
                <p><a href="index.html" class="btn">Iniciar sesión</a></p>
            </div>
        </body>
        </html>';
    } else {
        echo "Error al registrar: " . $stmt->errorInfo()[2];
    }
}
?>