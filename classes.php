<?php

class ActionsDB
{
    static function connect(
        $host = 'localhost',
        $user = 'root',
        $pass = '',
        $dbname = '23devsmess')
    {
        $cs = 'mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8;';
        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'
        );
        try {
            $pdo = new PDO($cs, $user, $pass, $options);
            return $pdo;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    static function createDB()
    {
        $pdo = ActionsDB::connect();

        $roles = 'create table roles(
	id int not null auto_increment primary key,
	role varchar(32) not null unique
) default charset="utf8"';
        $pdo->exec($roles);

        $rolein = 'INSERT INTO roles (role) VALUES 
            ("admin"), 
            ("user")';
        $pdo->exec($rolein);

        $users = 'create table users(
	id int not null auto_increment primary key,
	login varchar(128) not null unique,
	pass varchar(128) not null,
	phone varchar(128) not null unique,
	email varchar(128) not null unique,
	role_id int DEFAULT "2",
	foreign key(role_id) references roles(id) on delete cascade
) default charset="utf8"';
        $pdo->exec($users);

        $mess = 'create table messages(
	id int not null auto_increment primary key,
	user_id int,
	title varchar(128) not null,
	announce varchar(128) not null,
	content text,
	foreign key(user_id) references users(id) on delete cascade
) default charset="utf8"';

        $pdo->exec($mess);

        $comm = 'create table comments(
	id int not null auto_increment primary key,
	message_id int,
	comment text not null,
	foreign key(message_id) references messages(id) on delete cascade
) default charset="utf8"';

        $pdo->exec($comm);
    }

}

class User
{
    public $id, $login, $pass, $phone, $email, $role_id;

    function __construct($id, $login, $phone, $email, $role_id)
    {
        $this->id = $id;
        $this->login = $login;
        $this->phone = $phone;
        $this->email = $email;
        $this->role_id = $role_id;
    }

    static function addUser($login, $phone, $email, $pass1, $pass2)
    {

        $login = trim(htmlspecialchars($login));
        $phone = trim(htmlspecialchars($phone));
        $email = trim(htmlspecialchars($email));
        $pass1 = trim(htmlspecialchars($pass1));
        $pass2 = trim(htmlspecialchars($pass2));

        if ($login == '' || $phone == '' || $email == '' || $pass1 == '' || $pass2 == '') {
            echo '<h3><span style="color:red;">Заполните все поля!</h3>';
            return false;
        }

        if ($pass1 != $pass2) {
            echo '<h3><span style="color:red;">Пароль и подтверждение не совпадают</h3>';
            return false;
        }

        if (strlen($login) < 3 || strlen($login) > 30) {
            echo '<h3><span style="color:red;">Должно быть от 3 до 30 символов в имени!</h3>';
            return false;
        }

        if (strlen($pass1) < 3 || strlen($pass1) > 30) {
            echo '<h3><span style="color:red;">Должно быть от 3 до 30 символов в пароле!</h3>';
            return false;
        }

        $user =new User ($id = 0, $login, $phone, $email, $role_id=2);
        $user->pass = md5($pass1);

        if ($user->existsLogin($login)) {
            echo '<h3><span style="color:red;">Пользователь с таким именем существует!</h3>';
            return false;
        }

        if ($user->existsPhone($phone)) {
            echo '<h3><span style="color:red;">Пользователь с таким телефоном существует!</h3>';
            return false;
        }
        if ($user->existsEmail($email)) {
            echo '<h3><span style="color:red;">Пользователь с такой почтой существует!</h3>';
            return false;
        }


        try {
            $pdo = ActionsDB::connect();
            $ps = $pdo->prepare("INSERT INTO users (login, pass, phone, email, role_id) 
                            VALUES (:login, :pass, :phone, :email, :role_id)");
            $ar = (array)$user;
            array_shift($ar);
            $ps->execute($ar);
            return true;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    static function fromDb($id)
    {
        try {
            $pdo = ActionsDB::connect();
            $ps = $pdo->prepare("SELECT * FROM users WHERE id=?");
            $ps->execute(array($id));
            $row = $ps->fetch();
            $item = new User($row['id'], $row['login'], $row['phone'], $row['email'], $row['role_id']);
            return $item;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    static function existsLogin($login)
    {
        try {
            $pdo = ActionsDB::connect();
            $ps = $pdo->prepare("SELECT * FROM users WHERE login=?");
            $ps->execute(array($login));
            //$row = $ps->fetch();
            //$item = new User($row['id'], $row['login'], $row['phone'], $row['email'], $row['role_id']);
            if ($ps) return true;
            else return false;

        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    static function existsPhone($phone)
    {
        try {
            $pdo = ActionsDB::connect();
            $ps = $pdo->prepare("SELECT * FROM users WHERE phone=?");
            $ps->execute(array($phone));
            if ($ps) return true;
            else return false;

        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    static function existsEmail($email)
    {
        try {
            $pdo = ActionsDB::connect();
            $ps = $pdo->prepare("SELECT * FROM users WHERE email=?");
            $ps->execute(array($email));
            if ($ps) return true;
            else return false;

        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }


}

class Message
{
    public $id, $user_id, $title, $announce, $content;

    function __construct($id = 0, $user_id, $title, $announce, $content)
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->title = $title;
        $this->announce = $announce;
        $this->content = $content;

    }

    static function addMessage($user_id, $title, $announce, $content)
    {
        $user_id = trim(htmlspecialchars($user_id));
        $title = trim(htmlspecialchars($title));
        $announce = trim(htmlspecialchars($announce));
        $content = trim(htmlspecialchars($content));

        if ($title == '' || $announce == '' || $content == '') {
            echo '<h3><span style="color:red;">Заполните все поля!</h3>';

        } else {
            $mess =new Message ($id = 0, $user_id, $title, $announce, $content);
            try {
                $pdo = ActionsDB::connect();
                $ps = $pdo->prepare("INSERT INTO messages (user_id, title, announce, content) 
                            VALUES (:user_id, :title, :announce, :content)");
                $ar = (array)$mess;
                array_shift($ar);
                $ps->execute($ar);
                return true;
            } catch (PDOException $e) {
                return $e->getMessage();
            }
        }
    }

    static function fromDb($id)
    {
        try {
            $pdo = ActionsDB::connect();
            $ps = $pdo->prepare("SELECT * FROM messages WHERE id=?");
            $ps->execute(array($id));
            $row = $ps->fetch();
            $item = new Message($row['id'], $row['user_id'], $row['title'], $row['announce'], $row['content']);
            return $item;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    static function GetCountMessages()
    {
        try {
            $pdo = ActionsDB::connect();

            $ps = $pdo->prepare("SELECT COUNT(*) FROM messages ");
            $ps->execute();

            while ($row = $ps->fetch()) {
                $count = $row['COUNT(*)'];
            }
            return $count;

        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    static function GetMessagesPerPart($start, $perPart)
    {
        $mess = null;
        try {
            $pdo = ActionsDB::connect();

            $ps = $pdo->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM messages ORDER BY id DESC LIMIT {$start}, {$perPart}");
            $ps->execute();

            while ($row = $ps->fetch()) {
                $item = new Message($row['id'], $row['user_id'], $row['title'], $row['announce'], $row['content']);
                $mess[] = $item;
            }

            foreach ($mess as $m) {
                $ps = $pdo->prepare("SELECT login FROM users WHERE id={$m->user_id}");
                $ps->execute();
                while ($row = $ps->fetch()) $m->user_id = $row['login'];
            }

            return $mess;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }


}

class Comment
{
    public $id, $message_id, $comment;

    function __construct($id = 0, $message_id, $comment)
    {
        $this->id = $id;
        $this->message_id = $message_id;
        $this->comment = $comment;
    }

    static function addComment( $message_id, $comment)
    {
        $message_id = trim(htmlspecialchars($message_id));
        $comment = trim(htmlspecialchars($comment));

        if ($message_id == '' || $comment == '') {
            echo '<h3><span style="color:red;">Заполните все поля!</h3>';
            return false;
        }
        else{
            $com =new Comment($id = 0, $message_id, $comment);
            try {
                $pdo = ActionsDB::connect();
                $ps = $pdo->prepare("INSERT INTO comments (message_id, comment) 
                            VALUES (:message_id, :comment)");
                $ar = (array)$com;
                array_shift($ar);
                $ps->execute($ar);
                return true;
            } catch (PDOException $e) {
                return $e->getMessage();
            }
        }

    }

    static function updComment( $id, $comment)
    {
        $id = trim(htmlspecialchars($id));
        $comment = trim(htmlspecialchars($comment));

        if ($comment == '') {
            echo '<h3><span style="color:darkblue;">empty</h3>';
            return false;
        }
        else{
            try {
                $pdo = ActionsDB::connect();
                $ps = $pdo->prepare("UPDATE comments SET comment = {$comment} WHERE id={$id}");
                $ar = (array)$comment;
                array_shift($ar);
                $ps->execute($ar);
                return true;
            } catch (PDOException $e) {
                return $e->getMessage();
            }
        }
    }

    static function fromDb($id)
    {
        try {
            $pdo = ActionsDB::connect();
            $ps = $pdo->prepare("SELECT * FROM comments WHERE id=?");
            $ps->execute(array($id));
            $row = $ps->fetch();
            $item = new Comment($row['id'], $row['message_id'], $row['comment']);
            return $item;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    static function GetComments($messid)
    {
        if (!$messid) return false;
        $comm = null;
        try {
            $pdo = ActionsDB::connect();
            $ps = $pdo->prepare('SELECT *FROM comments WHERE message_id="' . $messid . '"');
            $ps->execute(array());

            while ($row = $ps->fetch()) {
                $item = new Comment($row['id'], $row['message_id'], $row['comment']);
                $comm[] = $item;
            }
            return $comm;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
}
