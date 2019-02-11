use doinsdone;

insert into user set 
	email = 'Vasyl.Pupkin@testmail.com',
	name = 'Vasyl Pupkin',
	password = 'qwerty';
insert into user set
	email = 'Ivan.Ivanov@testmail.com',
	name = 'Ivan Ivanov',
	password = 'asdfgh';

insert into project set
	name = 'Входящие',
	user_id = 1;
insert into project set
	name = 'Учеба',
	user_id = 1;
insert into project set
	name = 'Работа',
	user_id = 1;
insert into project set
	name = 'Домашние дела',
	user_id = 1;
insert into project set
	name = 'Авто',
	user_id = 1;

insert into task set
	user_id = 1,
	project_id = 3,
	task_status = 0,
	name = 'Собеседование в IT компании',
	term_date = '2019-02-12 00:00:00';
insert into task set
	user_id = 1,
	project_id = 3,
	task_status = 0,
	name = 'Выполнить тестовое задание',
	term_date = '2019-09-14 00:00:00';
insert into task set
	user_id = 1,
	project_id = 2,
	complited_date = '2019-02-09 20:00:00',
	task_status = 1,
	name = 'Сделать задание первого раздела',
	file_url = null,
	term_date = '2019-02-10 00:00:00';
insert into task set
	user_id = 1,
	project_id = 1,
	task_status = 0,
	name = 'Встреча с другом',
	file_url = null,
	term_date = '2019-02-16 00:00:00';
insert into task set
	user_id = 1,
	project_id = 4,
	task_status = 0,
	name = 'Купить корм для кота',
	file_url = null;
insert into task set
	user_id = 1,
	project_id = 4,
	task_status = 0,
	name = 'Заказать пиццу',
	file_url = null;

// Получить список из всех проектов для одного пользователя;
select name from project where user_id = 1;

// Получить список из всех задач для одного проекта
select u.name, p.name, t.created_date, t.complited_date, t.task_status, t.name, t.file_url, t.term_date 
from task t join user u on t.user_id = u.id join project p on t.project_id = p.id where project_id = 3;

// Пометить задачу как выполненную
update task set task_status = 1 where id = 6;

// Обновить название задачи по её идентификатору
update task set name = 'Обязательно купить корм для кота' where id = 5;