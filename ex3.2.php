<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filtrar Lenguas</title>
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
    <h1>Filtrar Lenguas</h1>
    <form action="ex3.2.php" method="POST">
        <label for="lengua">Nombre de la lengua:</label>
        <input type="text" id="lengua" name="lengua" placeholder="Escribe el nombre de la lengua" required>
        <button type="submit">Filtrar</button>
    </form>
</body>
</html>
<br>
<?php

if (isset($_POST["lengua"])) {

		$lengua = $_POST["lengua"];
		

 		# (1.1) Connectem a MySQL (host,usuari,contrassenya)
 		$conn = mysqli_connect('localhost','admin','admin');
 
 		# (1.2) Triem la base de dades amb la que treballarem
 		mysqli_select_db($conn, 'world');
 
 		

// Protegemos la entrada para evitar inyecciones SQL
$lengua = $conn->real_escape_string($lengua);

// Consulta SQL para filtrar lenguas por nombre con coincidencias parciales
$consulta = "
    SELECT DISTINCT cl.Language, 
               c.Name AS CountryName,
               CASE 
                   WHEN cl.IsOfficial = 'T' THEN CONCAT(cl.Language, ' [OFICIAL]')
                   ELSE cl.Language 
               END AS LanguageWithStatus
        FROM countrylanguage cl
        INNER JOIN country c ON cl.CountryCode = c.Code
        WHERE cl.Language LIKE '%$lengua%'
        ORDER BY c.Name, cl.Language;
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
 	<thead><td colspan="5" align="center" bgcolor="yellow">Idiomas:</td></thead>
 	<?php
 		# (3.2) Bucle while
		
		echo"\t\t<th>Pais</th>\n";
		echo"\t\t<th>Idioma</th>\n";

 		while( $registre = mysqli_fetch_assoc($resultat) )
 		{
 			# els \t (tabulador) i els \n (salt de línia) son perquè el codi font quedi llegible
  
 			# (3.3) obrim fila de la taula HTML amb <tr>
 			echo "\t<tr>\n";
 
 			# (3.4) cadascuna de les columnes ha d'anar precedida d'un <td>
 			#	després concatenar el contingut del camp del registre
 			#	i tancar amb un </td>
             echo "\t\t<td>".$registre["CountryName"]."</td>\n";
 			echo "\t\t<td>".$registre["LanguageWithStatus"]."</td>\n";

 			/*echo "\t\t<td>".$registre['CountryCode']."</td>\n";
 			
 			echo "\t\t<td>".$registre['Population']."</td>\n";*/
 
 			# (3.5) tanquem la fila
 			echo "\t</tr>\n";
 		}
	}
 	?>
  	<!-- (3.6) tanquem la taula -->
 	</table>

    
</body>
</html>