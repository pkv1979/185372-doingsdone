<?php

// Подключение файла functions.php
require_once 'functions.php';
require_once 'mysql_helper.php';

$conn = connectDb();
$user = getUser($conn);
$projects = getUserProjects($conn, $user['id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$task = $_POST;

	$required = ['name'];
	$dict = ['name' => 'Название', 'date' => 'Дата выполнения', 'project' => 'Проект'];
	$errors = [];

	// Проверка обязательных полей
	foreach ($required as $key) {
		if (empty($_POST[$key])) {
			$errors[$key] = 'Это поле надо заполнить';
		}
	}

	// Проверка на существование проекта
	$success = false;
	foreach ($projects as $key => $item) {
		if ($item['id'] == $_POST['project_']) {
			$success = true;
			break;
		}
	}
	if (!$success) {
		$errors['project'] = 'Выберите существующий проект';
	}

	// Проверка даты
	if (isset($_POST['date'])) {
		if((strtotime($_POST['date']) - strtotime(date('d.m.Y 00:00:00'))) < 0) {
			$errors['date'] = 'Введите дату не позднее сегодняшней';
		}
	}

	// Загрузка файла
	$filePath = '';
	if (isset($_FILES['preview']['name'])) {
		$tmp = $_FILES['preview']['tmp_name'];
		$filePath =  $_FILES['preview']['name'];
		move_uploaded_file($tmp, $filePath);
	}

	if (count($errors) > 0) {
		$main_content = include_template('add.php', ['projects' => $projects, 'task' => $task, 'errors' => $errors, 'dict' => $dict]);
	}
	else {
		$stmt = mysqli_stmt_init($conn);
		if (empty($_POST['date'])) {
			$sql = 'insert into task set user_id = ?, project_id = ?, name = ?, file_url = ?';
			$stmt = db_get_prepare_stmt($conn, $sql, [$user['id'], $_POST['project_'], $_POST['name'], $filePath]);
		}
		else {
			$sql = 'insert into task set user_id = ?, project_id = ?, name = ?, file_url = ?, term_date = ?';
			$stmt = db_get_prepare_stmt($conn, $sql, [$user['id'], $_POST['project_'], $_POST['name'], $filePath, $_POST['date']]);
		}
		$result = mysqli_stmt_execute($stmt);
		if ($result) {
			header('Location: /');
		}
	}
}
else {
	$main_content = include_template('add.php', ['projects' => $projects]);
}

//mysqli_stmt_close($stmt);
mysqli_close($conn);

// Итоговый HTML код
$layout_source = include_template('layout.php', ['title' => 'Дела в порядке', 'user_name' => $user['name'], 'projects' => $projects, 'content' => $main_content]);
// Вывод резульата на экран
print($layout_source);