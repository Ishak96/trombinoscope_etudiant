<?php
session_start ();
if (!isset($_SESSION['username']) or !isset($_SESSION['password'])) {
	header ('location: ./identificationprofsecret.php');
	exit();
}
require_once "../include/util.inc.php";
require_once "../include/functions.inc.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<title>trombinoscopes</title>
<meta charset="UTF-8"/>
<link rel="stylesheet" href="../css/style.css" />
<?php echo generTrombinoCSS(); ?>
</head>
<body>
<header>
		<h1 style="position: absolute; margin-left: 30%;margin-top: 3%;">Espace trombinoscopes</h1>
		<img src="../images/logo.jpg " alt="logoucp">
</header>
<nav>
	<ul>
		<li><a href="../index.html">Accueil</a></li>
		<li><a href="./professeur_secretaire.php">Enseignant et Secrétaires</a></li>
		<li><a href="./logout.php">Déconnexion</a></li>
	</ul>
</nav>
<div>
	<?php
		if(isset($_POST['afficher'])   && isset($_POST['format'])){
			if (isset($_POST['filiere'])) {
				$_SESSION['trombinoPath']="../admin/Informatique/".$_POST['filiere'];
				$_SESSION['trombinoType']='filiere';
				echo "<ul>".showPhotosRec($_SESSION['trombinoPath'],$_POST['format'])."</ul>";
			}
			elseif(isset($_POST['groupe'])){
				$_SESSION['trombinoPath']="../admin/Informatique/".$_POST['groupe'];
				$_SESSION['trombinoType']='groupe';
				echo getPhotosTable($_SESSION['trombinoPath'],$_POST['format']);
			}
		}
		else{
			header ('location: ./professeur_secretaire.php');
		}
	?>			
</div>
<form method="post" action="../pdf/generpdf.php" style="margin: auto;text-align: center;padding: 2%;">
	<input type="submit" name="creatPDF" value="Télécharger la version PDF">
</form>
	<?php
	if(isset($_SESSION['trombinoPath'])){
		$string = explode('/', $_SESSION['trombinoPath']);
		$fillier = str_replace_espace(true,$string[3]);

		$effectif_total = get_fillier_effectif("../admin/dataEffectifs.csv", $fillier);
	
		if($effectif_total >0 ){
			echo "<img src='../stat/piegraph.php' alt='piegraph' style='margin-left:20%;border: 2px solid gray;'>";
		}
		else {
			echo "<p style='color:red;text-align:center'>Filière vide!</p>\n";	
		}
	}
	?>
<footer>
	<p>Site créé par HACHOUD Rassem et AYAD Ishak,le 25/Mars/2018</p>
	<?php 
	$infos = get_name_surname("../admin/dataProf.csv",$_SESSION['username']);
	if($infos == null){
		$infos = get_name_surname("../admin/dataSecret.csv",$_SESSION['username']);
	}
		echo "<p>Vous êtes connectés en tant que ".$infos["nom"]." , ".$infos["prénom"]."</p>";
	?>
</footer>
</body>
</html>
