<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filtrar Países por Continente</title>
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

<h1>Filtrar Países por Continente</h1>

<form action="ex4_2.php" method="POST">
    <label for="continentes">Selecciona los continentes:</label><br>
    
    <!-- Checkbox para seleccionar varios continentes -->
    <input type="checkbox" name="continentes[]" value="Asia"> Asia<br>
    <input type="checkbox" name="continentes[]" value="Europe"> Europa<br>
    <input type="checkbox" name="continentes[]" value="North America"> Norteamérica<br>
    <input type="checkbox" name="continentes[]" value="Africa"> África<br>
    <input type="checkbox" name="continentes[]" value="Oceania"> Oceanía<br>
    <input type="checkbox" name="continentes[]" value="Antarctica"> Antártida<br>
    <input type="checkbox" name="continentes[]" value="South America"> Sudamérica<br>
    
    <button type="submit">Tramet Consulta</button>
</form>

<br>

<?php
if (isset($_POST["continentes"])) {

    // Obtener los continentes seleccionados
    $continentes = $_POST["continentes"];

    // Conexión a la base de datos
    $conn = mysqli_connect('localhost', 'admin', 'admin', 'world');
    if (!$conn) {
        die("Conexión fallida: " . mysqli_connect_error());
    }

    // Protegemos la entrada para evitar inyecciones SQL
    $continentes = array_map(function($continente) use ($conn) {
        return $conn->real_escape_string($continente);
    }, $continentes);

    // Crear la condición para la consulta SQL, con la selección de múltiples continentes
    $continentes_string = "'" . implode("', '", $continentes) . "'";

    // Consulta SQL para filtrar países según los continentes seleccionados
    $consulta = "
        SELECT Name 
        FROM country 
        WHERE Continent IN ($continentes_string)
        ORDER BY Name;
    ";

    // Ejecutar la consulta
    $resultat = mysqli_query($conn, $consulta);

    // Verificar si la consulta fue exitosa
    if (!$resultat) {
        die("Error en la consulta: " . mysqli_error($conn));
    }

    // Mostrar los resultados
    if (mysqli_num_rows($resultat) > 0) {
        echo "<h2>Países en los continentes seleccionados:</h2>";
        echo "<ul>";
        while ($row = mysqli_fetch_assoc($resultat)) {
            echo "<li>" . htmlspecialchars($row['Name']) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "No se encontraron países en los continentes seleccionados.";
    }

    // Cerrar la conexión
    mysqli_close($conn);
}
?>

</body>
</html>
