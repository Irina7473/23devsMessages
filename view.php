<?php
session_start();
include_once('functions.php');
include_once('classes.php');

if (isset($_GET['id'])) {

    $id = $_GET['id'];
    $mess = Message::fromDb($id);
    if ($mess == null) exit();

    if (!isset($_POST['editmessbtn'])) {
        ?>

        <!-- Просмотр сообщения со списком комментариев -->
        <div class='container-fluid'>

            <?php
            if (isset($_SESSION['ruser'])) {

                ?>
                <form method="post">
                    <button name="editmessbtn" class="btn btn-primary ">Редактировать сообщение</button>
                </form>
                <?php
            }
            echo '<h2>' . $mess->title . '</h2>' .
                '<p>' . $mess->content;
            ?>
            </p>

        </div>

        <?php
        $comments = Comment::GetComments($id);
        if ($comments != null) {
            echo '<h4> Комментарии </h4>';
            foreach ($comments as $com) { ?>

                <form method="post">
                    <div class="form-group">
                        <input type="hidden" name="com_id" value=" <?php echo $com->id; ?> ">
                        <input name="updcomment" type="text" class="form-control"
                               value=" <?php echo $com->comment; ?> ">
                        <button name="updcombtn" type="submit" class="btn btn-primary">Изменить</button>
                    </div>
                </form>

            <?php }
        }
        ?>

        <!-- Форма добавления комментария -->
        <form method="post">
            <div class="form-group">
                <input name="comment" type="text" class="form-control" placeholder="Новый комментарий">
                <button name="addcombtn" type="submit" class="btn btn-primary">Добавить</button>
            </div>
        </form>

        <?php
        // Изменение комментария
        if (isset($_POST['updcombtn'])) {
            Comment::updComment($_POST['com_id'], $_POST['updcomment']);
            //Заменила
            //updComment($_POST['com_id'], $_POST['updcomment']);
            header("Refresh: 0");
        }
        //Добавление комментария
        if (isset($_POST['addcombtn'])) {
            if (Comment::addComment($id, $_POST['comment'])) {
                header("Refresh: 0");
            }
        }

        /* Заменила через класс
        if (isset($_POST['addcombtn'])) {
            addComment($id, $_POST['comment']);
            header("Refresh: 0");
        }
        */
    } else {
        ?>

        <!-- Форма редактирования сообщения -->
        <div class='container-fluid'>
            <form method="post">
                <div class="form-group">

                    <button name="updmessbtn" type="submit" class="btn btn-primary">Сохранить</button>

                    <input type="hidden" name="user_id" value="1">

                    <div class="form-group">
                        <label for="updtitle">Заголовок</label>
                        <input name="updtitle" type="text" class="form-control"
                               placeholder=" <?php echo $mess->title; ?> ">
                    </div>

                    <div class="form-group">
                        <label for="updannounce">Краткое содержание</label>
                        <input name="updannounce" type="text" class="form-control"
                               placeholder=" <?php echo $mess->announce; ?> ">
                    </div>

                    <div class="form-group">
                        <label for="updcontent">Текст сообщения</label>
                        <input name="updcontent" type="text" class="form-control"
                               placeholder=" <?php echo $mess->content; ?> ">
                    </div>
                </div>
            </form>
        </div>

        <?php
    }
//Изменение сообщения
    if (isset($_POST['updmessbtn'])) {
        updMessage($id, $_POST['updtitle'], $_POST['updannounce'], $_POST['updcontent']);
        header("Refresh: 0");
    }
} else {
    if (isset($_SESSION['ruser'])) {
        ?>

        <!-- Форма добавления сообщения -->
        <div class="container-fluid">
            <h4> Добавить новое сообщение </h4>
            <form method="post">
                <div class="form-group">

                    <button name="addmessbtn" type="submit" class="btn btn-primary">Сохранить</button>

                    <input type="hidden" name="user_id" value="1">

                    <div class="form-group">
                        <label for="title">Заголовок</label>
                        <input name="title" type="text" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="announce">Краткое содержание</label>
                        <input name="announce" type="text" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="content">Текст сообщения</label>
                        <input name="content" type="text" class="form-control">
                    </div>
                </div>
            </form>
        </div>

        <hr>

        <?php
        //Добавление сообщения

        if (isset($_POST['addmessbtn'])) {

            if (Message::addMessage($_POST['user_id'], $_POST['title'], $_POST['announce'], $_POST['content'])) {
                echo '<h3><span style="color:darkgreen;"> Сообщение добавлено </h3>';
                header("/index.php?page=2");
            }
        }

        /*  Заменила через класс
        if (isset($_POST['addmessbtn'])) {
            if (addMessage($_POST['user_id'], $_POST['title'], $_POST['announce'], $_POST['content'])) {
                header("index.php?page=2");
                echo '<h3><span style="color:darkgreen;"> Сообщение добавлено </h3>';
            }
        }
        */
    }
}
?>

<hr>
<a href='../index.php?page=2'>Все сообщения</a>
