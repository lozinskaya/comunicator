<?php
require_once 'init.php';
$error = true;
$success = false;

$code = trim($_REQUEST["code"]);
if ($code) {
    $now = time();
    $user = get_user(array("code" => $code));
    if ($user) {
        $error = false;
        $active_session = get_session(array("user_id" => $user->id, "exit_time" => NULL));
        if($active_session) {
            $tariff = get_tariff($active_session->tariff_id);
            $end_signature = soltstring($active_session->id . " ". $now);
            $minutes = ceil(($now - $active_session->enter_time) / 60);
            $tariff_sum = $tariff->price * $active_session->count;
            $session_sum = $tariff_sum * $minutes;
        } else {
            $tariff = get_standart_tariff();
        }

        if(isset($_POST["submit"])) {
            $mode = $_POST["mode"];
            if($mode == "end" && $active_session) {
                $end_time = intval($_POST["end_time"]);
                if($end_time) {
                    $end_signature_posted = trim($_POST["end_signature"]);
                    $end_signature_check  = soltstring($active_session->id . " " . $end_time);
                    if($end_signature_posted != $end_signature_check)
                        $end_time = $now;
                } else 
                    $end_time = $now;

                $minutes = ceil(($end_time - $active_session->enter_time) / 60);
                $tariff_sum = $tariff->price * $active_session->count;
                $session_sum = $tariff_sum * $minutes;

                $update_balance = $mdb->update($mdb->users, array("balance" => $user->balance - $session_sum), array("id" => $user->id));
                if($update_balance) {
                    $end_session = $mdb->update($mdb->sessions, array(
                        "exit_time" => $end_time,
                        "sum" => $session_sum
                    ), array("id" => $active_session->id));
                    if($end_session)
                        $success = true;
                    else
                        $error = true;
                } else
                    $error = true;
            }

            if($mode == "start" && !$active_session) {
                $extra_count = intval($_POST["extra_count"]);
                $count = 1;
                if($extra_count)
                    $count += $extra_count;

                $open_session = $mdb->insert($mdb->sessions, array(
                    "user_id" => $user->id,
                    "tariff_id" => $tariff->id,
                    "enter_time" => $now,
                    "count" => $count
                ));

                if($open_session)
                    $success = true;
                else
                    $error = true;
            }
        }
    }
}

if($error || $success) 
    $body_classes[] = "centered";

$page_title = "Сканирование";
$lk_page_id = "scan";

get_template_part('header');
?>
<?php if ($error) : ?>
        <div class="container">
            <div class="icon big-icon error"></div>
            Что-то не получилось :(
        </div>
<?php else : ?>
    <?php if ($success) : ?>
            <div class="container">
                <div class="icon big-icon success"></div>
                Сеанс <?=$active_session ? "завершён" : "начался"?>!
            </div>
    <?php else : ?>
        <div class="container">
        <form class="form-horizontal" method="POST" action="">
            <div class="heading text-center"><?=$active_session ? "Завершить" : "Начать"?> сеанс пользователя</div>
            <div class="text-left">
                <h3>Данные посетителя</h3>
                <table class="table scan-info">
                <tbody>
                    <tr>
                        <td class="label-col">Имя</td>
                        <td class="value"><?=$user->firstname . ' ' . $user->surname?></td>
                    </tr>
                    <tr>
                        <td class="label-col">Баланс</td>
                        <td class="value"><?=$user->balance?> ₽</td>
                    </tr>
                    <tr>
                        <td class="label-col">Тариф</td>
                        <td class="value"><?=$tariff->price?> ₽ / мин</td>
                    </tr>
                    <?php if($active_session) : ?>
                        <tr>
                            <td class="label-col">Посетители</td>
                            <td class="value"><?=$active_session->count?></td>
                        </tr>
                        <tr>
                        <td class="label-col">Длительность</td>
                            <td class="value"><?=$minutes?></td>
                        </tr>
                        <tr>
                            <td class="label-col">Сумма</td>
                            <td class="value"><?=$session_sum?>  ₽</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                </table>
            </div>
            <?php if($active_session) : ?>
                <input type="hidden" name="end_time" value="<?=$now?>">
                <input type="hidden" name="end_signature" value="<?=$end_signature?>">
            <?php else: ?>
                <input type="number" id="extraCount" name="extra_count" min="0" style="display: none;" required value="0">
                <h3 class="text-left">Друзья посетителя</h3>
                <div class="centered number-choice">
                    <button type="button" class="form-control btn-gray rounded-circle minus-btn" disabled>–</button>
                    <span class="number">0</span>
                    <button type="button" class="form-control btn-gray rounded-circle plus-btn">+</button>
                </div>
            <?php endif; ?>
            <input type="hidden" name="mode" value="<?=$active_session ? "end" : "start"?>">
                <button type="submit" name="submit" value="1" class="form-control btn btn-default"><?=$active_session ? "Завершить" : "Начать"?> сеанс</button>
        </form>
        </div>
        <script>
            function checkButtons() {
                let current_val = eval($('#extraCount').val());
                if(current_val > 0)
                    $('.number-choice .minus-btn').removeAttr('disabled');
                else
                    $('.number-choice .minus-btn').attr('disabled', 'disabled');
                $('.number-choice .number').text(current_val);
            }
            $('.number-choice .minus-btn').click(function() {
                let current_val = eval($('#extraCount').val());
                if(current_val > 0)
                {
                    $('#extraCount').val(current_val - 1);
                }
                checkButtons();
            });
            $('.number-choice .plus-btn').click(function() {
                let current_val = eval($('#extraCount').val());
                $('#extraCount').val(current_val + 1);
                checkButtons();
            });
        </script>
    <?php endif; ?>
<?php endif; ?>
<?php
get_template_part('footer');
?>