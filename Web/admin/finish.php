<?php
require_once 'init.php';

$sessions = $mdb->get_results("SELECT * FROM `{$mdb->sessions}` WHERE `exit_time` IS NOT NULL");
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
            <text>
                <h1>Сеансы</h1>
            </text>
        </div>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <?
          $active_count = $mdb->get_var("SELECT COUNT(*) FROM `{$mdb->sessions}` WHERE `exit_time` IS NULL");
          ?>
                <a class="nav-link" data-toggle="tab" href="index.php">Активные (<?= $active_count ?>)</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="finish.php">Завершенные</a>
            </li>
        </ul>
        <div class="container-filter">
            <span class="heading">Показать по дате</span>
            <div class="filter-buttons">
              <form class="dateFiltr">
                  <div class="form-group">
                      <input type="date" class="form-control" id="inputDateFirst" placeholder="Дата">
                  </div>
                  <div class="form-group">
                      -
                  </div>
                  <div class="form-group">
                      <input type="date" class="form-control" id="inputDateSecond" placeholder="Дата">
                  </div>
                  <div class="form-group">
                      <div class="form-control btn btn-default">Показать</div>
                  </div>
              </form>
              <div class="btn btn-primary">
                <img src="img/report.svg">
                <a href="report.php">Отчет</a>
              </div>
            </div>
        </div>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="FinishSessions">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Тариф, мин</th>
                            <th scope="col">Кол-во человек</th>
                            <th scope="col">Дата</th>
                            <th scope="col">Время сеанса</th>
                            <th scope="col">Длительность</th>
                            <th scope="col">Имя клиента</th>
                            <th scope="col">Сумма</th>
                        </tr>
                    </thead>
                    <tbody id="myTable">
                        <?php
                        foreach ($sessions as $session) :

                            $tariff = get_tariff($session->tariff_id);
                            $user = get_user(array("id" => $session->user_id));
                            $minutes = ceil(($session->exit_time - $session->enter_time) / 60);
                        ?>
                            <tr scope="row">
                                <td><?= $session->id ?></td>
                                <td><?= $tariff->price ?> ₽</td>
                                <td><?= $session->count ?></td>
                                <td class="date" data-date="<?= date('Y-m-d', $session->enter_time) ?>" ><?= date('d.m.Y', $session->enter_time) ?></td>
                                <td><?= date('G:i', $session->enter_time) ?>-<?= date('G:i', $session->exit_time) ?></td>
                                <td><?= $minutes ?> мин</td>
                                <td><?= $user->firstname . ' ' . $user->surname ?></td>
                                <td><?= $session->sum ?> ₽</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <nav>
        </nav>
    </div>
    <script>
    $(document).ready(function(){
      $( ".btn" ).click(function(){ // вызываем функцию при нажатии на элемент с классом parent
        var value_1 = $("#inputDateFirst").val().toLowerCase();
        if (value_1=="") value_1="0000-00-00";
        var value_2 = $("#inputDateSecond").val().toLowerCase();
        if (value_2=="") value_2="9999-12-31";
        $("#myTable tr").filter(function() {
          $(this).toggle(($("#myTable tr .date").data("date")>=value_1 && $("#myTable tr .date").data("date")<=value_2));
        });
       });
      });
    </script>
</body>
</html>
