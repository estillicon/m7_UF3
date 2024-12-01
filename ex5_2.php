<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filtrar Lenguas por País</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table, td, th {
            border: 1px solid black;
            border-spacing: 0px;
            padding: 5px;
        }
        th {
            background-color: yellow;
        }
    </style>
</head>
<body>

<h1>Filtrar Lenguas por País</h1>

<!-- Formulario para filtrar por país -->
<form action="ex5_2.php" method="POST">
    <label for="pais">Escribe el nombre del país:</label>
    <input type="text" id="pais" name="pais" placeholder="Nombre del país" required>
    <button type="submit">Buscar</button>
</form>

<br>

<?php
if (isset($_POST["pais"])) {

    // Obtener el nombre del país ingresado por el usuario
    $pais = $_POST["pais"];

    // Conexión a la base de datos
    $conn = mysqli_connect('localhost', 'admin', 'admin', 'world');
    if (!$conn) {
        die("Conexión fallida: " . mysqli_connect_error());
    }

    // Protegemos la entrada para evitar inyecciones SQL
    $pais = $conn->real_escape_string($pais);

    // Consulta SQL para buscar lenguas por país, con coincidencia parcial
    $consulta = "
        SELECT co.Name AS Pais, cl.Language, cl.IsOfficial, cl.Percentage
        FROM countrylanguage cl
        JOIN country co ON cl.CountryCode = co.Code
        WHERE co.Name LIKE '%$pais%'
        ORDER BY co.Name, cl.Language;
    ";

    // Ejecutar la consulta
    $resultat = mysqli_query($conn, $consulta);

    // Verificar si la consulta fue exitosa
    if (!$resultat) {
        die("Error en la consulta: " . mysqli_error($conn));
    }

    // Mostrar los resultados
    if (mysqli_num_rows($resultat) > 0) {
        echo "<h2>Lenguas encontradas:</h2>";
        echo "<table>";
        echo "<thead><tr><th>País</th><th>Llengua</th><th>Oficial</th><th>Percentatge</th></tr></thead>";
        echo "<tbody>";
        while ($row = mysqli_fetch_assoc($resultat)) {
            // Mostrar los datos en la tabla
            $oficial = ($row['IsOfficial'] == 'T') ? 'Sí' : 'No';
            echo "<tr><td>" . htmlspecialchars($row['Pais']) . "</td><td>" . htmlspecialchars($row['Language']) . "</td><td>" . $oficial . "</td><td>" . htmlspecialchars($row['Percentage']) . "%</td></tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "No se encontraron lenguas para el país ingresado.";
    }

    // Cerrar la conexión
    mysqli_close($conn);
}
?>

</body>
</html>
