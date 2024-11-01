<?php
// core/validate.php

function validatePassword($password) {
    return (strlen($password) >= 8);
}

function sanitizeInput($input) {
    return htmlspecialchars(stripslashes(trim($input)));
}
?>
