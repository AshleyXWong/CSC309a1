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
				<h1>Congratulations! You won!</h1>
				<?php echo(view_errors($errors)); ?>
				<?php 
					foreach($_SESSION['GuessGame']->history as $key=>$value){
						echo("<br/> $value");
					}
				?>
				<form action="index.php" method="post">
					<input type="submit" name="submit" value="start again" />
				</form>
			</section>
			<section class='stats'>
				<h1>Stats</h1>
				<?php echo "This is final score: " . $_SESSION['GuessGame']->numGuesses;?>
			</section>
		</main>
		<footer>
			A project by ME
		</footer>
	</body>
</html>

