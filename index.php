<?php
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);
// Массив проектов
$projects = [];
// Массив задач
$tasks = [];
$current_user = 'Vasyl Pupkin';
$user_id = 0;

// Подключение файла functions.php
require ('functions.php');

$conn = mysqli_connect('localhost', 'root', '', 'doinsdone');

if (!$conn) {
    print('Ошибка: Невозможно подключиться к базе данных ' . mysqli_connect_error());
}
else {
    $sql = "select id from user where name = '" . $current_user . "'";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        print('Ошибка: Невозможно получить данные из таблицы ' . mysqli_connect_error());
    }
    else {
        $user_id = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    $sql = "select name from project where user_id = " . $user_id['id'];
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        print('Ошибка: Невозможно получить данные из таблицы ' . mysqli_connect_error());
    }
    else {
        $projects = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    $sql = "select * from task where user_id = " . $user_id['id'];
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        print('Ошибка: Невозможно получить данные из таблицы ' . mysqli_connect_error());
    }
    else {
        $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}

// HTML код главной страницы
$main_content = include_template('index.php', ['show_complete_tasks' => $show_complete_tasks, 'tasks' => $tasks]);
// Итоговый HTML код
$layout_source = include_template('layout.php', ['title' => 'Дела в порядке', 'projects' => $projects, 'tasks' => $tasks, 'content' => $main_content]);
// Вывод резульата на экран
print($layout_source);