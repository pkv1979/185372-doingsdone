<?php

require_once 'functions.php';
require_once 'mysql_helper.php';

$conn = connectDb();
$user = getUser($conn);
$projects = getUserProjects($conn, $user['id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$task = $_POST;
	$errors = [];
	$sql = 'insert into task set user_id = ?';
	$data = [$user['id']];

	// Проверка поля название
	$name = mysqli_real_escape_string($conn, trim($task['name']));
	if (empty($name)) {
		$errors['name'] = 'Это поле должно быть заполнено';
	}
	else {
		$sql = $sql . ', name = ?';
		$data[] = $name;
	}

	// Проверка на существование проекта
	$success = false;
	$project = (int)mysqli_real_escape_string($conn, $task['project_']);
	$sql = 'select * from user where id = ?';
	$stmt = db_get_prepare_stmt($conn, $sql, [$project]);
	mysqli_stmt_execute($stmt);
	$result = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
	if (!$result) {
		$errors['project'] = 'Выберите существующий проект';
	}
	else {
		$sql = $sql . ', project_id = ?';
		$data[] = $project;
	}
	
	// Проверка даты
	$date = mysqli_real_escape_string($conn, $task['date']);
	if (isset($date)) {
		if(strtotime($date) < mktime(0, 0, 0)) {
			$errors['date'] = 'Введите дату не позднее сегодняшней';
		}
		else {
			$sql = $sql . ', term_date = ?';
			$data[] =  $date;
		}
	}

	// Загрузка файла
	if (isset($_FILES['preview']['name'])) {
		$tmp = $_FILES['preview']['tmp_name'];
		$filePath =  $_FILES['preview']['name'];
		if (move_uploaded_file($tmp, $filePath)) {
			$sql = $sql . ', file_url = ?';
			$data[] = $filePath;
		}
		else {
			$errors['file'] = 'Ошибка загрузки файла';
		}
	}

	if (count($errors) > 0) {
		$main_content = include_template('add.php', ['projects' => $projects, 'task' => $task, 'errors' => $errors]);
	}
	else {
		$stmt = mysqli_stmt_init($conn);
		$stmt = db_get_prepare_stmt($conn, $sql, $data);
		$result = mysqli_stmt_execute($stmt);
		if ($result) {
			header('Location: /');
		}
	}
}
else {
	$main_content = include_template('add.php', ['projects' => $projects]);
}

mysqli_close($conn);

// Итоговый HTML код
$layout_source = include_template('layout.php', ['title' => 'Дела в порядке', 'user_name' => $user['name'], 'projects' => $projects, 'content' => $main_content]);
// Вывод резульата на экран
print($layout_source);