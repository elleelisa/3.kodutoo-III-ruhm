<?php 
 class Note {
 	
     private $connection;
 	
 	function __construct($mysqli){
 		$this->connection = $mysqli;
 	}
 	
 	/* KLASSI FUNKTSIOONID */
     
     function saveNote($note, $color, $r100, $r50, $r20, $r10, $r5, $r2, $r1) {
 		
 		$stmt = $this->connection->prepare("INSERT INTO colorNotes (note, color, r100, r50, r20, r10, r5, r2, r1) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
 		echo $this->connection->error;
 		
 		$stmt->bind_param("ssiiiiiii", $note, $color, $r100, $r50, $r20, $r10, $r5, $r2, $r1 );
 
 		if ( $stmt->execute() ) {
 			echo "salvestamine õnnestus";	
 		} else {	
 			echo "ERROR ".$stmt->error;
 		}
 		
 	}
 	

 	
 	function getAllNotes($q, $sort, $order) {

 		//lubatud tulbad
 		$allowedSort = ["id", "note", "color", "r100", "r50", "r20", "r10", "r5", "r2", "r1"];

 		if(!in_array($sort, $allowedSort)){
 			//ei olnud lubatud tulpade sees
 			$sort = "id"; //las sorteerib id järgi
 		}

 		$orderBy = "ASC";

 		if($order == "DESC") {
 			$orderBy = "DESC";
 		}

 		//echo "sorteerin ".$sort." ".$orderBy." ";

 		//otisme
 		if($q != "") {

 			echo "Otsin: ".$q;

 			$stmt = $this->connection->prepare("
 				SELECT id, note, color, r100, r50, r20, r10, r5, r2, r1
 				FROM colorNotes
 				WHERE deleted IS NULL
 				AND (note LIKE ? OR color LIKE ? OR r100 LIKE ? OR r50 LIKE ? OR r20 LIKE ? OR r10 LIKE ? OR r5 LIKE ? OR r2 LIKE ? OR r1 LIKE ?)
 				ORDER BY $sort $orderBy
 			");
 			$searchWord = "%".$q."%";
 			$stmt->bind_param("ss", $searchWord, $searchWord);

 		}else{
 			//ei otsi
 			$stmt = $this->connection->prepare("
 				SELECT id, note, color, r100, r50, r20, r10, r5, r2, r1
 				FROM colorNotes
 				WHERE deleted IS NULL
 			");
 		}
 		
 		$stmt->bind_result($id, $note, $color, $r100, $r50, $r20, $r10, $r5, $r2, $r1);
 		$stmt->execute();
 		
 		$result = array();
 		
 		// tsükkel töötab seni, kuni saab uue rea AB'i
 		// nii mitu korda palju SELECT lausega tuli
 		while ($stmt->fetch()) {
 			//echo $note."<br>";
 			
 			$object = new StdClass();
 			$object->id = $id;
 			$object->note = $note;
 			$object->noteColor = $color;
 			$object->r100 = $r100;
			$object->r50 = $r50;
			$object->r20 = $r20;
			$object->r10 = $r10;
			$object->r5 = $r5;
			$object->r2 = $r2;
			$object->r1 = $r1;
 			
 			
 			array_push($result, $object);
 			
 		}
 		
 		return $result;
 		
 	}
 	
 	function getSingleNoteData($edit_id){
     		
 		$stmt = $this->connection->prepare("SELECT note, color, r100, r50, r20, r10, r5, r2, r1 FROM colorNotes WHERE id=? AND deleted IS NULL");
 
 		$stmt->bind_param("i", $edit_id);
 		$stmt->bind_result($note, $color, $r100, $r50, $r20, $r10, $r5, $r2, $r1);
 		$stmt->execute();
 		
 		//tekitan objekti
 		$n = new Stdclass();
 		
 		//saime ühe rea andmeid
 		if($stmt->fetch()){
 			// saan siin alles kasutada bind_result muutujaid
 			$n->note = $note;
 			$n->color = $color;
 			$n->r100 = $r100;
			$n->r50 = $r50;
			$n->r20 = $r20;
			$n->r10 = $r10;
			$n->r5 = $r5;
			$n->r2 = $r2;
			$n->r1 = $r1;
 			
 			
 		}else{
 			// ei saanud rida andmeid kätte
 			// sellist id'd ei ole olemas
 			// see rida võib olla kustutatud
 			header("Location: data.php");
 			exit();
 		}
 		
 		$stmt->close();		
 		return $n;
 		
 	}
 
 
 	function updateNote($id, $note, $color, $r100, $r50, $r20, $r10, $r5, $r2, $r1){
 				
 		$stmt = $this->connection->prepare("UPDATE colorNotes SET note=?, color=?, r100=?, r50=?, r20=?, r10=?, r5=?, r2=?, r1=? WHERE id=? AND deleted IS NULL");
 		$stmt->bind_param("ssiiiiiiii",$note, $color, $r100, $r50, $r20, $r10, $r5, $r2, $r1, $id);
 		
 		// kas õnnestus salvestada
 		if($stmt->execute()){
 			// õnnestus
 			echo "salvestus õnnestus!";
 		}
 		
 		$stmt->close();
 		
 	}
 	
 	function deleteNote($id){
 		
 		$stmt = $this->connection->prepare("
 			UPDATE colorNotes 
 			SET deleted=NOW() 
 			WHERE id=? AND deleted IS NULL
 		");
 		$stmt->bind_param("i", $id);
 		
 		// kas õnnestus salvestada
 		if($stmt->execute()){
 			// õnnestus
 			echo "salvestus õnnestus!";
 		}
 		
 		$stmt->close();
 		
 	}
 } 
 ?> 