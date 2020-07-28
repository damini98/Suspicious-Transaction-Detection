<?php
include('session.php'); 
unset($_SESSION['user_id']);
unset($_SESSION['username']);
ob_clean();
header('Location:index.php');
 ?>
