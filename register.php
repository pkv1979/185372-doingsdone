<?php

require_once 'functions.php';
require_once 'mysql_helper.php';

// Валидация формы
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$conn = connectDB();

	$form = $_POST;
	$errors = [];

	// Проверка поля Имя
	$name = mysqli_real_escape_string($conn, trim($form['name']));
	if (empty($name)) {
		$errors['name'] = 'Это поле должно быть заполнено';
	}

	// Проверка на валидность e-mail
	$email = mysqli_real_escape_string($conn, trim($form['email']));
	if (empty($email)) {
		$errors['email'] = 'Это поле должно быть заполнено';
	}
	else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$errors['email'] = 'E-mail введён некорректно';
	}
	else {
		$sql = 'select email from user where email = ?';
		$stmt = db_get_prepare_stmt($conn, $sql, [$email]);
		mysqli_stmt_execute($stmt);
		$result = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
		if ($result) {
			$errors['email'] = 'Пользователь с таким E-mail уже существует';
		}
	}

	// Проверка поля пароль
	$password = mysqli_real_escape_string($conn, trim($form['password']));
	if (empty($password)) {
		$errors['password'] = 'Это поле должно быть заполнено';
	}

	if (count($errors) > 0) {
		$main_content = include_template('register.php', ['form' => $form, 'errors' => $errors]);
	}
	else {
		$password = password_hash($password, PASSWORD_DEFAULT);

		$sql = 'insert into user set email = ?, name =?, password = ?';
		$stmt = db_get_prepare_stmt($conn, $sql, [$email, $name, $password]);
		$result = mysqli_stmt_execute($stmt);
		if ($result) {
			header('Location: /');
		}
	}
	mysqli_close($conn);
}
else {
	$main_content = include_template('register.php', []);
}

// Итоговый HTML код
$layout_source = include_template('layout_admin.php', ['title' => 'Регистрация', 'content' => $main_content]);
// Вывод резульата на экран
print($layout_source);