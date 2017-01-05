<?php
class User {

	//private $name = "Elle";
	//public $familyName = "Ivantsikova";
	private $connection;

	//käivitatakse siis kui new ja see mis saadakse
	//sulgudess new User(?) see jõuab siia
	function __construct($mysqli){

		//this viitab sellele klassile siin
		//selle klassi muutuja connection
		$this->connection = $mysqli;
	}
	/* KLASSI FUNKTSIOONID */

	function login($username, $password) {

		$notice = "";
		$stmt = $this->connection->prepare("

			SELECT id, email, username, password, created
			FROM Aruanne
			WHERE username = ?

		");
		// asendan ?
		$stmt->bind_param("s", $username);

		//määran muutujad reale mis kätte saan
		$stmt->bind_result($id, $emailFromDb, $usernameFromDb, $passwordFromDb, $created);

		$stmt->execute();

		//ainult SELECT'i puhul
		if ($stmt->fetch()) {

			//vähemalt üks rida tuli
			//kasutaja sisselogimise parool räsiks
			$hash = hash("sha512", $password);
			echo $hash;
			echo $passwordFromDb;
			if ($hash == $passwordFromDb) {
				//õnnestus
				echo "Kasutaja ".$id." logis sisse";

				$_SESSION["userId"] = $id;
				$_SESSION["userEmail"] = $emailFromDb;
				$_SESSION["userUsername"] = $usernameFromDb;

				header("Location: data.php");
				exit();
			} else{
				$notice = "Vale parool!";
			}
		} else {
			//ei leitud ühtegi rida
			$notice = "Sellist emaili/kasutajat ei ole!";
		}

		return $notice;
	
	}

	function signup($email, $username, $password) {
		$stmt = $this->connection->prepare("INSERT INTO Aruanne (email, username, password) VALUES (?, ?, ?)");
		echo $this->connection->error;

		$stmt->bind_param("sss", $email, $username, $password );

		if ( $stmt->execute() ) {
			echo "salvestamine õnnestus";
		} else {
			echo "ERROR ".$stmt->error;
		}
	}
}