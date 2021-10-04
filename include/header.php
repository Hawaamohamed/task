<?php
include "function.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

	<title>Task</title>

	<!-- Google font -->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700%7CVarela+Round" rel="stylesheet">

	<!-- Bootstrap -->
	<link type="text/css" rel="stylesheet" href="layout/css/bootstrap.min.css" />

	<!-- Magnific Popup -->
	<link type="text/css" rel="stylesheet" href="layout/css/magnific-popup.css" />

	<!-- Font Awesome Icon -->
	<link rel="stylesheet" href="layout/css/font-awesome.min.css">
    <!-- google fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab&family=Roboto:wght@100;400&display=swap" rel="stylesheet">

    <!-- response modal (success-error) -->
    <link rel="stylesheet" href="layout/css/response-modal.css" type="text/css">


    <link type="text/css" rel="stylesheet" href="layout/css/style.css" />
    <link type="text/css" rel="stylesheet" href="layout/css/front.css" />

    <link type="text/css" rel="stylesheet" href="layout/css/grid.css" />

    <!--************ for textarea and new design *********-->
    <!-- font awsome -->
    <script src="https://kit.fontawesome.com/3f03e5b0c1.js" crossorigin="anonymous"></script>
    <!--
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
   -->
    <link rel="stylesheet" href="layout/css/style1.css" type="text/css">
    <link rel="stylesheet" href="layout/css/newfieldstyle.css" type="text/css">
    <link rel="stylesheet" href="layout/css/summernote.min.css" type="text/css">

    <!--************ /for textarea and new design *********-->

</head>

<body>

	<!-- Header -->
	<header id="home">
		<!-- Background Image
		<div class="bg-img">
			<div class="overlay"></div>
		</div>
-->
		<!-- /Background Image -->

		<!-- Nav -->
		<nav id="nav" class="navbar nav-transparent">
			<div class="container">

				<div class="navbar-header">
					<!-- Logo -->
					<div class="navbar-brand">
						<a href="/" target="_blank">
							<img class="logo" src="layout/img/logo.png" alt="First logo">
							<img class="logo-alt" src="layout/img/logo.png" alt="Second logo">
						</a>
					</div>
					<!-- /Logo -->

					<!-- Collapse nav button -->
					<div class="nav-collapse">
						<span></span>
					</div>
					<!-- /Collapse nav button -->
				</div>

				<!--  Main navigation  -->
				<ul class="main-nav nav navbar-nav navbar-right">

                    <?php
                    $online_user = getElement("users","WHERE id = {$_SESSION['userid']}");
                    $user_privileges = explode(" - ",$online_user['users_privileges']);
										if(in_array("Show users", $user_privileges) || in_array("Create New User", $user_privileges) || in_array("Add Privileges", $user_privileges) || in_array("Delete User", $user_privileges))
										{
                    ?>

										<li><a href="users_grid.php" class="users_color"><img src="layout/img/users.png" class="notif-icon"><br>Users</a></li>

										 <?php
									  }
										 ?>

                    <li class="has-dropdown"><a href="#" class="me_color"><img src="layout/img/me.png" class="notif-icon"><br><span><?php echo $online_user['name'];?></span></a>
                        <ul class="dropdown dropdown2 sm_show notif-dropdown">

                          <li class="me_color border-top"><a href="logout.php">Sign Out</a></li>
                        </ul>
                    </li>

				</ul>
				<!-- /Main navigation -->

			</div>
		</nav>
		<!-- /Nav -->
