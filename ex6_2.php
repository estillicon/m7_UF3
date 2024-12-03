<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Nuevo Usuario</title>
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
<h1>Afegeix un nou usuari</h1>

<!-- Formulario para añadir nuevo usuario -->
<form action="ex6_2.php" method="POST">
    <label for="nom">Nom:</label>
    <input name="nom" type="text" placeholder="Introdueix el nom" required /><br><br>
    
    <label for="cognoms">Cognoms:</label>
    <input name="cognoms" type="text" placeholder="Introdueix els cognoms" required /><br><br>

    <label for="email">Email:</label>
    <input name="email" type="email" placeholder="Introdueix l'email" required /><br><br>

    <label for="contrasenya">Contrasenya:</label>
    <input name="contrasenya" type="password" placeholder="Introdueix la contrasenya" required /><br><br>

    <label for="telefon">Telèfon:</label>
    <input name="telefon" type="text" placeholder="Introdueix el telèfon" /><br><br>

    <label for="tipus">Tipus d'usuari:</label>
    <input name="tipus" type="text" placeholder="admin, profesor o estudiant" required /><br><br>

    <input type="submit" value="Registrar">
</form>

<?php

if (isset($_POST["tipus"]) || isset($_POST["nom"])) {
    $nom = $_POST["nom"];
    $cognoms = $_POST["cognoms"];
    $email = $_POST["email"];
    $contrasenya = $_POST["contrasenya"];
    $telefon = $_POST["telefon"];
    $tipus = $_POST["tipus"];

    // Conexión a la base de datos
    $conn = mysqli_connect('localhost', 'admin', 'admin', 'instituto');
    if (!$conn) {
        die("Conexión fallida: " . mysqli_connect_error());
    }

    // Consulta SQL para obtener el id del tipo de usuario
    $consulta = "SELECT id FROM tipus WHERE tipus_usuari = '$tipus'";

    $resultat = mysqli_query($conn, $consulta);

    // Verificar si la consulta fue exitosa
    if (mysqli_num_rows($resultat) == 0) {
        echo "<p>La opción es errónea. No se encontraron resultados.</p>";
    } else {
        $fila = mysqli_fetch_assoc($resultat);
        $id_tipus = $fila['id'];

        // Encriptar la contrasenya
        $contrasenya_encriptada = password_hash($contrasenya, PASSWORD_BCRYPT);

        // Consulta para insertar el nuevo usuario
        $insertar = "INSERT INTO usuaris (nom, cognoms, email, contrasenya, telefon, tipus_usuaris) 
                     VALUES ('$nom', '$cognoms', '$email', '$contrasenya_encriptada', '$telefon', '$id_tipus')";

        if (mysqli_query($conn, $insertar)) {
            echo "<p>Usuari '$nom' afegit correctament.</p>";
        } else {
            echo "<p>Error al afegir l'usuari: " . mysqli_error($conn) . "</p>";
        }
    }

    // Cerrar la conexión
    mysqli_close($conn);
}
?>

</body>
</html>
