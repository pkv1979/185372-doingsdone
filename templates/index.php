<h2 class="content__main-heading">Список задач</h2>

<form class="search-form" action="index.php" method="post">
    <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

    <input class="search-form__submit" type="submit" name="" value="Искать">
</form>

<div class="tasks-controls">
    <nav class="tasks-switch">
        <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
        <a href="/" class="tasks-switch__item">Повестка дня</a>
        <a href="/" class="tasks-switch__item">Завтра</a>
        <a href="/" class="tasks-switch__item">Просроченные</a>
    </nav>

    <label class="checkbox">
        <input class="checkbox__input visually-hidden show_completed" type="checkbox" <?=$show_complete_tasks === 1 ? 'checked' : ''; ?>>
        <span class="checkbox__text">Показывать выполненные</span>
    </label>
</div>

<table class="tasks">
    <?php foreach ($tasks as $key => $item): ?>
        <?php if ($show_complete_tasks === 1 or !$item['task_status']): ?>
            <tr class="tasks__item task 
                <?php if ($item['task_status']) {
                  print('task--completed');  
                } else if (isDeadlineNow($item['term_date'])) { 
                    print('task--important');
                } 
                ?>">
                <td class="task__select">
                    <label class="checkbox task__checkbox">
                        <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="<?=$key; ?>" <?=$item['task_status']? 'checked' : '' ?>>
                        <span class="checkbox__text"><?=$item['name'];?></span>
                    </label>
                </td>

                <?php if(isset($item['file_url'])): ?>
                    <td class="task__file">
                        <a class="download-link" href="<?=$item['file_url'];?>"><?=$item['file_url'];?></a>
                    </td>
                <?php else: ?>
                    <td></td>
                <?php endif; ?>

                <td class="task__date"><?=$item['term_date'] == '' ? '' : date("d.m.Y", strtotime($item['term_date']));?></td>
            </tr>
        <?php endif; ?>
    <?php endforeach; ?>
</table>
