<?php
// So I don't have to deal with unset $_REQUEST['user'] when refilling the form
// You can also take a look at the new ?? operator in PHP7

$_REQUEST['user']=!empty($_REQUEST['user']) ? $_REQUEST['user'] : '';
$_REQUEST['password']=!empty($_REQUEST['password']) ? $_REQUEST['password'] : '';
$_REQUEST['verifypassword']=!empty($_REQUEST['verifypassword']) ? $_REQUEST['verifypassword'] : '';

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
		</header>
		<main>
			<section>
				<h1>Login</h1>
				<form action="index.php" method="post">
					<legend>Login</legend>
					<table>
						<!-- Trick below to re-fill the user form field -->
						<tr><th><label for="user">User</label></th><td><input type="text" name="user" value="<?php echo($_REQUEST['user']); ?>" /></td></tr>
						<tr><th><label for="password">Password</label></th><td> <input type="password" name="password" /></td></tr>
						<tr><th><label for="verifypassword">Verify Password</label></th><td> <input type="password" name="verifypassword" /></td></tr>

						<tr><th><label for="choice">Choose a colour: </label>
						<input type="radio" id="green" name="choice" value="green"/><label for="green">green</label>
						<input type="radio" id="red" name="choice" value="red"/><label for="red">red</label>
						<input type="radio" id="blue" name="choice" value="blue"/><label for="blue">blue</label>
						</td></tr>

						<tr><th>
						<label for="cool">Are you a cool person:</label>
						<select name="cool" id="cool">
							<option value="Yes">Yes</option>
							<option value="No">No</option>
							<option value="Maybe">Maybe</option>
						</select>
						</td><tr>

						<tr><th> <input type="checkbox" id="agreebtn" name="agree"><label for="agree"> I agree to the terms of service</label></td></tr>

						<tr><th>&nbsp;</th><td><input type="submit" name="submit" value="login" /></td></tr>
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

