<?php
session_start();
if (isset($_POST['login'])) {
    $_SESSION['login'] = $_POST['login'];
}
?>