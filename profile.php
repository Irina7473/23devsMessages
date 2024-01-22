<?php

if (!isset($_SESSION['ruser'])) exit();

echo '<h3>Ваш профиль</h3>';

if (!isset($_POST['updatebtn'])) {

    $id = $_SESSION['rid'];
    $user = User::fromDb($id);
    ?>

    <form action="index.php?page=2" method="post">

        <div class="form-group">
            <label for="login">Имя</label>
            <input name="login" type="text" class="form-control" placeholder="<?php echo $user->login ?>">
        </div>

        <div class="form-group">
            <label for="phone">Телефон</label>
            <input name="phone" type="tel" class="form-control" placeholder="<?php echo $user->phone ?>">
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input name="email" type="email" class="form-control" placeholder="<?php echo $user->email ?>">
        </div>

        <div class="form-group">
            <label for="pass1">Пароль</label>
            <input name="pass1" type="password" class="form-control" placeholder="****">
        </div>

        <div class="form-group">
            <label for="pass2">Повторите пароль</label>
            <input name="pass2" type="password" class="form-control" placeholder="****">
        </div>
        <button name="updatebtn" type="submit" class="btn btn-primary" >Изменить</button>

    </form>
    <hr>

    <?php
} else {
    // !!Не работает!!

    if (updateUser($_POST['login'], $_POST['phone'], $_POST['email'], $_POST['pass1'], $_POST['pass2'])) {
        echo '<h3><span style="color:green;">Данные изменены!</h3>';
    }
    else echo '<h3><span style="color:blue;">Данные не изменены!</h3>';
}

