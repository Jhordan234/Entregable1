<?php
header('Content-Type: application/json');

// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "tienda_videojuegos");

if ($conn->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conn->connect_error]));
}

// Obtener el género desde la URL
if (isset($_GET['genero'])) {
    $genero = $conn->real_escape_string($_GET['genero']);

    // Consulta para obtener recomendaciones del mismo género
    $sql = "SELECT * FROM videojuegos WHERE genero = '$genero' ORDER BY RAND() LIMIT 6";
    $result = $conn->query($sql);

    $recomendaciones = [];
    while ($row = $result->fetch_assoc()) {
        $recomendaciones[] = $row;
    }

    echo json_encode($recomendaciones);
} else {
    echo json_encode(["error" => "No se proporcionó un género"]);
}

$conn->close();
?>