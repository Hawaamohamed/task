<?php
session_start();
include "../include/connect.php";
include "../include/function.php";

$online_user = getElement("users","WHERE id = {$_SESSION['userid']}");


/*********************************** Insert new user ************************************/
if(isset($_POST['users_form']))
{
    $password = sha1($_POST['password']);
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $phone = $_POST['phone'];
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);


    $users_privileges = "";

    if(isset($_POST['users_privileges']) && !empty($_POST['users_privileges']))
    {
        for($i = 0;$i<count($_POST['users_privileges']);$i++)
        {
            $users_privileges.= $_POST['users_privileges'][$i];
            if($i+1 < count($_POST['users_privileges']))
            {
                $users_privileges.=" - ";
            }

        }
    }
    $added_by = $_POST['user_id'];

    $data = array();
    $data["error"] = "";

    if(empty($password))
    {
        $data["error"].= "<li>Enter Password</li>";
    }
    if(empty($name))
    {
        $data["error"].= "<li>Enter Name</li>";
    }
    if(empty($phone))
    {
        $data["error"].= "<li>Enter Phone</li>";
    }else{
        if(!preg_match("/^[\+|\(|\)|\d|\- ]*$/", $phone, $matches))
        {
            $data["error"].="<li>Error in Phone number, Enter valid Number</li>";
        }
    }
    if(empty($email))
    {
        $data["error"].= "<li>Enter Email</li>";
    }else{
        if(!filter_var($email,FILTER_VALIDATE_EMAIL))
        {
            $data["error"].="Error in Email, Enter valid Email";
        }
    }
    if(empty($data["error"]))
    {
      $data['response'] = "Submitted Successfully";




      $stmtinuser = $con->prepare("INSERT INTO users (name, password, email, phone, added_by, date)


      VALUES(:zname,:zpassword,:zemail,:zphone, :zadded_by, now())");

      $stmtinuser->execute(array("zname"=>$name, "zpassword"=>$password, "zemail"=>$email, "zphone"=>$phone, "zadded_by"=>$added_by));

      $curr_user = getField("users","id","ORDER BY id DESC");

      $data['id'] = $curr_user['id'];
        echo json_encode($data);
    }else{

        $data['response'] = "error";
        echo json_encode($data);
    }


}


?>
