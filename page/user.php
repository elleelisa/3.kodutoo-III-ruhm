<?php 
	
	require("../functions.php");

	require("../class/Helper.class.php");
 	$Helper = new Helper();
 	
 	require("../class/Interests.class.php");
 	$Interest = new Interest($mysqli);
	
	//kui ei ole kasutaja id'd
	if (!isset($_SESSION["userId"])){
		
		//suunan sisselogimise lehele
		header("Location: signup.php");
		exit();
	}
	
	
	//kui on ?logout aadressireal siis login välja
	if (isset($_GET["logout"])) {
		
		session_destroy();
		header("Location: signup.php");
		exit();
	}
	
	$msg = "";
	if(isset($_SESSION["message"])){
		$msg = $_SESSION["message"];
		
		//kui ühe näitame siis kustuta ära, et pärast refreshi ei näitaks
		unset($_SESSION["message"]);
	}
	
	
	if ( isset($_POST["interest"]) && 
		!empty($_POST["interest"])
	  ) {
		  
		$Interest->saveInterest($Helper->cleanInput($_POST["interest"]));
		
	}

	//Rippmenüü valik
	if ( isset($_POST["userInterest"]) && 
		!empty($_POST["userInterest"])
	  ) {
		  
		echo $_POST["userInterest"]."<br>";
	
		$Interest->saveUserInterest($Helper->cleanInput($_POST["userInterest"]));
		
	}

    $interests = $Interest->getAllInterests();
	
?>
<?php require("../header.php"); ?>

<a href="data.php"> < tagasi</a> 
<h1 style="text-align:center; font-family:'Courier New', Courier, monospace;">Kasutaja leht</h1>
<?=$msg;?>
<p style="text-align: right;" class="monospace">Tere tulemast <a href="user.php"><?=$_SESSION["userUsername"];?></a>!
	<br>
	<a href="?logout=1">Logi välja</a>
	<br>
	</p>


<h2>Salvesta hobi</h2>
<?php
    
    $listHtml = "<ul>";
	
	foreach($interests as $i){
		
		
		$listHtml .= "<li>".$i->interest."</li>";
	}
    
    $listHtml .= "</ul>";
	
	echo $listHtml;
    
?>
<form method="POST">
	
	<label>Hobi/huviala nimi</label><br>
	<input name="interest" type="text">
	
	<input type="submit" value="Salvesta">
	
</form>



<h2>Kasutaja hobid</h2>
<form method="POST">
	
	<label>Hobi/huviala nimi</label><br>
	<select name="userInterest" type="text">
        <?php
            
            $listHtml = "";
        	
        	foreach($interests as $i){
        		
        		
        		$listHtml .= "<option value='".$i->id."'>".$i->interest."</option>";
        
        	}
        	
        	echo $listHtml;
            
        ?>
    </select>
    	
	
	<input type="submit" value="Lisa">
	
</form>

<?php require("../footer.php"); ?>