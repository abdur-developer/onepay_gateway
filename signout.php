<?php
require 'config.php';
$_SESSION = array();
session_destroy();
redirect('signin.php');
?>