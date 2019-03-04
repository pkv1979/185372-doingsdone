<?php

// Подключение файла functions.php
require_once 'functions.php';

// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);
// Массив задач
$tasks = [];

$guest = empty($_SESSION['id']);

if ($guest) {
   $main_content = include_template('guest.php', []);
   // Итоговый HTML код
   $layout_source = include_template('layout.php', ['title' => 'Дела в порядке', 'guest' => $guest, 'content' => $main_content]);
}
else {

   $conn = connectDB();
   $user = getUser($conn);
   $projects = getUserProjects($conn, $user['id']);
   $tasks = getTasks($conn, $user['id']);
   
   mysqli_close($conn);
   
   // HTML код главной страницы
   $main_content = include_template('index.php', ['show_complete_tasks' => $show_complete_tasks, 'tasks' => $tasks]);
   // Итоговый HTML код
   $layout_source = include_template('layout.php', ['title' => 'Дела в порядке', 'guest' => $guest, 'user_name' => $user['name'], 'projects' => $projects, 'content' => $main_content]);
}

// Вывод резульата на экран
print($layout_source);