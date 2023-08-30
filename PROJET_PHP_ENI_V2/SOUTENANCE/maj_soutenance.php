<?php 
	require("../connectBDD.php");
	if (isset($_POST['matricule']) and isset($_POST['organisme']) and isset($_POST['annee'])) {
		$matricule=$_POST['matricule'];

		$org=$_POST['organisme'];

		$annee=$_POST['annee'];

		$placeholder=$PDO->query("SELECT * FROM soutenir WHERE matricule='$matricule' and idorg='$org' and annee_univ='$annee'");
		$placeholder=$placeholder->fetch(PDO::FETCH_ASSOC);
		if($placeholder==null)
			$message = "<p style=\"color:red;text-align:center;\">Assurez vous que les informations sont correctes</p>";
		/*foreach ($placeholder as $key => $value) {
			echo $value;
		}*/
	}
	if(isset($_POST['btn_modifier']))
	{	
		$matricule=$_POST['matricule'];

		$org=$_POST['organisme'];
		/*$select_org = $PDO->query("SELECT idorg FROM organisme WHERE design='$org'");
		$select_org = $select_org->fetch(PDO::FETCH_ASSOC);
		$org=$select_org['idorg'];*/

		$annee=$_POST['annee'];
		$note=$_POST['note'];

		$pres=$_POST['president'];
		$select_pres=$PDO->query("SELECT idprof FROM professeur WHERE nom='$pres'");
		$select_pres=$select_pres->fetch(PDO::FETCH_ASSOC);
		$pres=$select_pres['idprof'];

		$exam=$_POST['examinateur'];
		$select_exam=$PDO->query("SELECT idprof FROM professeur WHERE nom='$exam'");
		$select_exam=$select_exam->fetch(PDO::FETCH_ASSOC);
		$exam=$select_exam['idprof'];

		$rap_int=$_POST['rapp_int'];
		$select_rap_int=$PDO->query("SELECT idprof FROM professeur WHERE nom='$rap_int'");
		$select_rap_int=$select_rap_int->fetch(PDO::FETCH_ASSOC);
		$rap_int=$select_rap_int['idprof'];

		$rap_ext=$_POST['rapp_ext'];
		$select_rap_ext=$PDO->query("SELECT idprof FROM professeur WHERE nom='$rap_ext'");
		$select_rap_ext=$select_rap_ext->fetch(PDO::FETCH_ASSOC);
		$rap_ext=$select_rap_ext['idprof'];

		$req = $PDO->prepare('UPDATE soutenir SET note = :note,president = :pres,examinateur =:exam, rapporteur_int =:rap_int,rapporteur_ext =:rap_ext WHERE matricule = :matricule and idorg = :organisme and Annee_univ = :annee');
		$req->execute(array(
				'matricule' => $matricule,
				'organisme' => $org,
				'annee' => $annee,
				'note' => $note,
				'pres' => $pres,
				'exam' => $exam,
				'rap_int' => $rap_int,
				'rap_ext' => $rap_ext
				));
		$message ="<p style=\"color:green;text-align:center;\">Modification reussi!</p>";
	}

	$select_nom_prof = $PDO->query("SELECT nom from professeur");
	$select_nom_prof=$select_nom_prof->fetchall(PDO::FETCH_ASSOC);

	function selection_nom_prof($select,$value,$prof,$PDO)
		{
			echo "Je suis dans la fonction</br>";
			echo $prof;

			$nom = $PDO->query("SELECT nom from professeur WHERE idprof='$prof'");
			$nom=$nom->fetch(PDO::FETCH_ASSOC);
			
			foreach($select as $val)
			{
				//echo $val[$value].$nom['nom'];
				if ($val[$value]==$nom['nom']) {
					echo "egale";
					echo "<option value=\"".$val[$value]."\" selected>".$val[$value]."</option>";
				}
				else
					echo "<option value=\"".$val[$value]."\">".$val[$value]."</option>";
				
			}
		}
	?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Mise à jour soutenance</title>
	<link rel="stylesheet" type="text/css" href="styleForme.css">
	<script src="../../jquery-3.6.4.min.js"></script>
	<script type="text/javascript" src="../PROJET_SOUTENANCE/script.js"></script>
</head>
<body>
	<form method="POST" action="maj_soutenance.php">
		<fieldset>
		<legend>Mettre a jour</legend>

		<div>
			<label>Matricule :</label>
			<input type="text" name="matricule" value="<?php if (isset($placeholder['matricule'])) { echo $placeholder['matricule'];}  ?>" required readonly>
		</div>
		<div>
			<label>Organimse</label>
			<input type="text" name="organisme" value="<?php if (isset($placeholder['idorg'])) { echo $placeholder['idorg'];}  ?>" required readonly>
		</div>
		<div>
			<label>Annee Universitaire</label>
			<input type="text" name="annee" value="<?php if (isset($placeholder['annee_univ'])) { echo $placeholder['annee_univ'];}  ?>" required readonly>
		</div>
		
		
			<hr>

		
		<div>
			<label>President(e) :</label>
			<select name="president" required>
				<?php 
					selection_nom_prof($select_nom_prof,"nom",$placeholder['president'],$PDO);
					echo "Fonction passe";
				 ?>
			</select>
		</div>
		<div>
			<label>Examinateur :</label>
			<select name="examinateur" required>
				<?php 
					selection_nom_prof($select_nom_prof,"nom",$placeholder['examinateur'],$PDO);
				 ?>
			</select>
		</div>
		<div>
			<label>Rapporteur interieur :</label>
			<select name="rapp_int" required>
				<?php 
					selection_nom_prof($select_nom_prof,"nom",$placeholder['rapporteur_int'],$PDO);
				 ?>
			</select>
		</div>
		<div>
			<label>Rappoteur exterieur :</label>
			<select name="rapp_ext" required>
				<?php 
					selection_nom_prof($select_nom_prof,"nom",$placeholder['rapporteur_ext'],$PDO);
				 ?>
			</select>
		</div>
		<div>
			<label>Note</label>
			<input type="number" name="note" value="<?php if (isset($placeholder['note'])) { echo $placeholder['note'];}  ?>" min="0" max="20" required/>
		</div>
		<hr>
			<button type="submit" name="btn_modifier">Modifier</button>
		</fieldset>
	</form>
	<?php if (isset($message)) {
		echo $message;
	} ?>
	<a href="listeGenerer.php"><img src="../img/return.png"></a>	

</body>
</html>