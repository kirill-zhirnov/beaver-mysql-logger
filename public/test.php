<?php

session_start();

class A {
	public function test() {
		echo get_class($this);
	}
}

class B extends A
{

}

class C extends B
{
	public $time = 0;
}

//$class = new C;

//$_SESSION['c'] = $class;
var_dump($_SESSION);
$c = $_SESSION['c'];
//$c->time++;
/*
$a = function() {
	return 'a';
};

$b = function() {
	return 'a';
};

var_dump($a == $b);
return;
*/
//$pdo = new PDO('sqlite:' . __DIR__ . '/protected/db.sq3', null, null);
//$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

/*
$pdo->query('
	create table mysql (
		mysql_id INTEGER PRIMARY KEY,
		mysql_dsn varchar(255) not null,
		mysql_username varchar(255) not null,
		mysql_password varchar(255) not null,
		mysql_options text default null
	)
');
/*
return;
/*
*/
/*
return;

$stmt = $pdo->prepare('
	insert into mysql
		(mysql_dsn, mysql_username, mysql_password, mysql_options)
	values
		(:dsn, :username, :pass, :options)
');
$stmt->execute([
	':dsn' => 'mysql:host=localhost;dbname=mysql',
	':username' => 'root',
	':pass' => 'ghjdthrf',
	':options' => null
]);

$result = $pdo->query('select * from mysql');
foreach ($result as $row)
	var_dump($row);
*/