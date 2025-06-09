<?php
session_start(); 

session_unset();

session_destroy();

header('Location: ../E-commerce/index.html');
exit();
?>
