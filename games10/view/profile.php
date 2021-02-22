<?php
// So I don't have to deal with unset $_REQUEST['user'] when refilling the form
// You can also take a look at the new ?? operator in PHP7

$_REQUEST['user']=!empty($_REQUEST['user']) ? $_REQUEST['user'] : '';
$_REQUEST['password']=!empty($_REQUEST['password']) ? $_REQUEST['password'] : '';
$_SESSION['choice']=!empty($_SESSION['choice']) ? $_SESSION['choice'] : 'black';

?>
<!DOCTYPE html>
<style>
    #colour {
        color: <?php echo($_SESSION['choice']); ?>
    }
</style>
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
				<h1>Profile</h1>
				<form action="index.php" method="post">
					<legend>Enter information you wish to change</legend>
					<table>
						<!-- Trick below to re-fill the user form field -->
						<tr><th><label for="user">Username: </label></th><td><input type="text" name="user" value="<?php echo($_SESSION['user']); ?>" /></td></tr>
						<tr><th><label for="password">Change Password: </label></th><td> <input type="password" name="password" value="<?php echo($_SESSION['password']); ?>" /></td></tr>

						<tr><th>
                        <label for="choice" id="colour"> <br>Chosen colour: <?php echo($_SESSION['choice'] . "<br>"); ?>  </label>
                        <label for="choice"> Are you a cool person: <?php echo($_SESSION['cool'] . "<br><br>"); ?>  </label>
						<?php
							$color = $_SESSION['choice'];
							$cool= $_SESSION['cool'];
						?>
                        <label for="choice" id="colour"> Choose a new colour: </label>
						<input type="radio" id="green" name="choice" value="green" <?php echo ($color=="green"?"checked":"")?>/><label for="green">green</label>
						<input type="radio" id="red" name="choice" value="red"  <?php echo ($color=="red"?"checked":"")?>/><label for="red">red</label>
						<input type="radio" id="blue" name="choice" value="blue" <?php echo ($color=="blue"?"checked":"")?>/><label for="blue">blue</label>
						</td></tr>

						<tr><th>
						<label for="cool">Are you a still cool person:</label>
						<select name="cool" id="cool">
							<option value="Yes"  <?php echo ($cool=="Yes"?"selected":"")?>>Yes</option>
							<option value="No"<?php echo ($cool=="No"?"selected":"")?>> No</option>
							<option value="Maybe"  <?php echo ($cool=="Maybe"?"selected":"")?>>Maybe</option>
						</select>
						</td><tr>

						<tr><th>&nbsp;</th><td><input type="submit" name="submit" value="update profile" /></td></tr>
						<tr><th>&nbsp;</th><td><?php echo(view_errors($errors)); ?></td></tr>

					</table>
				</form>
			</section>
			<section>
			</section>
		</main>
		<footer>
			A project by ME
		</footer>
	</body>
</html>

