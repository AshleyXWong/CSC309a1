<?php
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
				<h1>Welcome to Rock Paper Scissors</h1>
				<?php if($_SESSION["rpsGame"]->getRPSState()!="you win"){ ?>
					<form action="index.php" method="post">
						<input type="radio" id="rock" name="choice" value="rock"/><label for="rock">rock</label>
						<input type="radio" id="paper" name="choice" value="paper"/><label for="paper">paper</label>
						<input type="radio" id="scissors" name="choice" value="scissors"/><label for="scissors">scissors</label>
						<input type="submit" name="submit" value="guess" />
					</form>
				<?php } ?>
				<?php echo(view_errors($errors)); ?> 

				<?php 
					foreach($_SESSION['rpsGame']->history as $key=>$value){
						echo("<br/> $value");
					}
					if($_SESSION["rpsGame"]->getRPSState()=="correct"){ 
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
				<?php echo "Current Guess Game Score: " . $_SESSION['rpsGame']->numGuesses; ?>
			</section>
		</main>
		<footer>
			A project by ME
		</footer>
	</body>
</html>

