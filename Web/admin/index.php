<?php
require_once 'init.php';

$sessions = db_where($mdb->sessions, array("exit_time" => NULL), [], true);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel=stylesheet type="text/css" href="css/style.css">
    <title>Панель администратора</title>
</head>
<body>
    <div class="menu">
        <div class="nav-item active">
            <div class="nav-item-text">
                <text><a href="index.php">Сеансы</a></text>
            </div>
        </div>
        <div class="nav-item">
            <div class="nav-item-text">
                <text><a href="users.php">Пользователи</a></text>
            </div>
        </div>
        <div class="nav-item">
            <div class="nav-item-text">
                <text><a href="future-events.php">Мероприятия</a></text>
            </div>
        </div>
        <div>
            <div class="nav-item-text">
              <img src="img/qr.png" alt="">
            </div>
        </div>
        <div>
            <div class="nav-item-text">
              <img src="img/exit.svg" alt="">
              <text><a href="logout.php">Выход</a></text>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="title">
            <text><h1>Сеансы</h1></text>
        </div>
        <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="index.php">Активные (<?=count($sessions)?>)</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="finish.php">Завершенные</a>
        </li>
        </ul>
        <div class="tab-content">
        <div class="tab-pane fade show active" id="ActivSessions">
        <table class="table">
                <thead>
                    <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Тариф, мин</th>
                    <th scope="col">Кол-во человек</th>
                    <th scope="col">Начало сеанса</th>
                    <th scope="col">Имя клиента</th>
                    <th scope="col">Сумма</th>
                    </tr>
                </thead>
                <tbody id="myTable">
                  <?php
                  $now = time();
                  foreach($sessions as $session) :
                            $tariff = get_tariff($session->tariff_id);
                            $minutes = ceil(($now - $session->enter_time) / 60);
                            $tariff_sum = $tariff->price * $session->count;
                            $session_sum = $tariff_sum * $minutes;
                            $user = get_user(array("id" => $session->user_id));
                    ?>
                    <tr scope="row">
                      <td><?=$session->id?></td>
                      <td><?=$tariff->price?> ₽</td>
                      <td><?=$session->count?></td>
                      <td><?=date('G:i:s', $session->enter_time)?></td>
                      <td><?=$user->firstname . ' ' . $user->surname?></td>
                      <td><?=$session_sum?> ₽</td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        </div>
        <nav>
        </nav>
    </div>
</body>
</html>
