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
				<h1>USER STATS</h1>
				<!-- get user scores for all games from database and print it out -->
				<?php echo $_SESSION['userScores']; ?>
			</section>
			<section class='stats'>
				<h1>Leadership Board</h1>
				<!-- get leaderboard scores for all games from database and print it out -->
				<?php echo $_SESSION['leaderboard']; ?>
			</section>
		</main>
		<footer>
			A project by ME
		</footer>
	</body>
</html>

