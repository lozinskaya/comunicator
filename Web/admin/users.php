<?php
require_once 'init.php';
$errorText = "";

if (isset($_POST["action"])) {
  $action = $_POST["action"];
  $user_id = intval($_POST["user_id"]);
  $user = get_user(array("id" => $user_id));


  if ($user) {
    switch ($action) {
      case 'refill':
        $sum = intval($_POST["sum"]);
        if ($sum) {
          $refill = $mdb->insert($mdb->balance_refills, array("user_id" => $user->id, "sum" => $sum, "date" => time()));
          if ($refill) {
            $update_balance = $mdb->update($mdb->users, array("balance" => $user->balance + $sum), array("id" => $user->id));
            $success = $update_balance;
          }
        }
        if($success)
          $errorText = "Баланс успешно пополнен на " . $sum . " ₽";
        else
          $errorText = "Произошла ошибка при пополнении баланса :(";
        break;
      case 'refillUser':
        $firstname = $_POST["firstname"];
        $surname = $_POST["surname"];
        $email = $_POST["email"];
        $pass = $_POST["pass"];
        if ($email) {
          $update_email = $mdb->update($mdb->users, array("email" => $email), array("id" => $user->id));
          $success = $update_email;
        }
        if ($pass) {
          $update_pass = $mdb->update($mdb->users, array("pass" => soltstring($pass)), array("id" => $user->id));
          $success = $update_pass;
        }
        if ($firstname) {
          $update_firstname = $mdb->update($mdb->users, array("firstname" => $firstname), array("id" => $user->id));
          $success = $update_firstname;
        }
        if ($surname) {
          $update_surname = $mdb->update($mdb->users, array("surname" => $surname), array("id" => $user->id));
          $success = $update_surname;
        }
        if($success)
          $errorText = "Пользователь изменен";
        else
          $errorText = "Произошла ошибка при изменении :(";
        break;
      case 'addCardAction':
        $card = $_POST["codeCard"];
        if ($card) {
          $add_card = $mdb->update($mdb->users, array("card_number" => $card), array("id" => $user->id));
          $success = $add_card;
        }
        if($success)
          $errorText = "Карта привязана";
        else
          $errorText = "Произошла ошибка при привязке :(";
        break;
    }
  }
}

$users = db_where($mdb->users, [], [], true);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel=stylesheet type="text/css" href="css/style.css">
  <title>Панель администратора</title>
</head>

<body>
  <div class="menu">
    <div class="nav-item">
      <div class="nav-item-text">
        <text><a href="index.php">Сеансы</a></text>
      </div>
    </div>
    <div class="nav-item active">
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
        <h1>Пользователи</h1>
      </text>
    </div>
    <ul class="nav nav-tabs">
      <li class="nav-item">
        <a class="nav-link active" href="users.php">Клиенты</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="cards.php">Карточки</a>
      </li>
    </ul>
    <div class="container-search">
      <button class="btn btn-default"><img src="img/Search.svg" alt=""></button>
      <input class="form-control" id="myInput" type="text" placeholder="Поиск..">
      <button class="btn btn-default" id="clear"><img src="img/clear.svg" alt=""></button>
    </div>
    <div class="tab-content">
      <div class="tab-pane fade show active" id="Users">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col"><img src="img/card.svg" alt=""></th>
              <th scope="col">Имя</th>
              <th scope="col">Баланс, ₽</th>
              <th scope="col">E-mail</th>
              <th scope="col">Последний сеанс</th>
              <th></th>
            </tr>
          </thead>
          <tbody id="myTable">
            <?php foreach ($users as $user) :
              $last_session = $mdb->get_var("SELECT MAX(`enter_time`) FROM `{$mdb->sessions}` WHERE `user_id` = '{$user->id}'");
              $card_number = $user->card_number
            ?>
              <tr class="usrtbl" data-id="<?= $user->id ?>" data-name="<?= $user->firstname . ' ' . $user->surname ?>" data-balance="<?= $user->balance ?>" data-email="<?= $user->email ?>">
                <td><?= $user->id ?></td>
                <td class="addCard">
                  <?php if ($card_number == 0) : ?>
                    <img data-toggle="modal" data-target="#idModalAddCard-<?= $user->id ?>" src="img/Plus.svg" alt="">
                  <?php else :
                    echo ("..." . substr($card_number, 12, 4));
                  endif ?>
                </td>
                <td><?= $user->firstname . ' ' . $user->surname ?></td>
                <td class="addBalance" data-toggle="modal" data-target="#addBalance-<?= $user->id ?>"><?= $user->balance ?></td>
                <td><?= $user->email ?></td>
                <td><?= $last_session ? date('d.m.Y', $last_session) : "нет" ?></td>
                <td class="editUser" data-toggle="modal" data-target="#editUser-<?= $user->id ?>"><img src="img/Edit.svg" alt=""></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    <nav>

    </nav>
  </div>
  <?php foreach ($users as $user) : ?>
    <form method="POST" action="">
      <input type="hidden" name="user_id" value="<?= $user->id ?>">
      <div class="modal fade add-balance" id="addBalance-<?= $user->id ?>" tabindex="-1" role="dialog" aria-labelledby="ModalLabel2" aria-hidden="true">

        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <div class="modal-title" id="ModalLabel2">
                <h4><?= $user->firstname . ' ' . $user->surname ?></h4>
                <h6>ID <?= $user->id ?></h6>
              </div>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><img src="img/clear.svg" alt=""></span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <input type="number" min="0" name="sum" class="form-control" id="addBalance" placeholder="0 ₽">
                <label for="addBalance">Пополнить баланс</label>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
              <button type="submit" name="action" value="refill" class="btn btn-primary">Пополнить</button>
            </div>
            <div class="modal-footer container-history">
              <div class="history-title">
                <h4>История пополнений</h4>
              </div>
              <?php
              $user_refills = $mdb->get_results("SELECT * FROM `{$mdb->balance_refills}` WHERE `user_id` = '{$user->id}' ORDER BY `date` DESC");
              if (count($user_refills)) :
              ?>
                <table class="table">
                  <tbody class="history-body">
                    <?php foreach($user_refills as $refill) : ?>
                    <tr>
                      <td><?=date('d.m.Y в H:i', $refill->date)?></td>
                      <td>+ <?=$refill->sum?> ₽</td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              <?php else : ?>
                Нет пополнений
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </form>
  <?php endforeach; ?>
  <?php foreach ($users as $user) : ?>
    <form method="POST" action="">
      <input type="hidden" name="user_id" value="<?= $user->id ?>">
      <div class="modal fade" id="editUser-<?= $user->id ?>" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <div class="modal-title" id="ModalLabel">
                <h4><?= $user->firstname . ' ' . $user->surname ?></h4>
                <h6>ID <?= $user->id ?></h6>
              </div>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><img src="img/clear.svg" alt=""></span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label for="inputEmail">Имя</label>
                <input type="text" name="firstname" class="form-control" id="inputfirstname" placeholder="<?= $user->firstname ?>">
              </div>
              <div class="form-group">
                <label for="inputEmail">Фамилия</label>
                <input type="text" name="surname" class="form-control" id="inputsurname" placeholder="<?= $user->surname ?>">
              </div>
              <div class="form-group">
                <label for="inputEmail">E-mail</label>
                <input type="email" name="email" class="form-control" id="inputEmail" placeholder="<?= $user->email ?>">
              </div>
              <div class="form-group">
                <label for="inputPass">Пароль</label>
                <input type="text" name="pass" class="form-control" id="inputPass">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
              <button type="submit" name="action" value="refillUser" class="btn btn-primary">Сохранить</button>
            </div>
          </div>
        </div>
      </div>
</form>
<?php endforeach; ?>
<?php foreach ($users as $user) : ?>
  <form method="POST" action="">
    <input type="hidden" name="user_id" value="<?= $user->id ?>">
    <div class="modal fade idModalAddCard" id="idModalAddCard-<?= $user->id ?>" tabindex="-1" role="dialog" aria-labelledby="ModalLabel3" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <div class="modal-title" id="ModalLabel3">
              <h4><?= $user->firstname . ' ' . $user->surname ?></h4>
              <h6>ID <?= $user->id ?></h6>
            </div>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true"><img src="img/clear.svg" alt=""></span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <input type="text" name="codeCard" class="form-control" id="addCard" pattern="[0-9]{4}\s[0-9]{4}\s[0-9]{4}\s[0-9]{4}" placeholder="XXXX XXXX XXXX XXXX">
              <label for="addCard">Номер карты</label>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
            <button type="submit" name="action" value="addCardAction" class="btn btn-primary">Привязать</button>
          </div>
        </div>
      </div>
    </div>
  </form>
  <?php endforeach; ?>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <script>
    $(document).ready(function() {
      $("#myInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#myTable tr").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
      });

      $('#clear').click(function() {
        $('#myInput').val("");
        location.reload(); // To refresh the page
      });

      /*$(".usrtbl td.editUser img").click(function() {
        console.log("1");
        $("#idModalEditUser").modal('show');
        var getNameFromRow = $(event.target).closest('tr').data('name');
        var getIdFromRow = $(event.target).closest('tr').data('id');
        var getBalanceFromRow = $(event.target).closest('tr').data('balance');
        var getEmailFromRow = $(event.target).closest('tr').data('email');
        $("#idModalEditUser").find('#ModalLabel').html($('<h4>' + getNameFromRow + '</h4>' + '<h6>ID ' + getIdFromRow + '</h6>'));
        $("#inputBalance").val(getBalanceFromRow);
        $("#inputEmail").val(getEmailFromRow);
      });*/

      // $(".usrtbl td.addBalance").click(function() {
      //   console.log("2");
      //   $("#idModalAddBalance").modal('show');
      //   var getNameFromRow = $(event.target).closest('tr').data('name');
      //   var getIdFromRow = $(event.target).closest('tr').data('id');
      //   var getBalanceFromRow = $(event.target).closest('tr').data('balance');
      //   $("#idModalAddBalance").find('#ModalLabel2').html($('<h4>' + getNameFromRow  + '</h4>' + '<h6>ID ' + getIdFromRow  + '</h6>'));
      // });

      /*$(".usrtbl td.addCard img").click(function() {
        console.log("3");
        $("#idModalAddCard").modal('show');
        var getNameFromRow = $(event.target).closest('tr').data('name');
        var getIdFromRow = $(event.target).closest('tr').data('id');
        var getBalanceFromRow = $(event.target).closest('tr').data('balance');
        $("#idModalAddCard").find('#ModalLabel3').html($('<h4>' + getNameFromRow + '</h4>' + '<h6>ID ' + getIdFromRow + '</h6>'));
      });*/

      var addCard = document.querySelector('#addCard');
      for (var i in ['input', 'change', 'blur', 'keyup']) {
        addCard.addEventListener('input', formatCardCode, false);
      }

      function formatCardCode() {
        var cardCode = this.value.replace(/[^\d]/g, '').substring(0, 16);
        cardCode = cardCode != '' ? cardCode.match(/.{1,4}/g).join(' ') : '';
        this.value = cardCode;
      }

      var myInput = document.querySelector('#myInput');
      for (var i in ['input', 'change', 'blur', 'keyup']) {
        myInput.addEventListener('input', showClear, false);
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

    <?php if($errorText) : ?>
    $(window).on('load', function(e) {
      alert('<?=$errorText?>');
    });
    <?php endif; ?>
  </script>
</body>

</html>
