<?php

class GuessGame {
	public $secretNumber = 5;
	public $numGuesses = 0;
	public $history = array();
	public $state = "";
	public $numGames = 0;

	public function __construct() {
        	$this->secretNumber = rand(1,10);
    	}
	
	public function makeGuess($guess){
		$this->numGuesses++;
		if($guess>$this->secretNumber){
			$this->state="too high";
		} else if($guess<$this->secretNumber){
			$this->state="too low";
		} else {
			$this->state="correct";
		}
		$this->history[] = "Guess #$this->numGuesses was $guess and was $this->state.";
	}

	public function getState(){
		return $this->state;
	}

	public function getNumGames(){
		$this->numGames++; 
		return $this->numGames;
	}

	public function getNumMoves(){
		return $this->numGuesses;
	}
}

class RPS {
	public $numGuesses = 0;
	public $history = array();
	public $state = "";

	public function __construct() {
        	$this->secretNumber = rand(0,3);
    	}
	
	public function makeMatches($guess){
		$this->numGuesses++;
		$comp_index = rand(0,2);
		$computer_options = array("rock", "paper", "scissors");
		$computer = $computer_options[$comp_index];

		if ($guess == "rock") {
			if ($computer == "paper") { 
				$this->state = "you lose";
			} elseif ($computer == "scissors") {
				$this->state = "you win";
			} else {
				$this->state = "you tie";
			}
		} elseif ($guess == "scissors") {
			if ($computer == "rock") { 
				$this->state = "you lose";
			} elseif ($computer == "paper") {
				$this->state = "you win";
			} else {
				$this->state = "you tie";
			}
		} else { 
			if ($computer == "scissors") { 
				$this->state = "you lose";
			} elseif ($computer == "rock") {
				$this->state = "you win";
			} else {
				$this->state = "you tie";
			}
		} 
		$this->history[] = "Guess #$this->numGuesses was $guess and computer was $computer: $this->state.";
	}

	public function getRPSState(){
		return $this->state;
	}
}

class FrogGame {
	public $numGuesses = 0;
	public $state = "";
	public $winState = array(-1, -1, -1, 0, 1, 1, 1);

	public function __construct() {
			$this->board= array(1, 1, 1, 0 , -1, -1, -1);
    	}
	
	// makeGuess will now take user pressed frog and return board position, also if user won
	public function moveFrog($i){
		$this->numGuesses++;
		if($this->move($i,$i+$this->board[$i])) {
			$this->isWon(); 
			return;
		}
		$this->move($i,$i+2*$this->board[$i]);
		$this->isWon();
	}

	public function move($i,$j) {
		if($this->isEmpty($j)){
			$this->board[$j]=$this->board[$i];
			$this->board[$i]=0;
			return true;
		} else {
			return false;
		}
	}
	public function isEmpty($i){
		if($i<0||$i>6)return false;
		if($this->board[$i]==0)return true;
	}

	public function getFrogs() {
		$frogTable = "";
		$currentBoard = $this->board;
		$frogTable .= '<form action="index.php" method="post">';
		//$frogTable .= '<table>';
		$frogTable .= '<ul class="frogs" style="display:flex;justify-content: flex-start;">';
		for ($x=0; $x<=6; $x++) {
			$character = $currentBoard[$x];
			if ($character == 0) {
				$frogTable .= '<li class="frogs" ><a href="?frogSpace=' . $x . '"><img id="square1" width="50" height="50" src="./view/pics/empty.gif";" /></a></li>';
			} elseif ($character == 1) {
				$frogTable .= '<li class="frogs" ><a href="?frogSpace=' . $x . '"><img id="square1" width="50" height="50" src="./view/pics/yellowFrog.gif";" /></a></li>';
			} else {
				$frogTable .= '<li class="frogs" ><a href="?frogSpace=' . $x . '"><img id="square1" width="50" height="50" src="./view/pics/greenFrog.gif";" /></a></li>';
			}
		}
		$frogTable .= '</ul>';
		//$frogTable .= '</table>';
		$frogTable .= '</form>';
		echo $frogTable;
	}

	public function getState() {
		return $this->state;
	}

	public function isWon() {
		$winState = array(-1, -1, -1, 0, 1, 1, 1);
		if ($this->board == $winState) {
			$this->state = "correct";
		} else {
			$this->state = "incorrect";
		}
	}

}

class NavBar {
	public function __construct($option) {
		$this->option = $option;
	}

	public function create_nav($option) {
		// iterate through loop to create nav link one by one 
		// <li><a href="index.php?navButton=stats">All Stats</a></li>
		// will also add class=current to highlight current page on nav bar
		
		$array = array(
				"All Stats" => array("index.php?navButton=stats",""),
				"Guess Game" => array("index.php?navButton=guessgame",""),
				"Rock Paper Scissors" => array("index.php?navButton=rps",""),
				"Frogs" => array("index.php?navButton=frogs",""),
				"Profile" => array("index.php?navButton=profile",""),
				"Logout" => array("index.php?navButton=logout",""),
		);
		if ($option != "") {$array[$option][1] = 'class="current"';}
		$nav="";
		$nav .= '<nav>';
		$nav .= '<ul>';
		foreach ($array as $key=>$value) {
				if ($key == $option) {
					$nav .= '<li> <a ' . $value[1] . 'href="' . $value[0] . '" style="color:gray;">' . $key . '</a> </li>';
				} else {
					$nav .= '<li> <a ' . $value[1] . 'href="' . $value[0] . '">' . $key . '</a> </li>';
				}
		}

		$nav .= '</ul>';
		$nav .= '</nav>';
	   
		echo $nav;
	}
}

        // retrieve userdata for profile from database and set as session variables so view/profile.php can do its magic
        function updateUserData($userID) {
			$query="SELECT userid, password, colour, cool FROM userdata WHERE userid=" . "'" . $userID . "'";
			$result=pg_query($dbconn, $query);
			while ($row = pg_fetch_row($result)) {
					$_SESSION['user'] = $row[0];
					$_SESSION['password'] = $row[1];
					$_SESSION['choice'] = $row[2];
					$_SESSION['cool'] = $row[3];
			}

	}

	//add user when registered to profile database
	function addUserProfile($dbconn, $user, $pass, $colour, $cool) {
			$insert_sells_query="INSERT INTO profileData (userid,password,colour,cool,rps,guess,frog) VALUES($1, $2, $3, $4, $5, $6, $7);";
			$result = pg_prepare($dbconn, "profile_query", $insert_sells_query);
			$result = pg_execute($dbconn, "profile_query", array($user, $pass, $colour, $cool, 0, 0, 0)); 
			if($result){
					$rows_affected=pg_affected_rows($result);
					// successful register of user sets their initial score to zero
					$_SESSION['rps'] = 0;
					$_SESSION['guess'] = 0;
					$_SESSION['frog'] = 0;
					return "registered";
			} else {
					return "failed";
			}
	}

	function getStats($dbconn) {
			$leaderBoard = "";
			$query="SELECT userid, rps FROM gamescores WHERE rps!=0 ORDER BY rps ASC LIMIT 5";
			$result=pg_query($dbconn, $query);
			$leaderBoard .= "Leaderboard for Rock Paper Scissors: <br>";
			while ($row = pg_fetch_row($result)) {
					$leaderBoard .= $row[0] . " " . $row[1] . "<br>";
			}

			$query="SELECT userid, frog FROM gamescores WHERE frog!=0 ORDER BY frog ASC LIMIT 5";
			$result=pg_query($dbconn, $query);
			$leaderBoard .= "Leaderboard for Frog Game: <br>";
			while ($row = pg_fetch_row($result)) {
					$leaderBoard .= $row[0] . " " . $row[1] . "<br>";
			}

			$query="SELECT userid, guess FROM gamescores WHERE guess!=0 ORDER BY guess ASC LIMIT 5";
			$result=pg_query($dbconn, $query);
			$leaderBoard .= "Leaderboard for Guess Game: <br>";
			while ($row = pg_fetch_row($result)) {
					$leaderBoard .= $row[0] . " " . $row[1] . "<br>";
			}

			return $leaderBoard;
	}

	function getScores($dbconn) {
			$_SESSION['rpsScores'] = array();
			$_SESSION['guessScores'] = array();
			$_SESSION['frogScores'] = array();

			$query = "SELECT rps,guess,frog FROM gamescores WHERE userid=$1";
			$result = pg_prepare($dbconn, "queryScore", $query);
			$result = pg_execute($dbconn, "queryScore", array($_SESSION['user']));
			while ($row = pg_fetch_row($result)) {
					if ($row[0] > 0) { array_push($_SESSION['rpsScores'],$row[0]); }
					if ($row[1] > 0) { array_push($_SESSION['guessScores'],$row[1]); }
					if ($row[2] > 0) { array_push($_SESSION['frogScores'],$row[2]); }
			}

			$_SESSION['OtherRpsScores'] = array();
			$_SESSION['OtherGuessScores'] = array();
			$_SESSION['OtherFrogScores'] = array();

			$query = "SELECT rps,guess,frog FROM gamescores WHERE userid!=$1";
			$result = pg_prepare($dbconn, "queryOtherUser", $query);
			$result = pg_execute($dbconn, "queryOtherUser", array($_SESSION['user']));
			while ($row = pg_fetch_row($result)) {
					if ($row[0] > 0) { array_push($_SESSION['OtherRpsScores'],$row[0]); }
					if ($row[1] > 0) { array_push($_SESSION['OtherGuessScores'],$row[1]); }
					if ($row[2] > 0) { array_push($_SESSION['OtherFrogScores'],$row[2]); }
			}
			$userSummary = "";
			$userSummary .= "Best score for rock paper scissors: " . min($_SESSION['rpsScores']) . "<br>";
			$userSummary .= "Best score for frog game: " . min($_SESSION['frogScores']) . "<br>";
			$userSummary .= "Best score for guess game: " . min($_SESSION['guessScores']) . "<br><br>";
			$userSummary .= "Your other game scores for rock paper scissors: ";
			foreach ($_SESSION['rpsScores'] as $key=>$val) {
					$userSummary .= " - " . $val . " - ";
			}
			$userSummary .= "<br>";
			$userSummary .= "Your other game scores for frog game: ";
			foreach ($_SESSION['frogScores'] as $key=>$val) {
					$userSummary .= " - " . $val . " - ";
			}
			$userSummary .= "<br>";
			$userSummary .= "Your other game scores for guess game: ";
			foreach ($_SESSION['guessScores'] as $key=>$val) {
					$userSummary .= " - " . $val . " - ";
			}
			$userSummary .= "<br>";
			$userSummary .= "<br>";

			$userSummary .= "<h1>OTHER USER STATS</h1>";
			$userSummary .= "Best score for rock paper scissors: " . min($_SESSION['OtherRpsScores']) . "<br>";
			$userSummary .= "Best score for frog game: " . min($_SESSION['OtherFrogScores']) . "<br>";
			$userSummary .= "Best score for guess game: " . min($_SESSION['OtherGuessScores']) . "<br>";

			return $userSummary;
	}

	function validateLogin($dbconn, $userID, $password) {
			$query = "SELECT * FROM profileData WHERE userid=$1";
			$result = pg_prepare($dbconn, "queryUser", $query);
			$result = pg_execute($dbconn, "queryUser", array($userID));
			if($row = pg_fetch_row($result)){
					$hashPassword = $row[1];
					if ($hashPassword == hash('sha256', $password)) {
							$_SESSION['user']=$userID;
							$_SESSION['password']=$password;
							$_SESSION['state']='stats';
							$_SESSION['choice'] = $row[2];
							$_SESSION['cool'] = $row[3];
							$_SESSION['rps'] = $row[4];
							$_SESSION['guess'] = $row[5];
							$_SESSION['frog'] = $row[6];
							return "loggedin";
					}
			} else { 
					return "false";
			}
	}

	function updateGameScores($dbconn, $game) {
			$insert_sells_query="INSERT INTO gamescores (userid,rps,guess,frog) VALUES ($1, $2, $3, $4);";
			$result = pg_prepare($dbconn, "game_query", $insert_sells_query);
			if ($game == "frog") {$result = pg_execute($dbconn, "game_query", array($_SESSION['user'], 0, 0, $_SESSION["frogSpace"]->numGuesses)); }
			if ($game == "rps") {$result = pg_execute($dbconn, "game_query", array($_SESSION['user'], $_SESSION["rpsGame"]->numGuesses, 0, 0)); }
			if ($game == "guess") {$result = pg_execute($dbconn, "game_query", array($_SESSION['user'], 0, $_SESSION["GuessGame"]->numGuesses, 0)); }
			if($result){
					$rows_affected=pg_affected_rows($result);
			} else {
					$errors[] = "Failed to update score";
			}
	}

	function updateProfile($dbconn, $user, $pass, $colour, $cool, $previousUserID) {
			$insert_sells_query="UPDATE profileData SET userid=$1, password=$2, colour=$3, cool=$4 WHERE userid=$5;";
			$result = pg_prepare($dbconn, "update_query", $insert_sells_query);
			$result = pg_execute($dbconn, "update_query", array($_SESSION['user'], $hashedPassword, $_SESSION['choice'], $_SESSION['cool'], $previousUserID)); 
			if($result){
					$rows_affected=pg_affected_rows($result);
			} else {
					$errors[] = "Username already chosen";
			}
	}



?>
