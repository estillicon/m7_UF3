<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Nueva Lengua</title>
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
<h1>Añadir Nueva Lengua</h1>

<!-- Formulario para añadir nueva lengua -->
<form action="ex6_1.php" method="POST">
    <label for="pais">Selecciona un país:</label>
    <select name="pais" id="pais" required>
        <?php
        // Conexión a la base de datos
        $conn = mysqli_connect('localhost', 'admin', 'admin', 'world');
        if (!$conn) {
            die("Conexión fallida: " . mysqli_connect_error());
        }

        // Consulta SQL para obtener los países
        $consulta = "SELECT Code, Name FROM country";
        $resultat = mysqli_query($conn, $consulta);

        // Mostrar los países en el menú desplegable
        if ($resultat) {
            while ($row = mysqli_fetch_assoc($resultat)) {
                echo "<option value='" . $row['Code'] . "'>" . $row['Name'] . "</option>";
            }
        } else {
            echo "<option value=''>No hay países disponibles</option>";
        }

        // Cerrar la conexión
        mysqli_close($conn);
        ?>
    </select>
    <br><br>

    <label for="lengua">Nombre de la lengua:</label>
    <input type="text" id="lengua" name="lengua" placeholder="Lengua" required>
    <br><br>

    <label for="isOfficial">¿Es oficial?</label><br>
    <input type="radio" id="isOfficial" name="isOfficial" value="T" required> Sí
    <input type="radio" id="isOfficial" name="isOfficial" value="F" required> No
    <br><br>

    <button type="submit">Añadir Lengua</button>
</form>

<?php
// Procesar el formulario después de enviarlo
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar a la base de datos
    $conn = mysqli_connect('localhost', 'admin', 'admin', 'world');
    if (!$conn) {
        die("Conexión fallida: " . mysqli_connect_error());
    }

    // Obtener los datos del formulario
    $pais = $_POST['pais'];
    $lengua = $_POST['lengua'];
    $isOfficial = $_POST['isOfficial'];

    // Verificar que los datos se han recibido correctamente
    echo "Pais: $pais <br>";
    echo "Lengua: $lengua <br>";
    echo "Es Oficial: $isOfficial <br>";

    // Preparar la consulta para insertar la nueva lengua
    $insert = "INSERT INTO countrylanguage (CountryCode, Language, IsOfficial) 
                 VALUES ('$pais', '$lengua', '$isOfficial')";

    // Ejecutar la consulta
    if (mysqli_query($conn, $insert)) {
        echo "<p>Llengua '$lengua' añadida correctamente al país seleccionado.</p>";
    } else {
        echo "<p>Error al añadir la lengua: " . mysqli_error($conn) . "</p>";
    }

    // Cerrar la conexión
    mysqli_close($conn);
}
?>

</body>
</html>
