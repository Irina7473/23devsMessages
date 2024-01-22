<?php
if (isset($_SESSION['ruser'])) {
    ?>

    <form action="index.php"
    <?php  if (isset($_GET['page'])) echo '?page=' . $_GET['page'];  ?>
        class="form-inline pull-right" method="post">

        <h4> <span>  <?php echo $_SESSION['ruser']  ?>  </span>
        <input name="exit" type="submit" value="Выйти" class="btn btn-default btn-xs"></h4>
    </form>

    <?php
    if (isset($_POST['exit'])) {
        unset($_SESSION['rid']);
        unset($_SESSION['ruser']);
        unset($_SESSION['radmin']);
        header("Location: index.php?page=1");
        //header("Refresh: 0");
    }

} else {
    if (isset($_POST['gate'])) {
        if (login($_POST['login'], $_POST['pass'])) {
            header("Location: index.php?page=2");
        }
        else {
            header("Location: index.php?page=1");
        }
    } else {
        ?>

        <form action="index.php
        <?php  if (isset($_GET['page'])) echo '?page=' . $_GET['page'];   ?>
        " class="input-group pull-right" method="post">

        <input name="login" type="text" size="20" class="" placeholder="e-mail или телефон">
        <input name="pass" type="password" size="10" class="" placeholder="пароль">
        <input name="gate" type="submit" class="btn btn-default btn-xs" value="Войти">
    </form>

   <?php
    }
}
