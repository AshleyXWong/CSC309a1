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
				<?php 
				// print out frogs in current order
				if($_SESSION["frogSpace"]->getState()!="correct") {
					$_SESSION["frogSpace"]->getFrogs();
				}
		?>
			</table>
			</section>
			<section class='stats'>
				<h1>Stats</h1>
				<?php echo "Current Guess Game Score: " . $_SESSION['frogSpace']->numGuesses; ?>
			</section>
		</main>
		<footer>
			A project by ME
		</footer>
	</body>
</html>

