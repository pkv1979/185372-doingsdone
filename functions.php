<?php

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

// Функция проверяет сколько часов осталось до выполнения задачи.
// Если осталось меньше 24 часов, то возвращает true, если больше или нет даты, то false.
function isDeadlineNow($taskDate) {
	if ($taskDate === '') {
		return false;
	}
	$hourToEnd = floor((strtotime($taskDate) - time()) / 3600);

	return $hourToEnd <= 24 ? true : false;
}