<?php
session_start();
session_destroy();
header('Location: loginmodule.php');
exit();
