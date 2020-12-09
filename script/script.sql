create database todoapp;

use todoapp;

create table task(
	id int primary key auto_increment,
    text varchar(300),
    completed tinyint
);

insert into task(text, completed) values('Create an TodoApp', 0);

select * from task;

delete from task where id = 14;