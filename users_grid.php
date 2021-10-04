<?php
     session_start();
if(isset($_SESSION['userid']) && !empty($_SESSION['userid']))
{
     include "include/header.php";
     $online_user = getElement("users","WHERE id = {$_SESSION['userid']}");
    //Paginate
    if(isset($_POST["limit-records"])){
        $limit = $_POST["limit-records"];
    }else{
        $limit = 25;
    }
	if(isset($_GET['page'])){
        $page = $_GET['page'];
    }else{
         $page = 1;
     }
	$start = ($page - 1) * $limit;

	$result = $con->prepare("SELECT * FROM users ORDER BY id DESC LIMIT $start , $limit");
    $result->execute();
	$grid_users = $result->fetchAll();

	$result1 = $con->prepare("SELECT count(id) AS id FROM users");
    $result1->execute();
	$custCount = $result1->fetchAll();
	$total = $custCount[0]['id'];
	$pages = ceil( $total / $limit );

	$Previous = $page - 1;
	$Next = $page + 1;
    ?>
	</header>
	<!-- /Header -->

<!--****************** Grid **************-->
<div class="section sm-padding">
		<div class="container">

        <?php

      $user_privileges = explode(" - ",$online_user['users_privileges']);
      if(in_array("Show users", $user_privileges) || in_array("Create New User", $user_privileges) || in_array("Add Privileges", $user_privileges) || in_array("Delete User", $user_privileges))
      {

        if(in_array("Create New User", $user_privileges))
        {
       ?>
          <a href="users.php" class="btn btn-sm btnheader pull-right">New User</a>
<?php } ?>
           <div class="row">
               <div class="col-sm-12 no-padding-lg-sm">
                  <div class="tablearea viewtable">
                     <table class="table-users">
                       <thead>
                        <th>#</th>
                        <th>Name</th>
                        <th>Registration Date</th>
                        <th>Last Login</th>
                        <?php if(in_array("Add Privileges", $user_privileges) || in_array("Delete User", $user_privileges) || in_array("Edit", $user_privileges))
                        {
                            echo '<th>Actions</th>';
                        } ?>

                        </thead>

                         <tbody>

                       <?php
                      if(!empty($grid_users))
                      {
                           if($page == 1)
                          {
                              $i = $page;
                          }else{
                              $i = $page + (24*($page - 1));
                          }
                          foreach($grid_users as $user)
                          {
                          echo "<tr id='".$user['id']."'>
                                  <td>" . $i . "</td>
                                  <td>" . $user['name'] . "</td>
                                  <td>" . $user['date'] . "</td>
                                  <td>" . $user['last_login']. "</td>";
                                  if(in_array("Add Privileges", $user_privileges) || in_array("Delete User", $user_privileges) || in_array("Edit", $user_privileges))
                                  { ?>
                                 <td> <span class='actions togg user-info-btn' data-id="<?php echo $user['id'];?>"  id='togglebutton'>...</span>

                                  <div class="actions-list hidden">
                                      <span class="pseudo"></span>
                                      <ul>
                                      <?php
                                      if(in_array("Add Privileges", $user_privileges))
                                      {
                                      ?>
                                       <li data-class='Edit'><a class='btn btn-link li_edit' href="users.php?edit=user&id=2345-555<?php echo $user['id'];?>">Add Privileges</a></li>
                                      <?php
                                      }
                                      if(in_array("Edit", $user_privileges))
                                      {
                                       ?>
                                       <li data-class='Edit'><a class='btn btn-link li_edit' href="users.php?edit=user&id=2345-555<?php echo $user['id'];?>" data-id="<?php echo $last_lead['id'];?>">Edit</a></li>
                                       <?php
                                      }
                                      if(in_array("Delete User", $user_privileges))
                                      {
                                        ?>
                                       <li data-class='Delete'><button class='btn btn-link user_buttons li_delete delete' data-toggle='modal' data-target='#userDelete' data-id="<?php echo $user['id'];?>">Delete</button></li>
                                       <?php
                                      }
                                   ?>

                                     </ul>
                                   </div>

                                 </td>
                           <?php }
                           $i++;
                           echo "</tr>";
                         }
                     }else{
                         echo "<tr><td class='text-center' colspan='9'><b>No Users Exist</b></td></tr>";
                     }
                     ?>

                     </tbody>
                     </table>
                     <div class="footerarea">
                      <!--******************** Paginate ***************-->
                       <?php
                      if(!empty($grid_users)){
                      ?>
                       <nav aria-label="..." class="Page navigation example nav-pagination">
                          <ul class="pagination">
                            <li>
                              <a href="users_grid.php?page=<?php echo $Previous; ?>" aria-label="Previous" <?php if($pages == 1 || $Previous == 0){echo "style='pointer-events: none;'";}?>>
                                <span aria-hidden="true" <?php if($pages == 1 || $Previous == 0){echo "style='opacity: .2;'";}?> class="glyphicon glyphicon-menu-left"> </span>
                              </a>
                            </li>
                            <?php
                               for($i = 1; $i<= $pages; $i++)
                               {

                                if($i <= 10){
                               ?>
                                <li><a href="users_grid.php?page=<?php echo $i; ?>" <?php if($i == $page){echo 'class="active-paginate"';}?>><?php echo $i; ?></a></li>
                            <?php }
                             }
                            ?>
                            <li>

                              <a href="users_grid.php?page=<?php echo $Next; ?>" aria-label="Next" <?php if($page == $pages){echo "style='pointer-events: none;'";}?>>
                                <span aria-hidden="true" <?php if($page == $pages){echo "style='opacity: .2;'";}?> class="glyphicon glyphicon-menu-right"></span>
                              </a>
                            </li>
                          </ul>
                        </nav>
                      <?php
                      }
                      ?>

                     </div>

                 </div>
               </div>


             </div>

     <?php
      }else{
          echo "<div class='alert alert-info text-center'>You not have a Permission to Show this</div>";
      }
     ?>

</div>
</div>
     <!-- Modal Delete user -->
    <div class="modal fade" id="userDelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

          </div>
          <form action="" method="post" id='delete_lead'>
           <div class="modal-body">
              <h3 class="delete_message">Are you sure that you want to delete this User ?</h3>

              <div class="col-sm-12">
                 <input type="hidden" name="delete_form" id="delete_form">
                 <input type="hidden" name="user_id" class="user_id">
                 <input type="hidden" name="id" class="user_id">
                 <input type="hidden" name="user_id" value="<?php echo $_SESSION['userid'];?>">
              </div>
              <div class="clearfix"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary button_delete" data-dismiss="modal">YES</button>
          </div>
         </form>
    </div>
  </div>
 </div>


 <div class="hidden link_color" data-target="users_color"></div>
<?php
include "include/footer.php";
?>
<script type="text/javascript">
$(document).ready(function(){

    $(document).on("click",".user_buttons",function(){
        var user_id = $(this).data("id");
        $("input.user_id").val(user_id);
    });
    /**************Search Accounts***************/
    $("input.input-search").on("keyup", function(){
        var users_search = $(this).val();
        var table = "users";
        $("ul.pagination").addClass("hidden");
        $.ajax({
            url:"php/search.php",
            type:"post",
            dataType:"text",
            data:{users_search:users_search,table:table},
            success:function(data)
            {
               $("table.table-users tbody").html(data);
            },
            error:function(error)
            {
                //alert(error);
            }

        })

    })

    ///////////////Show list//////////////////
      $(document).on("click","#show-list ul li", function (){

          var show_users = $(this).data("class");
          var content = $(this).html();
          var table = "users";
          $("#show").val(content);

          $("#show-list").addClass("hidden");

          if($(this).data("class") == "all")
          {
            $("ul.pagination").addClass("hidden");
          }

          $.ajax({
              url:"php/search.php",
              dataType:"text",
              type:"post",
              data:{show_users:show_users,table:table},
              success:function(data)
              {
                  $(".table.table-users tbody").html(data);
              }
          });
      });

    //Append multi input phone
    $(document).on("click","form#users_form_edit .glyphicon-plus.plus-phone",function(){
        var parent = $(this).parent();
        $(".phone-cloned.hidden").clone().insertAfter(parent).removeClass("hidden");
    });

    $(document).on("click","button.status",function(){
        console.log('button.status');
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
                    action.parent().parent().parent().parent().parent().siblings(".user-status").html(user_status).css("color","#3c763d");
                }else{
                    action.parent().parent().parent().parent().parent().siblings(".user-status").html(user_status).css("color","#a94442");
                }
            },
            error:function(error){
                alert(error);
            }
        })
    });


   /******** delete user ********/
    $(document).on("click",".button_delete",function(){
        var id = $("input.user_id").val();
        var table = "users";
        $.ajax({
            url:"php/delete.php",
            data:{id:id,table:table},
            type:"post",
            success:function(data){

$("#success-modal").removeClass("hidden").addClass("display-flex");
                    $("#success-modal .modal-body p").text(data);
                $(".table-users tbody tr#"+id).addClass("hidden");
                $(".viewarea").addClass("hidden");
            }
        })
    })


//Export Files
var table_name = "users";
$("#csv_icon").on("click",function(){
    $('table.table-users').tableExport({type:'csv'});
})

});

</script>



<?php
}else{
 header("location:index.php");
 exit();
}
?>
