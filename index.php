<?php
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);
// Массив проектов
$projects = [];
// Массив задач
$tasks = [];
$user = [];

// Подключение файла functions.php
require_once('functions.php');

$conn = mysqli_connect('localhost', 'root', '', 'doinsdone');

if (!$conn) {
    print('Ошибка: Невозможно подключиться к базе данных ' . mysqli_connect_error());
    exit();
}

mysqli_set_charset($conn, "utf8");
$stmt = mysqli_stmt_init($conn);
$id = (int)mysqli_real_escape_string($conn, $_GET['id']);

// Получение имени пользователя
$sql = 'select name from user where id=?';
if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $user);
    mysqli_stmt_fetch($stmt);

    if ($user === null) {
        print('Ошибка: Пользователь не найден.');
        exit();
    }
}

// Получение списка проектов пользователя
$sql = 'select p.name, count(t.name) as count from project p left join task t on p.id = t.project_id where p.user_id=? group by p.name';
if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $projects = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
}

// Получение списка задач пользователя
$sql = 'select name, task_status, term_date from task where user_id=?';
if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $tasks = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);

// HTML код главной страницы
$main_content = include_template('index.php', ['show_complete_tasks' => $show_complete_tasks, 'tasks' => $tasks]);
// Итоговый HTML код
$layout_source = include_template('layout.php', ['title' => 'Дела в порядке', 'user_name' => $user, 'projects' => $projects, 'content' => $main_content]);
// Вывод резульата на экран
print($layout_source);