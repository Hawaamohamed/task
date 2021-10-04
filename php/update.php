<?php
session_start();
include "../include/connect.php";
include "../include/function.php";

$online_user = getElement("users","WHERE id = {$_SESSION['userid']}");

/*********************************** Update  user ************************************/
if(isset($_POST['users_form']))
{

    $id = $_POST['id'];

    $users_privileges = "";
    //convert user Privileges from array to string
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

    $data = array();
    $data["error"] = "";
    $user_privileges = explode(" - ",$online_user['users_privileges']);
    if(in_array("Edit", $user_privileges))
    {
        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $phone = $_POST['phone'];
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

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
                $data["error"].="<li>Error in Personal Email 1, Enter valid Email</li>";
            }
        }
    }

    if(empty($data["error"]))
    {
      $data['response'] = "Updated Successfully";
      //update user Privileges
      $stmtupuser = $con->prepare("UPDATE users SET  users_privileges = ?  WHERE id = ?");
      $stmtupuser->execute(array($users_privileges, $id));


       if(in_array("Edit", $user_privileges))
       {
         //update user data
         $stmtupuser2 = $con->prepare("UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?");
         $stmtupuser2->execute(array($name, $email, $phone, $id));
                 if(!empty($_POST['password']))
                 {
                     $password = sha1($_POST['password']);

                     $stmtupuser2 = $con->prepare("UPDATE users SET password = ? WHERE id = ?");
                     $stmtupuser2->execute(array($password, $id));


                 }
       }

        $data['id'] = $id;
        echo json_encode($data);
    }else{

        $data['response'] = "error";
        echo json_encode($data);
    }


}


?>
