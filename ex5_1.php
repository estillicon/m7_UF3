<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Ciudades por País</title>
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

<h1>Buscar Ciudades por País</h1>

<!-- Formulario de búsqueda por nombre del país -->
<form action="ex5_1.php" method="POST">
    <label for="pais">Escribe el nombre del país:</label>
    <input type="text" id="pais" name="pais" placeholder="Nombre del país" required>
    <button type="submit">Buscar</button>
</form>

<br>

<?php
if (isset($_POST["pais"])) {

    // Obtener los continentes seleccionados
    $pais = $_POST["pais"];

    // Conexión a la base de datos
    $conn = mysqli_connect('localhost', 'admin', 'admin', 'world');
    if (!$conn) {
        die("Conexión fallida: " . mysqli_connect_error());
    }

    $pais = $conn->real_escape_string($pais);
    // Consulta SQL para filtrar países según los continentes seleccionados
    $consulta = "
        SELECT c.Name AS Ciudad, co.Name AS Pais
        FROM city c
        JOIN country co ON c.CountryCode = co.Code
        WHERE co.Name LIKE '%$pais%'
        ORDER BY c.Name;
    ";

    // Ejecutar la consulta
    $resultat = mysqli_query($conn, $consulta);

   
    if (!$resultat) {
            $message  = 'Consulta invàlida: ' . mysqli_error($conn) . "\n";
            $message .= 'Consulta realitzada: ' . $consulta;
            die($message);
    }
?>


<!-- (3.1) aquí va la taula HTML que omplirem amb dades de la BBDD -->
<table>
<!-- la capçalera de la taula l'hem de fer nosaltres -->
<thead><td colspan="5" align="center" bgcolor="yellow">Ciutats per pais</td></thead>
<?php
    # (3.2) Bucle while
   
   echo"\t\t<th>Pais</th>\n";
   echo"\t\t<th>Ciutat</th>\n";

    while( $registre = mysqli_fetch_assoc($resultat) )
    {
        
        echo "\t<tr>\n";
        echo "\t\t<td>".$registre["Pais"]."</td>\n";
        echo "\t\t<td>".$registre["Ciudad"]."</td>\n";
        
        echo "\t</tr>\n";
    }
}
?>
 
</table>



</body>
</html>
