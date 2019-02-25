<?php

require_once 'mysql_helper.php';

date_default_timezone_set('Europe/Kiev');

function include_template($name, $data) {
	$name = 'templates/' . $name;
	$result = '';

	if (!is_readable($name)) {
		return $result;
	}

	ob_start();
	extract($data);
	require $name;

	$result = ob_get_clean();

	return $result;
}

// Функция проверяет сколько часов осталось до выполнения задачи.
// Если осталось меньше 24 часов, то возвращает true, если больше или нет даты, то false.
function isDeadlineNow($taskDate) {
	if ($taskDate === '') {
		return false;
	}
	$hourToEnd = floor((strtotime($taskDate) - time()) / 3600);

	return $hourToEnd <= 24 ? true : false;
}

// Подключение к БД
function connectDB() {
	$conn = mysqli_connect('localhost', 'root', '', 'doinsdone');

	if (!$conn) {
    	print('Ошибка: Невозможно подключиться к базе данных ' . mysqli_connect_error());
    	exit();
	}
	mysqli_set_charset($conn, "utf8");

	return $conn;
}

// Получение данных о текущем пользователе
function getUser($conn) {
	//$stmt = mysqli_stmt_init($conn);

	if (isset($_GET['user_id'])) {
    	$id = (int)mysqli_real_escape_string($conn, $_GET['user_id']);    
	}
	else {
    	$id = 1;
	}

	$sql = 'select * from user where id=?';
	$stmt = db_get_prepare_stmt($conn, $sql, [$id]);
    mysqli_stmt_execute($stmt);
    $user = mysqli_fetch_array(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);

    if ($user === null) {
       	print('Ошибка: Пользователь не найден.');
       	exit();
    }

	mysqli_stmt_close($stmt);

	return $user;
}

// Получение списка проектов пользователя
function getUserProjects($conn, $id) {
	$stmt = mysqli_stmt_init($conn);

	$sql = 'select p.id, p.name, count(t.name) as count from project p left join task t on p.id = t.project_id where p.user_id=? group by p.id';
	$stmt = db_get_prepare_stmt($conn, $sql, [$id]);
	mysqli_stmt_execute($stmt);
    $projects = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);

	mysqli_stmt_close($stmt);

	return $projects;
}

// Получение списка задач
function getTasks($conn, $id) {
	$stmt = mysqli_stmt_init($conn);

	if (isset($_GET['project_id'])) {
    	$project_id = (int)mysqli_real_escape_string($conn, $_GET['project_id']);
    	if ($project_id === 0) {
        	header('HTTP/1.1 404 Not Found');
    	}
    	$sql = 'select name, task_status, file_url, term_date from task where user_id=? and project_id=?';
    	$stmt = db_get_prepare_stmt($conn, $sql, [$id, $project_id]);
        mysqli_stmt_execute($stmt);
        $tasks = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
        if (count($tasks) === 0) {
           	header('HTTP/1.1 404 Not Found');
        }
	}
	else {
    	$sql = 'select name, task_status, term_date, file_url from task where user_id=?';
    	$stmt = db_get_prepare_stmt($conn, $sql, [$id]);
        mysqli_stmt_execute($stmt);
        $tasks = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
	}

	mysqli_stmt_close($stmt);

	return $tasks;
}