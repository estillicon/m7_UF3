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
<h1>Afagir un usuari nou</h1>

<!-- Formulario para añadir nueva lengua -->
<form action="ex6_2.php" method="POST">
    <label for="pais">Nom:</label>
    <input name="nom" type="text" placeholder="Introdueix el nom" required /><br>
    <br>
    <label for="pais">Tipus d'usuari:</label>
    <input name="tipus" type="text" placeholder="admin, profesor o estudiant" required /><br>
    <br>
    <input type="submit" value="Enregistrar">

</form>
<?php


if (isset($_POST["tipus"]) || isset($_POST["nom"])) {

    $tipus = $_POST["tipus"];
    $nom = $_POST["nom"];
        // Conexión a la base de datos
        $conn = mysqli_connect('localhost', 'admin', 'admin', 'instituto');
        if (!$conn) {
            die("Conexión fallida: " . mysqli_connect_error());
        }

        // Consulta SQL para obtener los países
        $consulta = "SELECT id FROM tipus where tipus_usuari = '$tipus'";

        $resultat = mysqli_query($conn, $consulta);

        // Verificar si la consulta fue exitosa
        if (mysqli_num_rows($resultat) == 0) {

            echo "<p>La opción es errónea. No se encontraron resultados.</p>";

        } else {
            $fila = mysqli_fetch_assoc($resultat);
            $id = $fila['id'];
            
            $insertar = "INSERT INTO usuaris (nom, tipus_usuari) VALUES ('$nom', '$id')";
            

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

