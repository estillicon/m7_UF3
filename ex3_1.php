<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ex3</title>
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



<h1>Filtrar per habitants</h1>
	<form action="ex3_1.php" method="POST">
		<label for="min_habitants">Minimum de habitants:</label>
		<input type="number" name="min_habitants" id="min_habitants"  value="0"><br>

		<label for="max_habitants">Maxim de habitants:</label>
		<input type="number" name="max_habitants" id="max_habitants"  value="0"><br>
		<input type="submit" name="" id=""><br>
	</form>
<br>

<?php

if(isset($_POST["min_habitants"]) && isset($_POST["max_habitants"])) {

		$min_habitants = $_POST["min_habitants"];
		$max_habitants = $_POST["max_habitants"];

 		# (1.1) Connectem a MySQL (host,usuari,contrassenya)
 		$conn = mysqli_connect('localhost','admin','admin');
 
 		# (1.2) Triem la base de dades amb la que treballarem
 		mysqli_select_db($conn, 'world');
 
 		# (2.1) creem el string de la consulta (query)
 		$consulta = "SELECT Name, CountryCode, Population FROM city WHERE Population BETWEEN $min_habitants AND $max_habitants ORDER BY population DESC;";
 
 		# (2.2) enviem la query al SGBD per obtenir el resultat
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
 	<thead><td colspan="5" align="center" bgcolor="yellow">Ciutats:</td></thead>
 	<?php
 		# (3.2) Bucle while
		echo"\t\t<th>Nombre</th>\n";
		echo"\t\t<th>Code</th>\n";
		echo"\t\t<th>Pop.</th>\n";

 		while( $registre = mysqli_fetch_assoc($resultat) )
 		{
 			# els \t (tabulador) i els \n (salt de línia) son perquè el codi font quedi llegible
  
 			# (3.3) obrim fila de la taula HTML amb <tr>
 			echo "\t<tr>\n";
 
 			# (3.4) cadascuna de les columnes ha d'anar precedida d'un <td>
 			#	després concatenar el contingut del camp del registre
 			#	i tancar amb un </td>
 			echo "\t\t<td>".$registre["Name"]."</td>\n";
 			echo "\t\t<td>".$registre['CountryCode']."</td>\n";
 			
 			echo "\t\t<td>".$registre['Population']."</td>\n";
 
 			# (3.5) tanquem la fila
 			echo "\t</tr>\n";
 		}
	}
 	?>
  	<!-- (3.6) tanquem la taula -->
 	</table>

    
</body>
</html>