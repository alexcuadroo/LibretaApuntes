<?php
session_start();
session_set_cookie_params(10080);

function isLoggedIn()
{
    return isset($_SESSION['user_email']);
}

function redirectIfNotLoggedIn()
{
    if (!isLoggedIn()) {
        header("Location: ../login");
        exit;
    }
}
?>