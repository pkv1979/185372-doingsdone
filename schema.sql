create database doinsdone COLLATE='utf8_general_ci';

use doinsdone;

create table user (
	id int not null auto_increment,
	email varchar(100) not null,
	name varchar(50) not null,
	password varchar(50) not null,
	primary key(id),
	index uName (name),
	unique index email (email)	
)engine=InnoDB;

create table project (
	id int not null auto_increment,
	user_id int not null,
	name varchar(100),
	primary key(id),
	index pName (name),
	index fkUserProject (user_id),
	constraint fkUserProject foreign key (user_id) references user (id)
)engine=InnoDB;

create table task (
	id int not null auto_increment,
	user_id int not null,
	project_id int not null,
	created_date timestamp not null default current_timestamp,
	complited_date timestamp null default null,
	task_status tinyint not null default 0,
	name varchar(255) not null,
	file_url varchar(100) null default null,
	term_date timestamp null,
	primary key (id),
	index tCreatedDate (created_date),
	index tName (name),
	index tTermDate (term_date),
	index fkUserTask (user_id),
	index fkProjectTask (project_id),
	constraint fkProjectTask foreign key (project_id) references project (id),
	constraint fkUserTask foreign key (user_id) references user (id)
)engine=InnoDB;