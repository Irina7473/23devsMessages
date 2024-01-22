<h3>Сообщения пользователей</h3>
<div class="container-fluid">

    <div class="row mb-5" style="margin-top:20px; font-size:15pt;">

        <?php

        $part = isset($_GET['part']) ? (int)$_GET['part'] : 1;
        $perPart = isset($_GET['per-part']) && $_GET['per-part'] <= 50 ? (int)$_GET['per-part'] : 5;
        $start = ($part > 1) ? ($part * $perPart) - $perPart : 0;

        $items = Message::GetMessagesPerPart($start, $perPart);

        $total = Message::GetCountMessages();
        $parts = ceil($total / $perPart);
        ?>

        <div class="col-md-12">
            <?php foreach ($items as $item): ?>
                <div>
                    <div>
                <span>
                    <?php echo $item->user_id ?>
                </span>
                        <?php echo "<a href='pages/view.php?id=" . $item->id . "' >";
                        echo $item->title;
                        ?>
                        </a>
                        <p> <?php echo $item->announce; ?> </p>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
        <hr>
        <div class="col-md-12">
            <div class="well well-sm">
                <h4>Страницы:</h4>
                <div class="paginate">

                    <?php for ($x = 1; $x <= $parts; $x++): ?>

                        <ul class="pagination">
                            <li>
                                <a type="submit"
                                   href="index.php?page=2&part=<?php echo $x; ?>&per-part=<?php echo $perPart; ?>">
                                    <?php echo $x; ?>
                                </a>
                            </li>
                        </ul>

                    <?php endfor  ; ?>
                </div>
            </div>
        </div>

<?php
        if (isset($_SESSION['ruser'])) {
            ?>

        <p><a href='pages/view.php' class='pull-right' style="margin-top:20px; font-size:15pt;">
                Добавить сообщение</a></p>

<?php  }  ?>