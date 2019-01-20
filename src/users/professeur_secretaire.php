<?php
session_start ();
if (!isset($_SESSION['username']) or !isset($_SESSION['password'])) {
	header ('location: ./identificationprofsecret.php');
	exit();
}
require_once "../include/util.inc.php";
require_once "../include/functions.inc.php";
$array_autocomplete = array_information_autocomplete("../admin/dataEtudiant.csv"); 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<title>profs et secretaires</title>
<meta charset="UTF-8"/>
<link rel="shortcut icon" href="../images/prof.png" type="images/png" />
<link rel="stylesheet" href="../css/style.css" />
<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css" />

<script src="../js/jquery-1.12.1.js"></script>
<script src="../js/jquery-ui.js"></script>

<style>
	.photosTable{
		background-color: white;
		border: 2px solid #570342;
		font-weight: bold;
		font-style: italic;
		color: #570342;
		font-size: 15pt;
		text-align: center;
		width: 30%;
		margin: auto;
	}
	section{
		background-color: #E6E6E6;
		width:90%;
		margin:auto;
		box-shadow: 3px 3px 3px #000;
	}
	input[type="search"]{
		width: 300px;
		height: 30px;
		font-size: 18px;
		line-height: 30px;
		padding: 8px 12px;
		border: 2px solid #ccc;
		border-radius: 8px;
	}
	input[type="search"]:focus{
		border-bottom-color: dodgerblue;
	}
	input[type="submit"]{
		height: 30px;
		line-height: 30px;
		border-radius: 8px;
		border-color: black;
		font-weight: bold;
	}
	input:required{
		background: url(../images/asterisk.png) right center no-repeat transparent;
	}
	input:focus:valid{
		border-bottom-color: green;
		background: url(../images/valid.png) right center no-repeat transparent;
	}
	input:focus:invalid{
		border-bottom-color: red;
		background: url(../images/invalid.png) right center no-repeat transparent;
	}
	legend{
		background-color: #A5A5A5; 
		border: 1px solid black;
		font-weight: bold;
	}
	label{
		padding: 3px;
		color: white;
		border-radius: 600px 100px 100px 300px;
		border-color: white;
		background-color: #570342;
	}
	h2{
 		color: #570342;
 		text-align: center;
 		font-size: 26pt;
 		padding:1%;

	}
	h3{
		color: #570342;
		margin-left: 50px;
	}
	.listeForm li {
		display: inline-block;
		list-style-type: none;
		width: 30%;
	}
</style>
</head>
<body>
<header>
		<h1 style="position: absolute; margin-left: 30%;margin-top: 3%;">Espace professeurs et sécritaires</h1>
		<img src="../images/logo.jpg " alt="logoucp">
</header>
<nav>
	<ul>
		<li><a href="../index.html">Accueil</a></li>
		<li><a href="./logout.php">Déconnexion</a></li>
	</ul>
</nav>

<section>
	<h2>Afficher un trombinoscope :</h2>
	<ul class="listeForm" style="margin-left: 20%;">
		<li><form style="padding-bottom: 10%;" method="post" action="trombinoscopes.php">
		<fieldset>
		<legend>Accès par filière</legend>
		<div style="margin-top: 5%;">
			<?php echo listOptionFilier("../admin/Informatique"); ?>
		</div>
		<div style="margin-top: 5%;">
		<label for="format">Format :</label>
		<select style="margin-left: 1%;" name='format' id="format">
			<option>4</option>
			<option>3</option>
			<option>2</option>
		</select>
		</div>
		<input type="submit" name="afficher" value="Afficher" style="font-weight: bold; margin-left: 35%; margin-top: 5%;">
		</fieldset>
	</form></li>
	<li><form style="padding-bottom: 10%;" method="post" action="trombinoscopes.php">
		<fieldset>
		<legend>Accès par groupe de TD</legend>
		<div style="margin-top: 5%;">
		<label  for="groupe">Filière :</label>
		<select name='groupe' id="groupe">
			<?php liste_option("../admin/Informatique",0); ?>
		</select>
		</div>
		<div style="margin-top: 5%;">
		<label for="format2">Format :</label>
		<select style="margin-left: 1%;" name='format' id="format2">
			<option>4</option>
			<option>3</option>
			<option>2</option>
		</select>
		</div>
		<input type="submit" name="afficher" value="Afficher" style="font-weight: bold; margin-left: 35%; margin-top: 5%;">
		</fieldset>
	</form></li>
	</ul>
</section>
<section id="rech">
<h2>Rechercher un étudiant :</h2>
<form style="width: 40%; margin-left: 31%;" method="post" action="professeur_secretaire.php#rech">
	<input type="search" name="nom" id="nom" placeholder="rentrer nom et prénom" required />
	<input style="margin-left: 10%;margin-top: 3%;" type="submit" name="recherche" value="Recherche" />
	<script>
		$(document).ready(function () {
			var items = <?= json_encode($array_autocomplete); ?>
			
			$("#nom").autocomplete({
				source: items			
			});
		});
	</script>
</form>
<div style="padding:5%;">
<?php
$saut = "\n";
$tab = "\t";
	if(isset($_POST['recherche'])){
		$nom = $_POST['nom'];
		$nom = explode(':', $nom);
		$nom[1] = str_replace(' ', '_', $nom[1]);

		if(search_name($nom[0], $nom[1])!=null){
			sleep(1);
			$liste = search_name($nom[0], $nom[1]);
			echo $tab."<div class='photosTable'>".$saut;
			echo "<h2>".str_replace_espace(false,$liste[0]).'_'.str_replace_espace(false,$liste[1])."</h2>".$saut;
			
				echo $tab."<figure>".$saut;	
				echo $tab.$tab."<img style='border:3px solid black;' src='".$liste[3]."' alt='".$_POST['nom']."' />".$saut;				
				echo $tab."<figcaption style='caption-side: bottom; text-align: center;'><em>".$nom[0].'_'.str_replace('_', ' ', $nom[1])."</em></figcaption>".$saut;
				echo $tab."<p style='text-indent: 1em; width: 90%; font-size: 11pt; background: red; opacity: 0.7;'><em>".$liste[2]."</em></p>".$saut;				
				echo $tab."</figure>".$saut;

			echo $tab."</div>".$saut;
		}
		else {
			echo "<p style='color:red;text-align:center'>étudiant non trouvé!</p>";
		}
	}
?>
</div>
</section>
<section>
	<h2>Statistiques:</h2>
	<?php
	if(count(get_groupe("../admin/dataEffectifs.csv")) > 0){
		echo '<div style="padding-bottom:5%;margin-left: 25%;">';
		echo '<img src="../stat/bargraph.php" style="border:3px solid gray" alt="statbar">';
		echo '</div>';
	}
	else {
		echo "<p style='color:red;text-align:center'>Filière vide!</p>\n";
	}
	?>
</section>
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
