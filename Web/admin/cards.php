<?php
require_once 'init.php';
$cards = db_where($mdb->cards, [], [], true);
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel=stylesheet type="text/css" href="css/style.css">
    <title>Карты лояльности</title>
</head>
<body>
    <div class="menu">
        <div class="nav-item">
            <div class="nav-item-text">
                <text><a href="index.php">Сеансы</a></text>
            </div>
        </div>
        <div class="nav-item">
            <div class="nav-item-text active">
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
            <text><h1>Пользователи</h1></text>
        </div>
        <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="users.php">Клиенты</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="cards.php">Карточки</a>
        </li>
        </ul>
        <div class="container-search">
          <button class="btn btn-default"><img src="img/Search.svg" alt=""></button>
          <input class="form-control" id="myInput" name="inputSearch" type="text" placeholder="Поиск..">
          <button class="btn btn-default" id="clear"><img src="img/clear.svg" alt=""></button>
        </div>
        <div class="tab-content">
        <div class="tab-pane fade show active" id="Users">
        <table class="table">
                <thead>
                    <tr>
                      <th scope="col">Номер карты</th>
                      <th scope="col">Баланс, ₽</th>
                    </tr>
                </thead>
                <tbody id="myTable">
                  <?php foreach($cards as $card) : ?>
                    <tr>
                      <td><?=$card->number?></td>
                      <td><?=$card->balance?></tD>
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
      $("#myInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#myTable tr").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
      });

      $('#clear').click(function(){
      $('#myInput').val("");
        location.reload(); // To refresh the page
     })

     var cc = document.querySelector('#myInput');
     for (var i in ['input', 'change', 'blur', 'keyup']) {
         cc.addEventListener('input', showClear, false);
     }
     function showClear() {
       console.log(value != '');
         var value = this.value;
         if (value != '') {
           document.getElementById('clear').style.display = 'block';
         } else {
           document.getElementById('clear').style.display = 'none';
         }
     }
    });
    </script>
</body>
</html>
