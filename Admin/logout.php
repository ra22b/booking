<?php
      require('db_connect.php');
      session_start();
      session_unset();
      session_destroy();
      
      header("Location: Sig_admin.php");
      exit()
?>