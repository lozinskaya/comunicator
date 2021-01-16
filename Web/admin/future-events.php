<?php
require_once 'init.php';
$errorText = "";

if (isset($_POST["action"])) {
  $action = $_POST["action"];
  $event_id = intval($_POST["event_id"]);
    switch ($action) {
      case 'create':
        $title = pil_string($_POST["title"]);
        if($title) {
        $description = pil_string($_POST["description"]);
        $date_time = DateTime::createFromFormat("Y-m-d H:i", $_POST["date_event"] . " " .$_POST["time_event"])->getTimestamp();
        $limit_persons = intval($_POST["limit_persons"]);
        $image_id = 0;
        if (check_upload('image')) {
          $upload_photo = ImageAttachment::upload($_FILES['image'], $account_id);
          if ($upload_photo == ImageAttachment::UPLOAD_SUCCESS) {
            $image_id = ImageAttachment::$uploaded_id;
          }
      }
        $add = $mdb->insert($mdb->events, array("title" => $title, "description" => $description, "date_time" => $date_time, "limit_persons" => $limit_persons, "image_id" => $image_id));
        }
        if($add)
          $errorText = "Мероприятие добавлено";
        else
          $errorText = "Произошла ошибка при добавлении мероприятия :(";
        break;
        case 'delete':
          if($event_id) {
            $success = $mdb->delete($mdb->events, array("id" => $event_id));
            if($success)
              $errorText = "Мероприятие удалено";
            else
              $errorText = "Произошла ошибка при удалении мероприятия :(";
          }
        break;
    }
}
$now = time();
$events = $mdb->get_results("SELECT * FROM `{$mdb->events}` WHERE `date_time` >= '{$now}'");
?><!DOCTYPE html>
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
        <div class="nav-item">
            <div class="nav-item-text">
                <text><a href="users.php">Пользователи</a></text>
            </div>
        </div>
        <div class="nav-item active">
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
            <text><h1>Мероприятия</h1></text>
        </div>
        <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" href="future-events.php">Предстоящие</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="finish-events.php">Завершенные</a>
        </li>
        </ul>
        <div class="container-filter">
            <span class="heading">Показать по названию</span>
            <div class="filter-buttons">
              <form class="dateFiltr">
                  <div class="form-group">
                      <input type="text" class="form-control" id="inputTitleFilter" placeholder="Название">
                  </div>
                  <div class="form-group">

                  </div>
              </form>
              <div class="btn btn-primary"  class="addBalance" data-toggle="modal" data-target="#addEventModal" id="addEvent">
                Добавить мероприятие
              </div>
            </div>
        </div>
        <div class="tab-content">
        <div class="tab-pane fade show active" id="Users">
        <table class="table">
                <thead>
                    <tr>
                      <th scope="col">Название</th>
                      <th scope="col">Дата</th>
                      <th scope="col">Время</th>
                      <th scope="col">Краткое описание</th>
                      <th scope="col">MAX человек</th>
                      <th scope="col">Участники</th>
                      <th></th>
                    </tr>
                </thead>
                <tbody id="TableEvents">
                  <?php foreach($events as $event) :
                    ?>
                    <tr class="usrtbl" data-title="<?=$event->title?>" data-date="<?=date('d.m.Y', $event->date_time)?>" data-time="<?=date('H:i', $event->date_time)?>" data-description="<?=$event->description?>" data-count="<?=$mdb->get_var("SELECT COUNT(*) FROM `{$mdb->events_regs}` WHERE `event_id` = '{$event->id}'")?>">
                      <td><?=$event->title?></td>
                      <td>
                        <?=date('d.m.Y', $event->date_time)?>
                      </td>
                      <td><?=date('H:i', $event->date_time)?></td>
                      <td><?=$event->description?></td>
                      <td><?=$event->limit_persons ? $event->limit_persons : "∞" ?></td>
                      <td><?=$mdb->get_var("SELECT COUNT(*) FROM `{$mdb->events_regs}` WHERE `event_id` = '{$event->id}'")?></td>
                      <td class="editUser" style="display: flex;justify-content: space-between;">
                        <img class="edit-link" src="img/Edit.svg" alt="">
                        <a href="<?=OFFICE_URI?>event-members.php?event_id=<?=$event->id?>" target="_blank"><img src="img/Paper.svg" alt=""></a>
                        <img data-toggle="modal" data-target="#deleteEvent-<?= $event->id ?>" src="img/delete_event.svg" alt="">
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        </div>
        <nav>

        </nav>
    </div>

    <?php foreach($events as $event) : ?>
      <form method="POST" action="">
        <input type="hidden" name="event_id" value="<?=$event->id?>">
      <div class="modal fade delete-event" id="deleteEvent-<?=$event->id?>" tabindex="-1" role="dialog" aria-labelledby="ModalLabel3" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="border: 0px;">
            <div class="modal-title" id="ModalLabel3"><h4>Удаление мероприятия</h4></div>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true"><img src="img/clear.svg" alt=""></span>
            </button>
          </div>
          <div class="modal-body">
            Вы уверены, что хотите удалить мероприятие «<?=$event->title?>»?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
            <button type="submit" name="action" value="delete" class="btn btn-danger">Удалить</button>
          </div>
        </div>
      </div>
    </div>
    </form>
    <?php endforeach; ?>
      <form method="POST" enctype="multipart/form-data" action="">
        <div class="modal fade" id="addEventModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <div class="modal-title" id="ModalLabel"><h4>Добавление мероприятия</h4></div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true"><img src="img/clear.svg" alt=""></span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                    <label for="inputTitle">Название</label>
                    <input type="text" name="title" class="form-control" id="inputTitle" placeholder="Название">
                </div>
                <div class="form-group">
                    <label for="">Изображение</label></br>
                    <label for="inputImg" class="btn btn-primary">
                        Загрузить
                    </label>
                    <label class="inputFileName" for=""></label>
                    <input name="image" id="inputImg" type="file"/>
                </div>
                <div class="form-group">
                    <label for="inputTime">Время</label>
                    <input type="time" name="time_event" class="form-control" id="inputTime" placeholder="Время">
                </div>
                <div class="form-group">
                    <label for="inputDate">Дата</label>
                    <input type="date" name="date_event" class="form-control" id="inputDate">
                </div>
                <div class="form-group">
                    <label for="inputDescription">Описание</label>
                    <textarea rows="10" cols="45" type="text" name="description" class="form-control" id="inputDescription" placeholder="Описание"></textarea>
                </div>
                <div class="form-group">
                    <label for="inputCount">Количество участников</label>
                    <input type="number" name="limit_persons" class="form-control" id="inputCount" placeholder="Количество участников">
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
                <button type="submit" name="action" value="create" class="btn btn-primary">Сохранить</button>
              </div>
            </div>
          </div>
        </div>
      </form>
    <div class="modal fade" id="idModalEditEvent" tabindex="-1" role="dialog" aria-labelledby="ModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <div class="modal-title" id="ModalLabel"></div>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true"><img src="img/clear.svg" alt=""></span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
                <label for="inputTitle">Название</label>
                <input type="text" name="title_event" class="form-control" id="inputTitle" placeholder="Название">
            </div>
            <div class="form-group">
                <label for="">Изображение</label></br>
                <label for="inputImg" class="btn btn-primary">
                    Загрузить
                </label>
                <label class="inputFileName" for=""></label>
                <input id="inputImg" type="file" value=""/>
            </div>
            <div class="form-group">
                <label for="inputTime">Время</label>
                <input type="time" name="time_event" class="form-control" id="inputTime" placeholder="Время">
            </div>
            <div class="form-group">
                <label for="inputDate">Дата</label>
                <input type="date" name="date_event" class="form-control" id="inputDate">
            </div>
            <div class="form-group">
                <label for="inputDescription">Описание</label>
                <textarea rows="10" cols="45" type="text" name="description_event" class="form-control" id="inputDescription" placeholder="Описание"></textarea>
            </div>
            <div class="form-group">
                <label for="inputCount">Количество участников</label>
                <input type="number" name="count_event" class="form-control" id="inputCount" placeholder="Количество участников">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Отменить</button>
            <button type="button" class="btn btn-primary">Сохранить</button>
          </div>
        </div>
      </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script>
    $(document).ready(function(){
      $("#inputTitleFilter").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        console.log(value)
        $("#TableEvents tr").filter(function() {
          $(this).toggle($(this).data("title")==value);
        });
      });

      $(".usrtbl .edit-link").click(function() {
        var index = $(this).index();
          console.log(index);
        if (index == 0){
          //показать окно для редактирования мероприятия
          $("#idModalEditEvent").modal('show');
          var getTitleFromRow = $(event.target).closest('tr').data('title');
          var getTimeFromRow = $(event.target).closest('tr').data('time');
          var getDateFromRow = $(event.target).closest('tr').data('date');
          var getDescriptionFromRow = $(event.target).closest('tr').data('description');
          var getCountFromRow = $(event.target).closest('tr').data('count');
          $("#idModalEditEvent").find('#ModalLabel').html($('<h4>Редактирование мероприятия</h4>'));
          $("#inputTitle").val(getTitleFromRow);
          $("#inputTime").val(getTimeFromRow);
          $("#inputDate").val(getDateFromRow);
          $("#inputDescription").val(getDescriptionFromRow);
          $("#inputCount").val(getCountFromRow);
        } else if (index == 1) {
          //скачать список участников
        }
      });

      //показать окно для добавления мероприятия
      //$("#addEvent").click(function() {
        //$("#idModalEditEvent").modal('show');
        //$("#idModalEditEvent").find('#ModalLabel').html($('<h4>Добавление мероприятия</h4>'));
      //  $("#inputTitle").val("");
      //  $("#inputTime").val("");
      //  $("#inputDate").val("");
      //  $("#inputDescription").val("");
      //  $("#inputCount").val("");
      //  $("#inputImg").val("");
      //  $(".inputFileName").removeClass("selected");
      //});

      $("#inputImg").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".inputFileName").addClass("selected").html(fileName);
      });
    });

    <?php if($errorText) : ?>
    $(window).on('load', function(e) {
      alert('<?=$errorText?>');
    });
    <?php endif; ?>
    </script>
</body>
</html>
