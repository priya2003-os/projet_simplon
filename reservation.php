

	<!DOCTYPE html>
	<html>
	<head>
		<meta charset="utf-8">
		<title>réservation</title>
	</head>
	<body>

		<h1><strong>Réservation place de cinéma</strong></h1>

		<br/>

		<form method="post">
			<table>
				<tr>
					<td>
						<label for="name">N° de rangée</label>
					</td>
					<td>	
						<input type="number" name="rg" value="<?php if(isset($rg)) { echo $rg; }?>">
					</td>
				<tr>	
					<td>
						<label for="name">N de place</label>
					</td>
					<td>	
						<input type="number" name="pl" value="<?php if(isset($pl)) { echo $pl; }?>">
					</td>
				</tr>
				<tr>
					<td>
						<label for="name">Valider</label>
					</td>
					<td>
						<input type="submit" name="valider">
					</td>
				</tr>
				
			</table>
		</form>
			
			
	</body>
	</html>

	<?php

try
{
	$bdd = new PDO('mysql:host=localhost:8889;dbname=simplon', 'root', 'root');
}
catch(Exception $e)
{
	die('Erreur : '.$e->getMessage());
}



if(isset($_POST['valider']))
{
	$rg=($_POST['rg']);
	$pl=($_POST['pl']);


	$req=$bdd->query('SELECT SUM(place) AS place_total, rang FROM rangee GROUP BY rang');
	$req->execute(array('place_total', 'rang'));
    $donnees=$req->fetch();
	$updatepl=$donnees['place_total']+$pl;	
	
	 
	if ($updatepl<9)
	{
		
		$pdoStat = $bdd->prepare('INSERT INTO rangee VALUES (NULL, :rang, :place)');
		$pdoStat->bindValue(':rang', $_POST['rg'], PDO::PARAM_INT);
		$pdoStat->bindValue(':place', $_POST['pl'], PDO::PARAM_INT);
		$pdoStat->execute();
		
		$pdoStat1 = $bdd->prepare('UPDATE salle SET reserver=:reserver WHERE rang=:num LIMIT 1');
		$pdoStat1->bindValue(':num', $_POST['rg'], PDO::PARAM_INT);
		$pdoStat1->bindValue(':reserver', $updatepl, PDO::PARAM_INT);
		$pdoStat1->execute();

		echo "Les places sont disponible sur cette rangée";
	}
	else
	{	echo "<br/><br/><br/><br/>";
		echo "Il n'y a plus de place disponible sur cette rangée!";
	}
	}

	echo "<br/><br/><br/><br/><br/><br/>";

	$reservation = $bdd->prepare('SELECT rang, reserver FROM salle');

	$reservation->execute(array('rang', 'reserver'));
	$resultats=$reservation->fetchAll();


	
	echo "      ";
	for($i=1;$i<10;$i++){
			// 
	        echo "<strong>" . "[" . $i . "]" . "</strong>";
	}
			echo "<br/>";

    foreach ($resultats as $resultat) {
		echo "<strong>" . "[" . $resultat['rang'] . "]" . "</strong>";

		

		for ($i=0; $i<$resultat['reserver']; $i++){
				echo "[X]";
		}
		for ($i=$resultat['reserver']; $i<9; $i++){
			echo "[_]";
		}

			echo "<br/>";
    
		}



?>
