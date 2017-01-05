<?php
	//et saada ligi sessioonile
	require("../functions.php");

	require("../class/Helper.class.php");
		$Helper = new Helper();

	require("../class/Note.class.php");
	$Note = new Note($mysqli);

	//ei ole sisseloginud, suunan login lehele
	if(!isset ($_SESSION["userId"])) {
		header("Location: signup.php");
		exit();

	}


	//kas kasutaja tahab välja logida
	//kas aadressireal on logout olemas
	if (isset($_GET["logout"])) {

		session_destroy();

		header("Location: signup.php");
		exit();

	}
if (isset($_POST["note"]) &&
	isset($_POST["color"]) &&
	isset($_POST["r100"]) &&
	isset($_POST["r50"]) &&
	isset($_POST["r20"]) &&
	isset($_POST["r10"]) &&
	isset($_POST["r5"]) &&
	isset($_POST["r2"]) &&
	isset($_POST["r1"]) &&
	!empty($_POST["note"]) &&
	!empty($_POST["color"]) &&
	!empty($_POST["r100"]) &&
	!empty($_POST["r50"]) &&
	!empty($_POST["r20"]) &&
	!empty($_POST["r10"]) &&
	!empty($_POST["r5"]) &&
	!empty($_POST["r2"]) &&
	!empty($_POST["r1"])
) {

		$note = $Helper->cleanInput($_POST["note"]);
		$color = $Helper->cleanInput($_POST["color"]);
		$r100 = $Helper->cleanInput($_POST["r100"]);
		$r50 = $Helper->cleanInput($_POST["r50"]);
		$r20 = $Helper->cleanInput($_POST["r20"]);
		$r10 = $Helper->cleanInput($_POST["r10"]);
		$r5 = $Helper->cleanInput($_POST["r5"]);
		$r2 = $Helper->cleanInput($_POST["r2"]);
		$r1 = $Helper->cleanInput($_POST["r1"]);

		$Note->saveNote($note, $color, $r100, $r50, $r20, $r10, $r5, $r2, $r1);
}

	$q = "";

	if(isset($_GET["q"])){
		$q = $Helper->cleanInput($_GET["q"]);
	}

	//vaikimisi
	$sort = "id";
	$order = "ASC";

	if(isset($_GET["sort"]) && isset($_GET["order"])){
		$sort = $_GET["sort"];
		$order = $_GET["order"];
	}

	$notes = $Note->getAllNotes($q, $sort, $order);
	//echo "<pre>";
	//var_dump($notes);
	//echo "</pre>";
?>

<!DOCTYPE html>
<html>
<head>
<style>
body {
	background-color: MistyRose;
}
p.monospace {
	font-family: "Courier New", Courier, monospace;";
}
</style>
</head>
<body>

<h1 style="text-align:center; font-family:'Courier New', Courier, monospace;">Aruanne</h1>
	<p style="text-align: right;" class="monospace">Tere tulemast <a href="user.php"><?=$_SESSION["userUsername"];?></a>!
	<br>
	<a href="?logout=1">Logi välja</a>
	<br>
	</p>



<form method="POST">

	<label>Kuupäev</label><br>
	<input name="date" type="date">

	<br><br>
	
	<h3 style="font-family:'Courier New', Courier, monospace;">Sularaha</h3>		

	<p><i>Kui rahatäht puudub, sisesta "0".</i></p><br>

	<label>100</label><br>
	<input name="r100" type="text">
	
	<br><br>
			
	<label>50</label><br>
	<input name="r50" type="text">	
			
	<br><br>

	<label>20</label><br>
	<input name="r20" type="text">	
			
	<br><br>
	
	<label>10</label><br>
	<input name="r10" type="text">	
			
	<br><br>

	<label>5</label><br>
	<input name="r5" type="text">	
			
	<br><br>

	<label>2</label><br>
	<input name="r2" type="text">	
			
	<br><br>

	<label>1</label><br>
	<input name="r1" type="text">	
			
	<br><br>	

	<label>Märkmed</label><br>
	<textarea name="note" rows="5" cols="25"></textarea>
	
	<br><br>

	<label>Vali värv</label><br>			
	<input name="color" type="color">

	<br><br>

	<input type="submit" value="Salvesta">
			
		
</form>

<h2 style="text-align:center; font-family:'Courier New', Courier, monospace;">Arhiiv</h2>

<form>
	<input type="search" name="q" value="<?=$q;?>">
	<input type="submit" value="Otsi">
</form>

<?php

	//iga liikme kohta massiiviks
foreach ($notes as $n) {

	$style = "width:200px; float:left; min-height:100px; border:1px solid gray; background-color: ".$n->noteColor.";";

	echo "<p style=' ".$style." '>".$n->note."</p>";

}

?>


<h2 style="clear:both; text-align:left; font-family:'Courier New', Courier, monospace;">Tabel</h2>
<?php

	$html = "<table>";

		$html .= "<tr>";

			$orderId = "ASC";

			if (isset($_GET["order"]) &&
				$_GET["order"] == "ASC" &&
				$_GET["sort"] == "id" ){
				$orderId = "DESC";
			}

			$html .= "<th>
				<a href='?q=".$q."&sort=id&order=".$orderId."'>
					id
				</a>
			</th>";

			$orderNote = "ASC";

			if (isset($_GET["order"]) &&
				$_GET["order"] == "ASC" &&
				$_GET["sort"] == "note" ){
				$orderNote = "DESC";
			}

			$html .= "<th>
				<a href='?q=".$q."&sort=note&order=".$orderNote."'>
					note
				</a>
			</th>";

			$orderColor = "ASC";

			if (isset($_GET["order"]) &&
				$_GET["order"] == "ASC" &&
				$_GET["sort"] == "color" ){
				$orderColor = "DESC";
			}

			$html .= "<th>
				<a href='?q=".$q."&sort=color&order=".$orderColor."'>
					color
				</a>
			</th>";

			$orderR100 = "ASC";

			if (isset($_GET["order"]) &&
				$_GET["order"] == "ASC" &&
				$_GET["sort"] == "r100" ){
				$orderR100 = "DESC";
			}

			$html .= "<th>
				<a href='?q=".$q."&sort=r100&order=".$orderR100."'>
					r100
				</a>
			</th>";

			$orderR50 = "ASC";

			if (isset($_GET["order"]) &&
				$_GET["order"] == "ASC" &&
				$_GET["sort"] == "r50" ){
				$orderR50 = "DESC";
			}

			$html .= "<th>
				<a href='?q=".$q."&sort=r50&order=".$orderR50."'>
					r50
				</a>
			</th>";

			$orderR20 = "ASC";

			if (isset($_GET["order"]) &&
				$_GET["order"] == "ASC" &&
				$_GET["sort"] == "r20" ){
				$orderR20 = "DESC";
			}

			$html .= "<th>
				<a href='?q=".$q."&sort=r20&order=".$orderR20."'>
					r20
				</a>
			</th>";

			$orderR10 = "ASC";

			if (isset($_GET["order"]) &&
				$_GET["order"] == "ASC" &&
				$_GET["sort"] == "r10" ){
				$orderR10 = "DESC";
			}

			$html .= "<th>
				<a href='?q=".$q."&sort=r10&order=".$orderR10."'>
					r10
				</a>
			</th>";

			$orderR5 = "ASC";

			if (isset($_GET["order"]) &&
				$_GET["order"] == "ASC" &&
				$_GET["sort"] == "r5" ){
				$orderR5 = "DESC";
			}

			$html .= "<th>
				<a href='?q=".$q."&sort=r5&order=".$orderR5."'>
					r5
				</a>
			</th>";

			$orderR2 = "ASC";

			if (isset($_GET["order"]) &&
				$_GET["order"] == "ASC" &&
				$_GET["sort"] == "r2" ){
				$orderR2 = "DESC";
			}

			$html .= "<th>
				<a href='?q=".$q."&sort=r2&order=".$orderR2."'>
					r2
				</a>
			</th>";

			$orderR1 = "ASC";

			if (isset($_GET["order"]) &&
				$_GET["order"] == "ASC" &&
				$_GET["sort"] == "r1" ) {
				$orderR1 = "DESC";
			}

			$html .= "<th>
				<a href='?q=".$q."&sort=r1&order=".$orderR1."'>
					r1
				</a>
			</th>";

		$html .= "</tr>";

	foreach ($notes as $note) {
		$html .= "<tr>";
			$html .= "<td>".$note->id."</td>";
			$html .= "<td>".$note->note."</td>";
			$html .= "<td>".$note->noteColor."</td>";
			$html .= "<td>".$note->r100."</td>";
			$html .= "<td>".$note->r50."</td>";
			$html .= "<td>".$note->r20."</td>";
			$html .= "<td>".$note->r10."</td>";
			$html .= "<td>".$note->r5."</td>";
			$html .= "<td>".$note->r2."</td>";
			$html .= "<td>".$note->r1."</td>";
			$html .= "<td><a href='edit.php?id=".$note->id."'>edit.php</a></td>";
		$html .= "</tr>";
	}

	$html .= "</table>";

	echo $html;


?>