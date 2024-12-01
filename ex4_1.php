<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ex4</title>
    <style>
 		body{
 		}
 		table,td,th {
 			border: 1px solid black;
 			border-spacing: 0px;
 		}

		 
 	</style>
</head>
<body>
<form action="ex4_1.php" method="POST">
    <label for="continente">Selecciona un continente:</label>
    <select name="continente" id="continente">
        <option value="Asia">Asia</option>
        <option value="Europe">Europa</option>
        <option value="North America">Norteamérica</option>
        <option value="Africa">África</option>
        <option value="Oceania">Oceanía</option>
        <option value="Antarctica">Antártida</option>
        <option value="South America">Sudamérica</option>
    </select>
    <button type="submit">Filtrar</button>
</form>
<?php

if (isset($_POST["continente"])) {

		$continente = $_POST["continente"];
		

 		# (1.1) Connectem a MySQL (host,usuari,contrassenya)
 		$conn = mysqli_connect('localhost','admin','admin');
 
 		# (1.2) Triem la base de dades amb la que treballarem
 		mysqli_select_db($conn, 'world');
 
 		

// Protegemos la entrada para evitar inyecciones SQL
$continente = $conn->real_escape_string($continente);

// Consulta SQL para filtrar lenguas por nombre con coincidencias parciales
$consulta = "
    select name from country where continent = '$continente';

    ";
 
$resultat = mysqli_query($conn, $consulta);
 		# (2.3) si no hi ha resultat (0 files o bé hi ha algun error a la sintaxi)
 		#     posem un missatge d'error i acabem (die) l'execució de la pàgina web
 		if (!$resultat) {
     			$message  = 'Consulta invàlida: ' . mysqli_error($conn) . "\n";
     			$message .= 'Consulta realitzada: ' . $consulta;
     			die($message);
 		}
 	?>


 	<!-- (3.1) aquí va la taula HTML que omplirem amb dades de la BBDD -->
 	<table>
 	<!-- la capçalera de la taula l'hem de fer nosaltres -->
 	<thead><td colspan="5" align="center" bgcolor="yellow">Paises:</td></thead>
 	<?php
 		# (3.2) Bucle while
		
		/*echo"\t\t<th>Pais</th>\n";
		echo"\t\t<th>Idioma</th>\n";*/

 		while( $registre = mysqli_fetch_assoc($resultat) )
 		{
 			
 			echo "\t<tr>\n";
 
             echo "\t\t<td>".$registre["name"]."</td>\n";
 			
 			echo "\t</tr>\n";
 		}
	}
 	?>
  	<!-- (3.6) tanquem la taula -->
 	</table>

    
</body>
</html>
    
</body>
</html>