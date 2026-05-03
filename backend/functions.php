<?php

function clean($data) {
    return htmlspecialchars(trim($data));
}

function redirect($location) {
    header("Location: $location");
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'Admin';
}

function isMember() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'Member';
}
?>