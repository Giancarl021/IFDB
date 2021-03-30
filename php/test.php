<?php
require("IFBD.php");
require("JSON.php");

$db = new IFDB("username", "password");

$users = $db->query("SELECT * FROM TB_USUARIO");

send_json($users, true);
