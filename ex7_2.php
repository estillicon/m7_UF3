<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Canvi de Tipus d'Usuari i Visualització d'Usuaris</title>
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

<h1>Canviar Tipus d'Usuari</h1>

<!-- Formulario para ingresar el nombre -->
<form action="ex7_2.php" method="POST">
    <label for="nom">Nom:</label>
    <input name="nom" type="text" placeholder="Introduce tu nombre" required /><br><br>
    <input type="submit" name="buscar_usuario" value="Buscar Usuario">
</form>

<?php
// Conexión a la base de datos
$conn = mysqli_connect('localhost', 'admin', 'admin', 'instituto');
if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Comprobar si el formulario de búsqueda se ha enviado
if (isset($_POST['buscar_usuario'])) {
    $nom = $_POST['nom'];

    // Consultar si el nombre existe en la base de datos
    $query = "SELECT id, tipus_usuaris FROM usuaris WHERE nom = '$nom'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $usuario = mysqli_fetch_assoc($result);
        $usuario_id = $usuario['id'];

        // Mostrar el formulario para cambiar el tipo de usuario
        echo "<h2>Selecciona el tipus d'usuari per a '$nom':</h2>";

        echo '<form action="ex7_2.php" method="POST">';
        echo '<input type="hidden" name="usuario_id" value="' . $usuario_id . '">';

        // Mostrar opciones de tipo de usuario
        echo '<label for="tipus_usuaris">Tipus d\'usuari:</label>';
        echo '<select name="tipus_usuaris" required>';
        echo '<option value="admin">Admin</option>';
        echo '<option value="estudiant">Estudiant</option>';
        echo '<option value="professor">Professor</option>';
        echo '</select><br><br>';

        echo '<input type="submit" name="canviar_tipus" value="Canviar Tipus d\'Usuari">';
        echo '</form>';
    } else {
        echo "<p>No s'ha trobat cap usuari amb aquest nom.</p>";
    }
}

// Comprobar si se ha enviado el formulario para cambiar el tipo de usuario
if (isset($_POST['canviar_tipus'])) {
    $usuario_id = $_POST['usuario_id'];
    $tipus_usuaris = $_POST['tipus_usuaris'];

    // Consultar el ID correspondiente del tipo de usuario en la tabla 'tipus'
    $query_tipus = "SELECT id FROM tipus WHERE tipus_usuari = '$tipus_usuaris'";
    $result_tipus = mysqli_query($conn, $query_tipus);

    if (mysqli_num_rows($result_tipus) > 0) {
        $tipus = mysqli_fetch_assoc($result_tipus);
        $tipus_id = $tipus['id']; // Este es el ID que debemos usar

        // Actualizar el tipo de usuario en la base de datos (tabla usuaris)
        $query_update = "UPDATE usuaris SET tipus_usuaris = $tipus_id WHERE id = $usuario_id";

        if (mysqli_query($conn, $query_update)) {
            echo "<p>El tipus d'usuari ha estat canviat correctament.</p>";
        } else {
            echo "<p>Error al canviar el tipus d'usuari: " . mysqli_error($conn) . "</p>";
        }
    } else {
        echo "<p>No s'ha trobat el tipus d'usuari seleccionat.</p>";
    }
}

// Consulta para obtener los datos con INNER JOIN y ordenado por 'tipus_usuari'
$query = "
    SELECT u.nom, t.tipus_usuari 
    FROM usuaris u
    INNER JOIN tipus t ON u.tipus_usuaris = t.id
    ORDER BY t.tipus_usuari ASC
";

$result = mysqli_query($conn, $query);

if ($result) {
    echo "<h2>Taula d'Usuaris i Tipus d'Usuari</h2>";
    echo "<table>";
    echo "<tr><th>Nom</th><th>Tipus d'Usuari</th></tr>";

    // Mostrar los resultados en la tabla
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['nom'] . "</td>";
        echo "<td>" . $row['tipus_usuari'] . "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "<p>Error al obtenir les dades: " . mysqli_error($conn) . "</p>";
}

// Cerrar la conexión
mysqli_close($conn);
?>

</body>
</html>
