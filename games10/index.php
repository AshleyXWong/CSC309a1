<?php

	//ini_set('display_errors', 'On');
	require_once "lib/lib.php";
	require_once "model/something.php";

	session_save_path("sess");
	session_start(); 

	$dbconn = db_connect();

	$errors=array();
	$view="";

	/* controller code */
	$navButton = isset($_REQUEST['navButton'])?$_REQUEST['navButton']:"";
	$_SESSION['navButton'] = new NavBar($navButton);
	$_SESSION['currentPage'] = "";
	
	// nav bar code
	if (isset($_REQUEST['registered'])&& $_REQUEST['registered']=="clicked") { $view = "register.php"; $_SESSION['state']="register";}
	if(!isset($_SESSION['state'])){ $_SESSION['state']='login';}
	if ($navButton == "logout") { session_destroy(); $_SESSION['state'] = 'login'; } 
	if ($navButton == "profile") { $_SESSION['state'] = "profile"; } 	
	if ($navButton == "stats") { $_SESSION['state'] = 'stats'; $_SESSION['currentPage'] = "All Stats"; }   
	if ($navButton == "guessgame") {
		if (empty($_SESSION["GuessGame"])) {
			$_SESSION["GuessGame"]=new GuessGame();
		}
		$_SESSION['state']="play";
		$view="play.php";
		$_SESSION['currentPage'] = "Guess Game";
	} 	

	if ($navButton == "frogs") {
		if (empty($_SESSION["frogSpace"])) {
			$_SESSION["frogSpace"]=new FrogGame();
		}
        $_SESSION['state'] = 'frogs';
		$view="frogs.php";
		$_SESSION['currentPage'] = "Frogs";
    } 

    if ($navButton == "rps") {
		if (empty($_SESSION["rpsGame"])) {
			$_SESSION["rpsGame"]=new RPS();
		}
		$_SESSION['state']="rps";
		$view="rps.php";
		$_SESSION['currentPage'] = "Rock Paper Scissors";
    } 

	switch($_SESSION['state']){
		case "login":
			// the view we display by default
			$view="login.php";

			// check if submit or not
			if(empty($_REQUEST['submit']) || $_REQUEST['submit']!="login"){
				break;
			}

			// validate and set errors
			if(empty($_REQUEST['user']))$errors[]='user is required';
			if(empty($_REQUEST['password']))$errors[]='password is required';
			if(!empty($errors))break;

			// perform operation, switching state and view if necessary
			if(!$dbconn){
				$errors[]="Can't connect to db";
				break;
			}

			$loggedIn = validateLogin($dbconn, $_REQUEST['user'], $_REQUEST['password']);
			if ($loggedIn == "loggedin") { 
				$userScores = getScores($dbconn);
				$_SESSION['userScores'] = $userScores;
				$leaderboard = getStats($dbconn);
				$_SESSION['leaderboard'] = $leaderboard;
				$_SESSION['state'] = 'stats'; 
				$view='stats.php'; 
			}
			else { $errors[]="invalid login"; }

			break;

		case "stats":			
			$userScores = getScores($dbconn);
			$_SESSION['userScores'] = $userScores;
			$leaderboard = getStats($dbconn);
			$_SESSION['leaderboard'] = $leaderboard;
			$view="stats.php";
			$_SESSION['currentPage'] = "All Stats";
			break;

		case "play":
			// the view we display by default
			$view="play.php";
			$_SESSION['currentPage'] = "Guess Game";

			// check if submit or not
			if(empty($_REQUEST['submit'])||$_REQUEST['submit']!="guess"){
				break;
			}

			// validate and set errors
			if(!is_numeric($_REQUEST["guess"]))$errors[]="Guess must be numeric.";
			if(!empty($errors))break;

			// perform operation, switching state and view if necessary
			$_SESSION["GuessGame"]->makeGuess($_REQUEST['guess']);
			if($_SESSION["GuessGame"]->getState()=="correct"){
				// adds user score to gamescores database after win
				updateGameScores($dbconn, "guess");
				$_SESSION['state']="won";
				$view="won.php";
			}
			$_REQUEST['guess']="";

			break;
		
		case "frogs":
			$view="frogs.php";
			$_SESSION['currentPage'] = "Frogs";

			$pageWasRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';
			if ($pageWasRefreshed) {
				$_SESSION['frogSpace']=new FrogGame();
				$_REQUEST['frogSpace'] = "";
			}

			// check if submit or not
			if(!isset($_REQUEST['frogSpace'])) {
				$_REQUEST['frogSpace'] = "";
				break;
			}
			if($_REQUEST['frogSpace'] == ""){
				break;
			}

			// validate and set errors
			if(!is_numeric($_REQUEST["frogSpace"]))$errors[]="Position must be numeric.";
			if(!empty($errors))break;

			$_SESSION["frogSpace"]->moveFrog($_REQUEST['frogSpace']);
			if($_SESSION["frogSpace"]->getState($_REQUEST['frogSpace'])=="correct"){
				// adds user score to gamescores database
				updateGameScores($dbconn, "frog");
				$_SESSION['state']="wonFrog";
				$view="wonFrogs.php";
			} 
			$_REQUEST['frogSpace']="";

			break;

		case "wonFrog":
			// the view we display by default
			$view="frogs.php";

			// check if submit or not
			if($_REQUEST['submitFrog']!="start again"){
				$errors[]="Invalid request";
				$view="frogs.php";
			}

			// validate and set errors
			if(!empty($errors))break;


			// perform operation, switching state and view if necessary
			$_SESSION["frogSpace"]=new FrogGame();
			$_SESSION['state']="frogs";
			$view="frogs.php";

			break;

		case "won":
			// the view we display by default
			$view="play.php";

			// check if submit or not
			if(empty($_REQUEST['submit'])||$_REQUEST['submit']!="start again"){
				$errors[]="Invalid request";
				$view="won.php";
			}

			// validate and set errors
			if(!empty($errors))break;

			// perform operation, switching state and view if necessary
			$_SESSION["GuessGame"]=new GuessGame();
			$_SESSION['state']="play";
			$view="play.php";

			break;
		
		case "register":
			$view="register.php";
			// check if submit or not
			if(empty($_REQUEST['submit']) || $_REQUEST['submit']!="login"){
				break;
			}

			// validate all forms
			if(empty($_REQUEST['user']))$errors[]='user is required';
			if(empty($_REQUEST['password'] || empty($_POST["verifypassword"])))$errors[]='password is required';
			if($_REQUEST['password'] != $_REQUEST['verifypassword'])$errors[]='passwords do not match';

			if(empty($_REQUEST["cool"])) $errors[] = "please choose colour";
			if(empty($_REQUEST["choice"])) $errors[] = "please choose colour ";
			if (empty($_REQUEST["agree"])) $errors[] = "Please agree to terms and conditions. It isn't legally binding I swear >.<";
			if(!empty($errors))break;

			// send info to database
			$_SESSION['user'] = $_REQUEST['user'];
			$_SESSION['password'] = $_REQUEST['password'];
			$_SESSION['cool'] = $_REQUEST['cool'];
			$_SESSION['choice'] = $_REQUEST['choice'];

			// store hashed password
			$hashedPassword = hash('sha256', $_REQUEST['password']);;
			$registered = addUserProfile($dbconn, $_SESSION['user'], $hashedPassword, $_SESSION['choice'], $_SESSION['cool']);
			if ($registered == "registered") {
				$userScores = getScores($dbconn);
				$_SESSION['userScores'] = $userScores;
				$leaderboard = getStats($dbconn);
				$_SESSION['leaderboard'] = $leaderboard;
	
				$_SESSION['state'] = 'stats';
				$view = "stats.php";
			} else {
				$errors[] = "user already exists";
			}

			if(!empty($errors))break;

			break;
		
		case "profile":
			$view = "profile.php";
			$_SESSION['currentPage'] = "Profile";
			// check if submit or not
			if(empty($_REQUEST['submit']) || $_REQUEST['submit']!="update profile"){
				break;
			}

			// keep old username for update psql command in case user wants to change their id
			$previousUserID = $_SESSION['user'];
			updateUserData($_SESSION['user']);

			// not necessary for user to re-enter all information. so will change session variables if user enters new data
			if (isset($_REQUEST['user'])) {$_SESSION['user'] = $_REQUEST['user']; }
			if (isset($_REQUEST['password'])) {$_SESSION['password'] = $_REQUEST['password']; }
			if (isset($_REQUEST['cool'])) {$_SESSION['cool'] = $_REQUEST['cool']; }
			if (isset($_REQUEST['choice'])) {$_SESSION['choice'] = $_REQUEST['choice']; }

			// store hashed password
			$hashedPassword = hash('sha256', $_SESSION['password']);;
			updateProfile($dbconn, $_SESSION['user'], $hashedPassword, $_SESSION['choice'], $_SESSION['cool'], $previousUserID);

			break;


		case "unavailable":
			$view="unavailable.php";
			break;
		
		case "logout":
			$view="login.php";
			break;
		
		case "rps":
			$view="rps.php";
			$_SESSION['currentPage'] = "Rock Paper Scissors";

			// check if submit or not
			if(empty($_REQUEST['submit'])||$_REQUEST['submit']!="guess"){
				break;
			}

			// validate and set errors
			if(empty($_REQUEST["choice"]))$errors[]="Please select an option.";
			if(!empty($errors))break;

			// perform operation, switching state and view if necessary
			$_SESSION["rpsGame"]->makeMatches($_REQUEST['choice']);
			if($_SESSION["rpsGame"]->getRPSState()=="you win"){
				updateGameScores($dbconn, "rps");
				$_SESSION['state']="wonRPS";
				$view="wonRPS.php";
			}
			$_REQUEST['guess']="";

			break;

		case "wonRPS":
			// the view we display by default
			$view="rps.php";
			
			// check if submit or not
			if(empty($_REQUEST['submit'])||$_REQUEST['submit']!="start again"){
				$errors[]="Invalid request";
				$view="wonRPS.php";
			}

			// validate and set errors
			if(!empty($errors))break;


			// perform operation, switching state and view if necessary
			$_SESSION["rpsGame"]=new RPS();
			$_SESSION['state']="rps";
			$view="rps.php";

			break;
	}
	require_once "view/view_lib.php";
	require_once "view/$view";
?>
