<?php
// Conectar a la base de datos
$conn = new mysqli("localhost", "root", "", "tienda_videojuegos");

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Si hay una búsqueda, obtener los juegos que coincidan
$query = "";
$resultados = [];
$genero = "";
if (isset($_GET['query'])) {
    $query = $conn->real_escape_string($_GET['query']);
    $sql = "SELECT * FROM videojuegos WHERE nombre LIKE '%$query%'";
    $resultados = $conn->query($sql);

    // Obtener el género del primer resultado encontrado
    if ($resultados->num_rows > 0) {
        $fila = $resultados->fetch_assoc();
        $genero = $fila['genero'];
        $resultados->data_seek(0); // Reiniciar puntero de resultados
    }
}

// Obtener recomendaciones basadas en el género
$recomendaciones = [];
if ($genero) {
    $sql_recomendados = "SELECT * FROM videojuegos WHERE genero = '$genero' ORDER BY RAND() LIMIT 6";
    $recomendaciones = $conn->query($sql_recomendados);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscador de Videojuegos</title>
    <link rel="shortcut icon" href="imagenes/imagen1.png" type="image/x-icon">
    <link rel="stylesheet" href="estilos/indexstyle.css">
</head>
<body>

    <header>
        <div class="logo">
            <img src="imagenes/imagen1.png" alt="Logo Spartans">
        </div>
        <form action="/Entregable1/indexold.php" method="GET">
            <input type="text" name="query" placeholder="Buscar videojuegos..." required>
            <button type="submit">Buscar</button>
        </form>
    </header>

    <main>
        <section id="resultados">
            <h2>Resultados de búsqueda</h2>
            <?php if ($resultados && $resultados->num_rows > 0): ?>
                <?php while ($fila = $resultados->fetch_assoc()): ?>
                    <div class="juego" data-genero="<?php echo htmlspecialchars($fila['genero']); ?>">
                        <h3><?php echo $fila['nombre']; ?></h3>
                        <img src="/<?php echo $fila['imagen']; ?>" alt="<?php echo $fila['nombre']; ?>" width="200px">
                        <p><?php echo $fila['descripcion']; ?></p>
                        <p><strong>Precio: S/. <?php echo $fila['precio']; ?></strong></p>
                        <button>Comprar</button>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No se encontraron resultados.</p>
            <?php endif; ?>
        </section>

        <section id="recomendaciones">
            <h2>PRODUCTOS SIMILARES</h2>
            <div id="juegos-similares">
                <?php if ($recomendaciones && $recomendaciones->num_rows > 0): ?>
                    <?php while ($fila = $recomendaciones->fetch_assoc()): ?>
                        <div class="juego">
                            <h3><?php echo $fila['nombre']; ?></h3>
                            <img src="//<?php echo $fila['imagen']; ?>" alt="<?php echo $fila['nombre']; ?>" width="200px">
                            </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No hay recomendaciones disponibles.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

</body>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1️⃣ Obtener el género del primer juego encontrado
    let juegoEncontrado = document.querySelector(".juego");
    let genero = juegoEncontrado ? juegoEncontrado.dataset.genero : "";

    console.log("Género detectado:", genero); // 👀 Verifica en consola

    // 2️⃣ Si no hay género, no hacer nada
    if (!genero) {
        console.error("No se detectó género del juego.");
        return;
    }

    // 3️⃣ Hacer la petición a la API para obtener recomendaciones
    fetch(`api/get_recomendaciones.php?genero=${encodeURIComponent(genero)}`)
        .then(response => response.json())
        .then(data => {
            console.log("Recomendaciones recibidas:", data); // 👀 Verifica en consola

            let juegosSimilares = document.getElementById("juegos-similares");
            juegosSimilares.innerHTML = ""; // Limpiar recomendaciones previas

            // 4️⃣ Insertar los juegos recomendados en la página
            data.forEach(juego => {
                let juegoDiv = document.createElement("div");
                juegoDiv.classList.add("juego");
                juegoDiv.innerHTML = `
                    <h3>${juego.nombre}</h3>
                    <img src="/${juego.imagen}" alt="${juego.nombre}" width="200px">
                `;
                juegosSimilares.appendChild(juegoDiv);
            });
        })
        .catch(error => console.error("Error al obtener recomendaciones:", error));
});
</script>
</html>