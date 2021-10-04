<?php
session_start();
session_regenerate_id();
include 'include/connect.php';
include 'include/function.php';

if(isset($_SESSION['userid']))
{
  header('location: home.php');
  exit();
}


if($_SERVER['REQUEST_METHOD'] == 'POST')
{
  if(isset($_POST['loginForm']))
  {
      $email=$_POST['email'];
      $password=$_POST['password'];
      $error = array();
      if($email == "")
      {
          $error[]="Enter your Email";
      }
      if($password == "")
      {
          $error[]="Enter your Password";
      }
      if(empty($error))
      {

          $hashedpass=sha1($password);
          $stmt2=$con->prepare('SELECT * FROM users WHERE email=? AND password=?');
          $stmt2->execute(array($email,$hashedpass));
          $get= $stmt2->fetch();
          $count=$stmt2->rowCount();

              if($count > 0)
              {
                  $stmt33=$con->prepare('UPDATE users SET last_login = now() WHERE id = ?');
                  $stmt33->execute(array($get['id']));

                    $_SESSION['userid'] = $get['id'];
                    $_SESSION['name'] = $get['name'];
                    $_SESSION['email'] = $get['email'];
                    $_SESSION['type'] = $get['type'];

                     header('location: users_grid.php');
                     exit();

              }else{

                  $error[]= "Name Or Password Invalid";

             }

       }
    }
}
?>

 <!DOCTYPE html>
 <html lang="en">
 <head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="description" content="help customers to create healthy working environment, achieve their vision and grow their business through Our HR Services." />
	<meta name="keywords" content="" />
	<meta name="Abdallah Nasser" content="" />

     <meta http-equiv="content-type" content="text/html; charset=UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
     <!--
     <link rel="icon" href="http://www.tatweeratmatah.com/layout/img/tatweer.png" type="image/x-icon" />
     <link rel="shortcut icon" href="http://www.tatweeratmatah.com/layout/img/tatweer.png" type="image/x-icon" />
     -->
     <title> LOGIN</title>

   <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">


   <link rel="stylesheet" href="layout/css/login.css">
 </head>
 <body>
<div class="big">

    <div class="parent">
        <div class="div-form">
            <form class='login' action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">

                <h3>Login</h3>
               <div class="input-container form-group has-feedback">
                   <input type='email' name='email' id="email" class='form-control' required placeholder="YOUR EMAIL*">
                   <span class="glyphicon glyphicon-envelope"></span>
               </div>
               <div class="input-container form-group has-feedback">
                    <input type='password' name='password' id="password" class='form-control' required placeholder="YOUR PASSWORD*">
                    <span class="glyphicon glyphicon-lock"></span>
               </div>
               <div class='form-group has-feedback'>
                     <input type='submit' class='btn free-background btn-block'  name='loginForm' value='LOG IN'>
               </div>
               <div class="col-sm-12">
                <!-- <p class="text-center"><a href="forgot-password.php" class="forgot">Forgot your Password ?</a></p> -->

               </div>
            </form><div class="clearfix"></div>
            <?php
             if(! empty($error))
            {
              echo "<div class='alert alert-danger text-center'><ul class='unstyled-list'>";
              foreach($error as $err)
              {
                echo  "<li>" . $err . "</li>";
              }
               echo "</ul></div>";
            }
           ?>
        </div>

    </div>

</div>

<script src="layout/js/jquery.min.js" type="text/javascript"></script>
<script src="layout/js/bootstrap.min.js" type="text/javascript"></script>
<script type="text/javascript">
     $(document).ready(function(){
         var val = $(".div-form").innerHeight();
         $(".div-cover").innerHeight(val);
     })

</script>
     <!--


-->
</body>
</html>
