<?php
     session_start();
if(isset($_SESSION['userid']) && !empty($_SESSION['userid']))
{
    include "include/header.php";
    $online_user = getElement("users","WHERE id = {$_SESSION['userid']}");
    if(isset($_GET["id"]) && !empty($_GET["id"]))
    {
        $get_user_id = $_GET['id'];
        $user_id = explode("-555",$get_user_id);
        $id = filter_var($user_id[1],FILTER_SANITIZE_NUMBER_INT);
        $user_edit = getElement("users","WHERE id = {$id}");
    }
    else{
        $id='';
        $user_edit = array();
        $user_edit['name'] = '';
        $user_edit['password'] = '';
        $user_edit['email'] = '';
        $user_edit['phone'] = '';
        $user_edit['users_privileges']= "";
    }
    ?>
	</header>
	<!-- /Header -->

    <!-- Form -->
<div class="section sm-padding users-form" style="padding-bottom: 0">

		<!-- Container -->
		<div class="container">
			<!-- Row -->
	 <div class="row">
      <?php
         $user_privileges = explode(" - ",$online_user['users_privileges']);
         if(in_array("Show users", $user_privileges) || in_array("Create New User", $user_privileges) || in_array("Add Privileges", $user_privileges) || in_array("Edit", $user_privileges))
         {
         ?>
      <!--*************** response messages **************-->
      <div class="col-sm-offset-3 col-sm-6">
        <div class="alert alert-success text-center hidden"></div>
        <div class="alert alert-danger text-center hidden"><ul class="unstyled-list"></ul></div>
      </div><div class="clearfix"></div>

    <div class="col-xs-12">
     <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" class="form1" <?php if(isset($_GET["id"]) && !empty($_GET["id"])){echo 'id="users_form_edit"';}else{echo 'id="users_form"';}?>>
    <?php
       if((in_array("Create New User", $user_privileges) && !isset($_GET["id"]) && empty($_GET["id"])) || in_array("Edit", $user_privileges))
       {
     ?>
       <div class="parent">
            <div class="col-sm-12">
               <h3>Personal Information</h3>
            </div>

            <div class="col-sm-3">
                 <input type="text" name="name" class="form-control" required placeholder="Name*" dir="auto" value="<?php echo $user_edit['name'];?>">
             </div>
             <div class="col-sm-3">

                 <input type="text" name="phone" class="form-control phone" pattern="[+0-9]+" required title="Enter only Numbers" placeholder="Mobile No*" dir="auto" value="<?php echo $user_edit['phone'];?>">

             </div>

              <div class="col-sm-3">
                  <input type="email" name="email" class="form-control" required placeholder="Email*" dir="auto" value="<?php echo $user_edit['email'];?>">
              </div>

              <div class="col-sm-3">
                  <input type="text" name="password" class="form-control" placeholder="Password*" dir="auto">
              </div>
             <div class="clearfix"></div>
         </div>

      <?php
       }
       if(isset($_GET['id']) && !empty($_GET['id']))
       {
         if(in_array("Add Privileges", $user_privileges) || in_array("Edit", $user_privileges))
         {
     ?>
          <div class="parent">
            <div class="col-sm-12">
               <h3>User Privileges</h3>
            </div>


            <div class="col-sm-12">
             <h4>Users Management Privileges</h4>
           </div>
           <?php
              $users_privileges = explode(" - ",$user_edit['users_privileges']);
           ?>
             <div class="col-sm-3 custom-checkbox">
                 <input type="checkbox" name="users_privileges[]" id="Checked43" value="Create New User" class="custom-control-input" <?php if (in_array("Create New User", $users_privileges)){echo "checked";}?>>
                 <label for="Checked43">Create New User</label>
             </div>

               <div class="col-sm-3 custom-checkbox">
                   <input type="checkbox" name="users_privileges[]" id="Checked431" value="Show users" class="custom-control-input" <?php if (in_array("Show users", $users_privileges)){echo "checked";}?>>
                   <label for="Checked431">Show users</label>
               </div>


             <div class="col-sm-3 custom-checkbox">
                 <input type="checkbox" name="users_privileges[]" id="Checked46" value="Add Privileges" class="custom-control-input" <?php if (in_array("Add Privileges", $users_privileges)){echo "checked";}?>>
                 <label for="Checked46">Add Privileges</label>
             </div>
             <div class="col-sm-3 custom-checkbox">
                 <input type="checkbox" name="users_privileges[]" id="Checked47" value="Delete User" class="custom-control-input" <?php if (in_array("Delete User", $users_privileges)){echo "checked";}?>>
                 <label for="Checked47">Delete User</label>
             </div>
             <div class="col-sm-3 custom-checkbox">
                 <input type="checkbox" name="users_privileges[]" id="Checked472" value="Edit" class="custom-control-input" <?php if (in_array("Edit", $users_privileges)){echo "checked";}?>>
                 <label for="Checked472">Edit User</label>
             </div>
             <p>&nbsp;</p>
              <!--*************************************************************************-->

             <div class="clearfix"></div>
         </div>
         <?php
         }
       }
        ?>
        <div class="parent">

             <input type="hidden" name="admin_id" value="<?php echo $_SESSION['userid'];?>">
             <input type="hidden" name="user_id" value="<?php echo $_SESSION['userid'];?>">
             <input type="hidden" name="id" value="<?php echo $id;?>">
             <input type="hidden" name="users_form" value="users_form">
             <div class="col-sm-offset-9 col-sm-3 col-xs-offset-3 col-xs-6">

                 <input type="submit" name="" class=" pull-right btn btn-default background-color" <?php if(!isset($_GET["id"]) && empty($_GET["id"])){ echo 'value="Submit"';}else{ echo 'value="Save"';}?>>
             </div>
             <div class="clearfix"></div>
         </div>
     </form>

     </div>
        <?php
       }else{
             echo "<div class='col-sm-12'><div class='alert alert-info text-center'>You Don't have a Permission To Access this</div>";
         }
         ?>
    </div>


  </div>
</div>


<div class="hidden link_color" data-target="users_color"></div>
<?php
include "include/footer.php";
?>
<!-- filter grid -->
<script type="text/javascript" src="layout/js/ddtf.js"></script>
<script type="text/javascript">
$(document).ready(function(){

    //insert User
    $("form#users_form").on("submit",function(e){
        e.preventDefault(); //loading
        $("#success-modal").removeClass("hidden").addClass("display-flex").children(".modal-dialog").css("display","none");
        $("#loader-footer").css("display","block");
        $(window).scrollTop(0);



        $.ajax({
            url:"php/insert.php",
            data: $(this).serialize(),
            type:"POST",
            dataType:"JSON",
            success:function(data)
            {
        $("#loader-footer").css("display","none");
        $("#success-modal").addClass("hidden").children(".modal-dialog").css("display","block");

                if(data.response == "error")
                {
                   $("#error-modal").removeClass("hidden").addClass("display-flex");
                    $("#error-modal .modal-body ul").html(data.error);

                }else{

$("#success-modal").removeClass("hidden").addClass("display-flex");
                    $("#success-modal .modal-body p").text(data.response);
                    $id = data.id;
                }
                $(window).scrollTop(0);

            },
            error:function(request,error)
            {
                //$(".alert-danger").removeClass("hidden").html(request.responseText);
            }
        });

    });
    //Edit User
    $("form#users_form_edit").on("submit",function(e){
        e.preventDefault(); //loading

        $(window).scrollTop(0);
 
        $.ajax({
            url:"php/update.php",
            data: $(this).serialize(),
            type:"POST",
            dataType:"JSON",
            success:function(data)
            {
                if(data.response == "error")
                {
                    $("#error-modal").removeClass("hidden").addClass("display-flex");
                    $("#error-modal .modal-body ul").html(data.error);

                }else{

                      $("#success-modal").removeClass("hidden").addClass("display-flex");
                      $("#success-modal .modal-body p").text(data.response);

                    $id = data.id;
                }

            },
            error:function(request,error)
            {
              //  $(".alert-danger").removeClass("hidden").html(request.responseText);
            }
        });

    });

    //Append multi input phone
    $(document).on("click","form#users_form_edit .glyphicon-plus.plus-phone",function(){
        var parent = $(this).parent();
        $(".phone-cloned.hidden").clone(true).insertAfter(parent).removeClass("hidden");
    });

    $(document).on("click","button.status",function(){
        var id = $(this).data("id");
        var user_status = $(this).data("target");
        var action = $(this);
        $.ajax({
            url:"php/update.php",
            type:"POST",
            data:{user_status:user_status,id:id},
            dataType:"TEXT",
            success:function(data){
                action.parent().siblings(".user-status").html(data);
                if(data == "Active"){
                    action.parent().siblings(".user-status").css("color","#3c763d");
                }else{
                    action.parent().siblings(".user-status").css("color","#a94442");
                }
            },
            error:function(error){
                alert(error);
            }
        })
    });



     //response Modal Buttons action
    $("#success-modal .btn-proced :not(.not-reload)").on('click', function(){
       window.location = "single-user.php?user=644372-111"+$id;
    })
    $("#success-modal .btn-close :not(.not-reload)").on('click', function(){
       window.location = "users_grid.php";
    });


});
</script>
<?php
}else{
 header("location:index.php");
 exit();
}
?>
