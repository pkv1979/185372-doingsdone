<?php
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);
// Массив проектов
$projects = ['Входящие', 'Учеба', 'Работа', 'Домашние дела', 'Авто'];
// Массив задач
$tasks = [
    [
        'taskName' => 'Собеседование в IT компании',
        'dateOfComplection' => '01.12.2019',
        'category' => 'Работа',
        'done' => false
    ],
    [
        'taskName' => 'Выполнить тестовое задание',
        'dateOfComplection' => '25.12.2019',
        'category' => 'Работа',
        'done' => false
    ],
    [
        'taskName' => 'Сделать задание первого раздела',
        'dateOfComplection' => '21.12.2019',
        'category' => 'Учеба',
        'done' => true
    ],
    [
        'taskName' => 'Встреча с другом',
        'dateOfComplection' => '21.12.2019',
        'category' => 'Входящие',
        'done' => false
    ],
    [
        'taskName' => 'Купить корм для кота',
        'dateOfComplection' => 'Нет',
        'category' => 'Домашние дела',
        'done' => false
    ],
    [
        'taskName' => 'Заказать пиццу',
        'dateOfComplection' => 'Нет',
        'category' => 'Домашние дела',
        'done' => false
    ]
];

// Функция для подсчета количества задач в проекте.
// Входные параметры:
//                      $arrayOfTasks - массив задач,
//                      $project      - название проекта.
// Возвращаемое значение: число задач в проекте.
function getCountTasksInProject($arrayOfTasks, $project) {
    $result = 0;

    foreach ($arrayOfTasks as $key => $item) {
        if ($item['category'] === $project) {
            $result++;
        }
    }

    return $result;
}

// Подключение файла functions.php
require ('functions.php');

// HTML код главной страницы
$main_content = include_template('index.php', ['show_complete_tasks' => $show_complete_tasks, 'tasks' => $tasks]);
// Итоговый HTML код
$layout_source = include_template('layout.php', ['title' => 'Дела в порядке', 'projects' => $projects, 'tasks' => $tasks, 'content' => $main_content]);
// Вывод резульата на экран
print($layout_source);