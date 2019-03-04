<?php

require_once 'functions.php';
require_once 'mysql_helper.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   $form = $_POST;
   $required = ['email', 'password'];
   $errors = [];

   $conn = connectDB();
}