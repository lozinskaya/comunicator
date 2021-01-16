<?php
require_once 'login-init.php';

global $page_title, $lk_page_id, $errorText, $redirect_to;
$page_title = "Авторизация";
$lk_page_id = "login";

if ($_POST["submit"] == 1) {
    $email = $_POST["email"];
    $pass = soltstring($_POST["pass"]);
    $account = get_admin(array("email" => $email, "pass" => $pass));
    if ($account) {
        auth_admin($account);
        header("Location: " . $redirect_to);
    } else {
        $errorText = "Логин или пароль неправильные.<br>Попробуем еще раз";
    }
}

get_template_part('office-login-header');
?>
    <span class="heading">Вход</span>
    <div class="form-group">
        <input type="email" required name="email" class="form-control" id="inputEmail" placeholder="Логин">
    </div>
    <div class="form-group">
        <input type="password" required name="pass" class="form-control" id="inputPassword" placeholder="Пароль">
    </div>
    <div class="form-group">
        <button type="submit" name="submit" value="1" class="form-control btn btn-default">Войти</button>
    </div>
    <?php if($errorText != "") : ?>
        <div class="error text-danger"><?=$errorText?></div>
    <?php endif; ?>
<?php
get_template_part('office-login-footer');
?>