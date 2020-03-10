<?php
require_once('connect.php');
session_start();

$error_msg = "";

if (!isset($_SESSION['user_id'])) {
	if (isset($_POST['submit'])) {
		$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		//Получение введённых пользователем данных для аутентификации
		$user_name = mysqli_real_escape_string($dbc, trim($_POST['username']));
		$user_password = mysqli_real_escape_string($dbc, trim($_POST['password']));
		if (!empty($user_name) && !empty($user_password)) {
			//Поиск имени пользователя и его пароля в БД
			$query = "SELECT id, username FROM mismatch_user 
						WHERE username = '$user_name' AND pass = SHA('$user_password')";
			$data = mysqli_query($dbc, $query);
			if (mysqli_num_rows($data) == 1) {
				//Процедура входа прошла успешно, сохраняем в куки
				//идентификатор пользователя и его имя
				$row = mysqli_fetch_array($data);
				setcookie('user_id', $row['id'], time() + (60 * 60 * 24 * 30));
				setcookie('username', $row['username'], time() + (60 * 60 * 24 * 30));
				$_SESSION['user_id'] = $row['id'];
				$_SESSION['username'] = $row['username'];
				//Переход на главную страницу
				$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
				header('Location: ' . $home_url);
			}
			else {
				//Имя пользователя и/или его пароля введенны неверно,
				//создание сообщения об ошибке
				$error_msg = 'Извините, для того чтобы войти в приложение, вы должны ввести правильное имя и пароль';
			}
		}
		else {
			//Имя пользователя и/или его пароля не введенны
			$error_msg = 'Извините, для того чтобы войти в приложение, вы должны ввести имя и пароль';
		}
	}
}
?>
<html>
	<head>
		<title>Вход в приложение</title>
		<link rel="stylesheet" href="style.css">
	</head>
	<body>
		<h3>Вход в приложение</h3>
		<?php
		//Если куки не содержат данных, выводится сообщенние об ошибке
		//и форма входа в приложение; в противном случае - подтверждение входа
		if (empty($_SESSION['user_id'])) {
			echo '<p class="error">' . $error_msg . '</p>';
		?>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<fieldset>
				<legend>Вход в приложение</legend>
				<label for="username">Имя пользователя</label>
				<input type="text" name="username" id="username" value="<?php echo (!empty($user_name)) ? $user_name : ''; ?>"><br>
				<label for="password">Пароль</label>
				<input type="text" name="password" id="password">
			</fieldset>
			<input type="submit" value="Signin" name="submit">
		</form>
		<?php
		}
		else {
			//Подтверждение успешного входа в приложение
			echo '<p class="login">Вы вошли в приложение как ' . $_SESSION['username'] . '</p>';
		}

		?>
	</body>
</html>