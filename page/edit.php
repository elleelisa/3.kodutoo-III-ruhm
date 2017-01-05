<?php
	//edit.php
	require("../functions.php");

	require("../class/Helper.class.php");
		$Helper = new Helper();

	require("../class/Note.class.php");
		$Note = new Note($mysqli);

	//kas aadressireal on delete
	if(isset($_GET["delete"])){
		//saadan kaasa aadressirealt id
		$Note->deleteNote($_GET["id"]);
		header("Location: data.php");
		exit();
	}

	//kas kasutaja uuendab andmeid
	if(isset($_POST["update"])){
		$Note->updateNote($Helper->cleanInput($_POST["id"]), $Helper->cleanInput($_POST["note"]), $Helper->cleanInput($_POST["color"]), $Helper->cleanInput($_POST["r100"]), $Helper->cleanInput($_POST["r50"]), $Helper->cleanInput($_POST["r20"]), $Helper->cleanInput($_POST["r10"]), $Helper->cleanInput($_POST["r5"]), $Helper->cleanInput($_POST["r2"]), $Helper->cleanInput($_POST["r1"]));
		header("Location: edit.php?id=".$_POST["id"]."&success=true");
	exit();
	}

	//saadan kaasa id
	$c = $Note->getSingleNoteData($_GET["id"]);
	//var_dump($c);

?>
<?php require("../header.php"); ?>

<br><br>
<a href="data.php"> tagasi </a>

<h2>Muuda kirjet</h2>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
		<input type="hidden" name="id" value="<?=$_GET["id"];?>" >		

	<label for="r100">100</label><br>
	<input id="r100" name="r100" type="text" value="<?=$c->r100;?>">
	
	<br>
			
	<label for="r50">50</label><br>
	<input id="r50" name="r50" type="text" value="<?=$c->r50;?>">
			
	<br>

	<label for="r20">20</label><br>
	<input id="r20" name="r20" type="text" value="<?=$c->r20;?>">
			
	<br>
	
	<label for="r10">10</label><br>
	<input id="r10" name="r10" type="text" value="<?=$c->r10;?>">
			
	<br>

	<label for="r5">5</label><br>
	<input id="r5" name="r5" type="text" value="<?=$c->r5;?>">
			
	<br>

	<label for="r2">2</label><br>
	<input id="r2" name="r2" type="text" value="<?=$c->r2;?>">
			
	<br>

	<label for="r1">1</label><br>
	<input id="r1" name="r1" type="text" value="<?=$c->r1;?>">	
			
	<br>	

	<label for="note">Märkmed</label><br>
	<textarea id="note" name="note"><?php echo $c->note;?></textarea><br>
	
	<br><

	<label for="color">Vali värv</label><br>			
	<input id="color" name="color" type="color" value="<?=$c->color;?>">

	<br>

	<input type="submit" name="update" value="Salvesta">

	</form>

	<br>
	<br>
	<a href="?id=<?=$_GET["id"];?>&delete=true">kustuta</a>
<?php require("../footer.php"); ?>