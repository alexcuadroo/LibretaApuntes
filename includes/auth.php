<?php
session_start();

function isLoggedIn()
{
    return isset($_SESSION['user_email']);
}

function redirectIfNotLoggedIn()
{
    if (!isLoggedIn()) {
        header("Location: ./login");
        exit;
    }
}
?>