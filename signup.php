<?php
require_once('connect.php');

$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if(isset($_POST['submit']))
{
	$username = mysqli_real_escape_string($dbc, trim($_POST['username']));
	$password1 = mysqli_real_escape_string($dbc, trim($_POST['password1']));
	$password2 = mysqli_real_escape_string($dbc, trim($_POST['password2']));

	if(!empty($username) && !empty($password1) && !empty($password2) && $password1 == $password2)
	{
		$query = "SELECT * FROM `mismatch_user` WHERE `username` = '$username'";
		$data = mysqli_query($dbc, $query);
		$num = mysqli_num_rows($data);

		if($num == 0)
		// If in Data Base not an account with 
		//this username, we are create new
		{
			$query = "INSERT INTO mismatch_user (username, pass, join_date) VALUES ('$username', SHA('$password1'), NOW())";
			mysqli_query($dbc, $query);
			echo '<p>Your new account was created. You can log in app and 
				<a href="editprofile.php">edit profile</a></p>';

			mysqli_close($dbc);
			exit();
		}
		else // If in DB has profile with this username
		{
			echo '<p>In DB already has profile</p>';
			$username = "";
		}
	}
	else
	{
		echo '<p>Enter all type of data, with password - twice.</p>';
	}
}
mysqli_close($dbc);
?>

<p>Enter your name and password for create a profile in app.</p>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<fieldset><legend>Enter date</legend>
		<label for="username">Username:</label>
		<input type="text" id="username" name="username" value="
		<?php if(!empty($username)) echo $username ?>"> <br>
		<label for="password1">Password:</label>
		<input type="password" id="password1" name="password1"><br>
		<label for="password2">Repeat password:</label>
		<input type="password" id="password2" name="password2"><br>
	</fieldset>
	<input type="submit" value="Create!" name="submit">
</form>