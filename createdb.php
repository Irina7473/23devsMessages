<?php
/* Вариант 2 */

include_once('pages/classes.php');

ActionsDB::createDB();

?>


<?php
/* Вариант 1 */
/*
include_once('functions.php');
include_once('models/ConnectDB.php');
include_once('models/Message.php');
include_once('models/Comment.php');


$connection = connect();

$pf1 = 'create table roles(
	id int not null auto_increment primary key,
	role varchar(32) not null unique
) default charset="utf8"';

$pf2 = 'INSERT INTO roles (role) VALUES 
            ("admin"), 
            ("user")';

$pf3 = 'create table users(
	id int not null auto_increment primary key,
	login varchar(128) not null unique,
	pass varchar(128) not null,
	phone varchar(128) not null unique,
	email varchar(128) not null unique,
	role_id int DEFAULT "2",
	foreign key(role_id) references roles(id) on delete cascade
) default charset="utf8"';


mysqli_query($connection, $pf1);
$err = mysqli_connect_error();
if ($err) {
    echo 'Error code 1:' . $err . '<br>';
    exit();
}

mysqli_query($connection, $pf2);
$err = mysqli_connect_error();
if ($err) {
    echo 'Error code 2:' . $err . '<br>';
    exit();
}

mysqli_query($connection, $pf3);
$err = mysqli_connect_error();
if ($err) {
    echo 'Error code 2:' . $err . '<br>';
    exit();
}
*/
?>



