<?php
	// So I don't have to deal with uninitialized $_REQUEST['guess']
	$_REQUEST['guess']=!empty($_REQUEST['guess']) ? $_REQUEST['guess'] : '';
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width">
		<link rel="stylesheet" type="text/css" href="style.css" />
		<title>Games</title>
	</head>
	<body>
		<header>
			<?php $_SESSION['navButton']->create_nav($_SESSION['currentPage']); ?>
		</header>
		<main>
			<section>
				<h1>Welcome to Guess Game</h1>
                <?php if($_SESSION["GuessGame"]->getState()!="correct"){ ?>
                    <form action="index.php" method="post">
						<input type="text" name="guess" value="<?php echo($_REQUEST['guess']); ?>" /> <input type="submit" name="submit" value="guess" />
                    </form>
                <?php } ?>
            
                <?php echo(view_errors($errors)); ?> 

                <?php 
                    foreach($_SESSION['GuessGame']->history as $key=>$value){
                        echo("<br/> $value");
                    }
                    if($_SESSION["GuessGame"]->getState()=="correct"){ 
                ?>
                    <form action="index.php" method="post">
                        <input type="submit" name="submit" value="start again" />
                    </form>
                <?php 
                    } 
                ?>
			</section>
			<section class='stats'>
				<h1>Stats</h1>
				<?php 
				echo "Current Guess Game Score: " . $_SESSION['GuessGame']->numGuesses;
				 ?>
			</section>
		</main>
		<footer>
			A project by ME
		</footer>
	</body>
</html>

