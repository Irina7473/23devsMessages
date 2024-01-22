<?php
include_once('functions.php');

if (isset($_SESSION['ruser'])) exit();

echo '<h3>Форма регистрации</h3>';

if (!isset($_POST['regbtn'])) { ?>

    <form action="index.php?page=3" method="post">

        <div class="form-group">
            <label for="login">Имя</label>
            <input name="login" type="text" class="form-control" >
        </div>

        <div class="form-group">
            <label for="phone">Телефон</label>
            <input name="phone" type="tel" class="form-control" >
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input name="email" type="email" class="form-control" >
        </div>

        <div class="form-group">
            <label for="pass1">Пароль</label>
            <input name="pass1" type="password" class="form-control" >
        </div>

        <div class="form-group">
            <label for="pass2">Повторите пароль</label>
            <input name="pass2" type="password" class="form-control" >
        </div>
        <button name="regbtn" type="submit" class="btn btn-primary" >Регистрация</button>

    </form>

    <?php
} else {
    //  !! Не работает!!
    if (User::addUser($_POST['login'], $_POST['phone'], $_POST['email'], $_POST['pass1'], $_POST['pass2'])) {
        echo '<h3><span style="color:green;">Новый пользователь добавлен!</h3>';
    }
/*  Заменила через класс
    if (register($_POST['login'], $_POST['phone'], $_POST['email'], $_POST['pass1'], $_POST['pass2'])) {
        echo '<h3><span style="color:green;">Новый пользователь добавлен!</h3>';
    }
    */
    else{
        echo '<h3><span style="color:red;">Новый пользователь добавлен!</h3>';
    }
}
