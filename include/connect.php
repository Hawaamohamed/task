<?php

     $dsn="mysql:host=localhost;dbname=task";
     $user="root";
     $pass="";
     $option=array(
                 PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                 );
     try{
           $con = new PDO($dsn,$user,$pass,$option);
           $con->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
           //Use Database
           $stmt=$con->prepare("use task");
           $stmt->execute();
        }
     catch(PDOException $e){
         echo "Faild to connect".$e->getMessage();
     }

?>
