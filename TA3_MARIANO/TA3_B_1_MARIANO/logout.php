<?php
session_start();
session_destroy();
header('Location: regismodules_login.php');
exit();
