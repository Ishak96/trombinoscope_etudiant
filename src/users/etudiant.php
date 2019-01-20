<?php
if(!isset($_COOKIE['nom']) and !isset($_COOKIE['prenom'])) {
	header('location: ./identificationetudiants.php');
}
setcookie("tentative", '', time() - 3600);
	require_once "../include/functions.inc.php";	
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<title>etudiant</title>
<script src=""></script>
<meta charset="UTF-8"/>
<link rel="shortcut icon" href="../images/etudiant.png" type="images/png" />
<link rel="stylesheet" href="../css/style.css" />
<?php require_once"../css/admincss.php"; ?>
<style>
.info{
	margin: auto;
	text-align: center;
	font-size: 18px;
	width: 80%;
	margin-bottom: 2%;
	opacity: 0.6;
	font-weight: bold;
	background-color: #D6D6D6;
	border: 1px solid black;
}
.error{
	width: 90%;
	margin: auto;
	background-color: #FFC0CB;
	border: 2px solid red;
	margin-bottom: 5%;
	margin-top: 5%;
}
.check{
	width: 90%;
	margin: auto;
	background-color: #99FFAC;
	border: 2px solid green;
	margin-bottom: 5%;
	margin-top: 5%;
}
.message1{
	font-size: 17px;	
}
.message2{
	text-align: justify;
	font-size: 15px;
	font-weight: normal;
	margin-left: 5%;
	margin-right: 5%;	
}
.message1 p{
	text-align: center;
}
td{
	margin-top: 10%;
}
input[type="submit"]{
	border-radius: 0px 0px 0px 0px; 
	border-color: black;
	width: 100%;
}
label{
	border-radius: 0px 0px 0px 0px;
}
.input-label{
	background: url(../images/Upload.png) 4% center no-repeat #570342;
	color: #fff;
	padding-left: 86px;
	text-align: center;
	opacity: 0.6;
	transition: 0.5s;
}
.input-label:hover{
	cursor: pointer;
	opacity: 1;
}
#file{
	display: none;
}
</style>
</head>
<body>
<header>
		<h1 style="position: absolute; margin-left: 40%;margin-top: 3%;">Espace étudiants</h1>
		<img src="../images/logo.jpg " alt="logoucp">
</header>

	<nav>
		<ul>
			<li><a href="../index.html">accueil</a></li>
			<li><a href="./logoutetudiant.php">Déconnexion</a></li>
		</ul>
	</nav>
	<section class="blockLeft">
	<form style="width: 50%; margin: 10% auto;" method="post" action="etudiant.php" enctype="multipart/form-data">
	<fieldset style="background-color: white; color: white;">

	<legend style="color: black;">Charger votre photo :</legend>
		<?php
			echo select("../admin/Informatique");
		?>
	</fieldset>	
	</form>
	<?php
	if(isset($_POST['upload'])){
		if(isset($_POST['filier']) and isset($_FILES['file'])){
			$file = $_FILES['file'];
			
			$groupe_etudiant = explode('/', $_POST['filier']);

			$file_name = $file['name'];
			$file_tmp = $file['tmp_name'];
			$file_size = $file['size'];
			$file_error = $file['error'];		
		
			$nom = $_COOKIE['nom'];
			$prenom = $_COOKIE['prenom'];
			$numero = $_COOKIE['numero'];
			$file_ext = explode('.', $file_name);
			$file_ext = strtolower(end($file_ext));
		
			$fillier = $_POST['filier'];
		
			$file_name_new = strtoupper($nom)."_".$prenom.'.'.$file_ext;
			$file_destination = "../admin/Informatique/".$fillier.'/'.$file_name_new;
			
			if(verify_groupe_etudiant($groupe_etudiant[0], $groupe_etudiant[1], $nom, $prenom, $numero)){
				$has_upload = true;
				upload_image($_POST['filier'],$nom,$prenom,$numero,$file_ext,$file_destination,$file_error,$file_size,$file_tmp);
			}
			else {
				$has_upload = false;
				echo "<p style='color:red;text-align:center'>Tentative de changement de groupe. 
				Veuillez sélectionner votre vrai filière!</p>\n";
			}
		}
		else {
			if(!isset($_POST['filier'])){
				echo "<p style='color:red;text-align:center'>Veuillez sélectionner votre filière!</p>\n";
			}
		}
	}	
	?>
	</section>
	<aside class="blockRight">
		<?php
		$saut = "\n";
		$tab = "\t";
			if(!empty($file_destination) and $has_upload) {
				echo $tab."<figure style='margin: 50px 200px auto;'>".$saut;	
				echo $tab.$tab."<img style='border:3px solid black;' src='".$file_destination."' alt='".$_COOKIE['nom']."' />".$saut;			
				echo $tab."<figcaption style='caption-side: bottom; text-align: center;'><em>".$_COOKIE['nom']."</em></figcaption>".$saut;		
				echo $tab."<figcaption style='caption-side: bottom; text-align: center; background: red; opacity: 0.7;'><em>".date('l-j-m-Y G:m:s')."</em></figcaption>".$saut;			
				echo $tab."</figure>".$saut;				
			}
			else if(verify_pic_nom($_COOKIE['nom'],$_COOKIE['prenom'],$_COOKIE['numero'],$_COOKIE['groupe']) != null){
				$liste = verify_pic_nom($_COOKIE['nom'],$_COOKIE['prenom'],$_COOKIE['numero'],$_COOKIE['groupe']);
				
				echo $tab."<figure style='margin: 50px 200px auto;'>".$saut;	
				echo $tab.$tab."<img style='border:3px solid black;' src='".$liste[3]."' alt='".$_COOKIE['nom']."' />".$saut;			
				echo $tab."<figcaption style='caption-side: bottom; text-align: center;'><em>".$_COOKIE['nom']."</em></figcaption>".$saut;
				echo $tab."<figcaption style='caption-side: bottom; text-align: center; background: red; opacity: 0.7;'><em>".$liste[2]."</em></figcaption>".$saut;		
				echo $tab."</figure>".$saut;
			
			}
		?>
	</aside>
	<footer>
		<p>Site créé par HACHOUD Rassem et AYAD Ishak,le 25/Mars/2018</p>
	<?php 
		echo "<p>Vous êtes connectés en tant que ".$_COOKIE["nom"]." , ".$_COOKIE["prenom"]."</p>";
	?>
	</footer>
</body>
</html>