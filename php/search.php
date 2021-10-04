<?php
session_start();
include "../include/connect.php";
include "../include/function.php";

$online_user = getElement("users","WHERE id = {$_SESSION['userid']}");
if($_SESSION['email'] == "r.amin@freezoner.net" || $_SESSION['email'] == "relghoul@freezoner.net"){
    $user_type = "Admin";
}else{
    $user_type = "user";
}
$company_assets_privileges = explode(" - ",$online_user['company_assets_privileges']);
$finance_privileges = explode(" - ",$online_user['finance_privileges']);
$templates_privileges = explode(" - ",$online_user['templates_privileges']);
$contacts_privileges = explode(" - ",$online_user['contacts_privileges']);
$account_privileges = explode(" - ",$online_user['account_privileges']);
$deal_privileges = explode(" - ",$online_user['deal_privileges']);
$lead_privileges = explode(" - ",$online_user['lead_privileges']);
$user_privileges = explode(" - ",$online_user['users_privileges']);
/*********************************** Search Leads ************************************/
if(isset($_POST['lead_search']) || ($_POST['table'] == "leads" && isset($_POST['search_report'])))
{
    if(isset($_POST['lead_search']))
    {
        $search = $_POST['lead_search'];
        $type = $_POST['type'];
    }else if(isset($_POST['report_table_search']))
    {
        $search = $_POST['report_table_search']; $type = '';
    }

  $table = $_POST['table'];

  $order_by = "ORDER BY id DESC";
    if($table == "leads")
    {
        $target = "lead";  if($type != "all"){ $type = " AND follow_category = '{$type}' "; }else{  $type = ""; }
    }else if($table == "opportunities")
    {   if($type == "won"){ $type = " AND status = 'won' "; }else if($type == "all"){ $type = ""; }else{  $type = " AND status = '' "; }
        $target = "opp";$client_type = " AND client_type LIKE '%$search%'";
        //in opportunity order by id and status, put status (won) in the bottom of grid
        $order_by = "ORDER BY status ASC, id DESC";
    }

      $leads = searchElements($table,"*","WHERE (first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR full_name LIKE '%$search%' OR email LIKE '%$search%' OR phone LIKE '%$search%' OR service_category LIKE '%$search%' OR source_type LIKE '%$search%' OR follow_category LIKE '%$search%' OR source_name LIKE '%$search%' OR whats LIKE '%$search%' OR nationality LIKE '%$search%' OR residency LIKE '%$search%' OR method LIKE '%$search%' OR follow_date LIKE '%$search%' OR status LIKE '%$search%' OR date LIKE '%$search%') {$type}  {$order_by}");



    if(!empty($leads))
    {
        $i = 0;
          foreach($leads as $lead)
          {
            $i++;

            echo "<tr id='".$lead['id']."'>
                     <td>" . $i . "</td>";
              if($table == "leads")
              {
                      if($lead['lead_type'] == "Corporate")
                      {
                          $name = $lead['company_name'];
                      }else{
                          $name = $lead['full_name'];
                      }

                      if(!empty($lead['service_category'])){ $lead['service_category'] = "#" . $lead['service_category']; }

                  echo "<td><a href='single-lead.php?lead=644372-111".$lead['id']."'>" . $name . " <br><span style='font-size: 10px;color: #c69923;'>" . $lead['service_category'] ."</span></a>";

              if(!empty($lead['owner']))
              {
                  if($lead['owner_type'] === "hr")
                  {
                      $employee = getElement("employees","WHERE id = {$lead['owner']}");
                      if(empty($employee))
                      {
                          $owner = "Not Specified";
                      }else{
                          $owner = $employee['full_name'];
                      }
                  }else if($lead['owner_type'] === "user")
                  {
                      $us= getElement("users","WHERE id= {$lead['owner']}");
                      if(empty($us))
                      {
                          $owner = "Not Specified";
                      }else{
                          $owner = $us['username'];
                      }
                  }

              }else{
                  $owner = "Not Specified";
              }
              if(empty($lead['first_contact_date']))
              {
                  $lead['first_contact_date'] = "0000-00-00 00:00:00";
              }

               echo "<td>" . $lead['source_type'] . '/' . $lead['source_name'] . "</td>
                     <td>" . $lead['date'] . "</td>
                     <td>" . $lead['first_contact_date'] . "</td>
                     <td>" . $owner . "</td>
                     <td>" . $lead['follow_category'] . "</td> ";
             }else{

                      if(!empty($lead['owner']))
                      {
                          if($lead['owner_type'] === "hr")
                          {
                              $employee = getElement("employees","WHERE id = {$lead['owner']}");
                              if(empty($employee))
                              {
                                  $owner = "Not Specified";
                              }else{
                                  $owner = $employee['full_name'];
                              }
                          }else if($lead['owner_type'] === "user")
                          {
                              $us= getElement("users","WHERE id= {$lead['owner']}");
                              if(empty($us))
                              {
                                  $owner = "Not Specified";
                              }else{
                                  $owner = $us['username'];
                              }
                          }

                       }else{
                          $owner = "Not Specified";
                       }
                       if(empty($lead['first_contact_date']))
                      {
                          $lead['first_contact_date'] = "0000-00-00 00:00:00";
                      } if($lead['status'] == '') {$lead['status'] = "in progress";$icon='';}else{ $icon = " <i class='fa fa-check' aria-hidden='true' style='background: #c69923; color: #fff; padding: 3px; font-size: 10px; border-radius: 50%;'></i>"; }
                    //$source = getField("accounts","source_name","WHERE opp_id = {$opp['id']}");
                      $categories = getElements("categories","WHERE table_name = 'opportunities' AND table_id = {$lead['id']} ORDER BY id ASC LIMIT 3");

                      $categories_count = getCounts("categories","WHERE table_name = 'opportunities' AND table_id = {$lead['id']}");


                      $opp_categories="";
                  if(!empty($categories))
                  {
                      $opp_categories.="#";
//                      if($categories_count == 1)
//                      {
//                          foreach($categories as $category)
//                          {
//                            $opp_categories.=  $category['service_category'] . "".$category['sub_service_category']."";
//
//                          }
//                      }else{
                          foreach($categories as $category)
                          {
                             $opp_categories.= $category['service_category'] .  " - ";

                          }
                     // }
                    }

                      if($lead['opp_type'] === "Corporate")
                      {
                          $name = $lead['company_name'];
                      }else{
                          $name = $lead['full_name'];
                      }

                    echo "
                              <td><a href='single-opp.php?opp=644372-111".$lead['id']."'>".$name . ' ' . $icon ." <br><span style='font-size: 10px;color: #c69923;'>" . $opp_categories .  "</span></a></td>
                             <td>" . $lead['source_type'] . '/' . $lead['source_name'] . "</td>
                             <td>" . $lead['date'] . "</td>
                             <td>" . $lead['first_contact_date'] . "</td>
                             <td>" . $owner . "</td>";
              }
              if(!isset($_POST['report_table_search']))
              {
              if($table == "leads")
              {
                     ?>
                        <td> <span class='actions togg lead-info-btn' data-id="<?php echo $lead['id'];?>"  id='togglebutton'>...</span>

                              <div class="actions-list hidden">
                                  <span class="pseudo"></span>
                                  <ul>
                                    <?php
//                                     if($user_type == "Admin")
//                                     {
                                    ?>
                                     <li data-class='Delegate'><button class='btn btn-link li_delegation prospect_buttons' data-class='prospect_delegation' data-assign="<?php echo $lead['assign_to'];?>" data-id="<?php echo $lead['id'];?>" data-del="<?php echo $lead['delication_notes'];?>" data-toggle='modal' data-target='#leadDelegation'>Delegate</button></li>
                                    <?php
                                    // }

                                     ?>
                                     <li data-class='Qualify'><button class='btn btn-link li_qualify prospect_buttons' data-id="<?php echo $lead['id'];?>" data-qualify="<?php echo $lead['follow_notes'];?>" data-category="<?php echo $lead['follow_category'];?>" data-method="<?php echo $lead['method'];?>" data-class='self_assign' data-toggle='modal' data-target='#leadQualification'>Qualify</button></li>
                                     <?php
//                                     if($user_type == "Admin" || $lead['added_by'] == $_SESSION['userid'])
//                                     {
                                     ?>
                                     <li data-class='Edit'><a class='btn btn-link li_edit' href="leads.php?edit=lead&id=2345-555<?php echo $lead['id'];?>">Edit</a></li>
                                     <?php
                                    // }
                                     if($user_type == "Admin")
                                     {
                                     ?>
                                     <li data-class='Delete'><button class='btn btn-link prospect_buttons li_delete delete' data-toggle='modal' data-target='#leadDelete' data-id="<?php echo $lead['id'];?>">Delete</button></li>
                                     <?php
                                     }
                                     ?>
                                  </ul>
                               </div>

                       </td>
                      <?php } else { ?>
                         <td> <span class='actions togg lead-info-btn' data-id="<?php echo $lead['id'];?>"  id='togglebutton'>...</span>

                              <div class="actions-list hidden">
                                  <span class="pseudo"></span>
                                  <ul>

                                     <li data-class='Edit'><a class='btn btn-link li_edit' href="opportunities.php?edit=opp&id=2345-555<?php echo $lead['id'];?>">Edit</a></li>
                                      <?php
                                    // }
                                    if($_SESSION['email'] == "r.amin@freezoner.net" || $_SESSION['email'] == "relghoul@freezoner.net")
                                     {
                                     ?>
                                     <li data-class='Delete'><button class='btn btn-link prospect_buttons li_delete delete' data-toggle='modal' data-target='#oppDelete' data-id="<?php echo $lead['id'];?>">Delete</button></li>
                                    <?php
                                    }
                                    ?>

                                   </ul>
                               </div>


                        </td><?php }
              }
                echo "</tr>";

        }
      }else{
            echo "<tr><td class='text-center' colspan='100%'><b>No ". $table ." Found with ".$search."</b></td></tr>";
      }
}

/*********************************** Show list Leads ************************************/
if(isset($_POST['show']) || ($_POST['table'] == "leads" && isset($_POST['show_report'])))
{
    if(isset($_POST['show_report']))
    {
        $show = $_POST['show_report'];

    }else if(isset($_POST['show']))
    {
        $show = $_POST['show'];
    }
    $order_by = "ORDER BY id DESC";

    $table = $_POST['table'];
    if($table == "leads")
    {
        $target = "lead";
    }else if($table == "opportunities")
    {
        $target = "opp";
        //in opportunity order by id and status, put status (won) in the bottom of grid
        $order_by = "ORDER BY status ASC, id DESC";
    }

    if($show == 'all')
    {
            $leads = getElements($table,$order_by);

    }else{
             $leads = getElements($table,"{$order_by} LIMIT {$show}");
    }

    if(!empty($leads))
    {
        $i = 0;
          foreach($leads as $lead)
          {
            $i++;
              if(!empty($lead['owner']))
              {
                  if($lead['owner_type'] === "hr")
                  {
                      $employee = getElement("employees","WHERE id = {$lead['owner']}");
                      if(empty($employee))
                      {
                          $owner = "Not Specified";
                      }else{
                          $owner = $employee['full_name'];
                      }
                  }else if($lead['owner_type'] === "user")
                  {
                      $us= getElement("users","WHERE id= {$lead['owner']}");
                      if(empty($us))
                      {
                          $owner = "Not Specified";
                      }else{
                          $owner = $us['username'];
                      }
                  }

              }else{
                  $owner = "Not Specified";
              }

            echo "<tr id='".$lead['id']."'>
                     <td>" . $i . "</td>";
                      if($table == "leads")
                      {
                          echo "<td><a href='single-lead.php?lead=644372-111".$lead['id']."'>" . $lead['full_name'] ."</a></td>";
                      }else if($table == "opportunities"){
                          echo "<td><a href='single-opp.php?opp=644372-111".$lead['id']."'>" . $lead['full_name'] . "</a></td>";
                      }
               echo "
                     <td>" . $lead['source_type'] . '/' . $lead['source_name'] . "</td>
                     <td>" . $lead['date'] . "</td>
                     <td>" . $lead['first_contact_date'] . "</td>
                     <td>" . $owner . "</td>
                     <td>" . $lead['follow_category'] . "</td> ";
            ?>
                        <td> <span class='actions togg lead-info-btn' data-id="<?php echo $lead['id'];?>"  id='togglebutton'>...</span>

                              <div class="actions-list hidden">
                                  <span class="pseudo"></span>
                                  <ul>
                                    <?php
//                                     if($user_type == "Admin")
//                                     {
                                    ?>
                                     <li data-class='Delegate'><button class='btn btn-link li_delegation prospect_buttons' data-class='prospect_delegation' data-assign="<?php echo $lead['assign_to'];?>" data-id="<?php echo $lead['id'];?>" data-del="<?php echo $lead['delication_notes'];?>" data-toggle='modal' data-target='#leadDelegation'>Delegate</button></li>
                                    <?php
                                    // }

                                     ?>
                                     <li data-class='Qualify'><button class='btn btn-link li_qualify prospect_buttons' data-id="<?php echo $lead['id'];?>" data-qualify="<?php echo $lead['follow_notes'];?>" data-category="<?php echo $lead['follow_category'];?>" data-method="<?php echo $lead['method'];?>" data-class='self_assign' data-toggle='modal' data-target='#leadQualification'>Qualify</button></li>
                                     <?php
//                                     if($user_type == "Admin" || $lead['added_by'] == $_SESSION['userid'])
//                                     {
                                     ?>
                                     <li data-class='Edit'><a class='btn btn-link li_edit' href="leads.php?edit=lead&id=2345-555<?php echo $lead['id'];?>">Edit</a></li>
                                     <?php
                                   //  }
                                     if($user_type == "Admin")
                                     {
                                     ?>
                                     <li data-class='Delete'><button class='btn btn-link prospect_buttons li_delete delete' data-toggle='modal' data-target='#leadDelete' data-id="<?php echo $lead['id'];?>">Delete</button></li>
                                     <?php
                                     }
                                     ?>
                                  </ul>
                               </div>

                       </td>
                      <?php
                echo "</tr>";

          }
      }else{
            echo "<tr><td class='text-center' colspan='100%'><b>No ". $table ." Found </b></td></tr>";
      }
}

/*********************************** Search Accounts ************************************/
if(isset($_POST['account_search']) || ($_POST['table'] == "accounts" && isset($_POST['search_report'])))
{
     if(isset($_POST['account_search']))
    {
         $search = $_POST['account_search'];

    }else if(isset($_POST['report_table_search']))
    {
        $search = $_POST['report_table_search'];
    }
  //$admin = getElement("admins","WHERE name LIKE '%$search%'");
   $type = $_POST['type'];
   if($type != "all"){ $type = " AND account_type = '{$type}' "; }else{  $type = ""; }


  $accounts = getElements("accounts","WHERE primary_account = 0 AND (first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR company_name LIKE '%$search%' OR company_location LIKE '%$search%' OR full_name LIKE '%$search%' OR email LIKE '%$search%' OR phone LIKE '%$search%'  OR nationality LIKE '%$search%' OR residency LIKE '%$search%' OR source_type LIKE '%$search%' OR source_name LIKE '%$search%' OR userid IN (SELECT id FROM users WHERE full_name LIKE '%$search%' OR username LIKE '%$search%') OR assign_to IN (SELECT id FROM employees WHERE full_name LIKE '%$search%' ) ) {$type} ORDER BY id DESC");

    if(!empty($accounts))
    {
        $i = 0;
        foreach($accounts as $account)
        {
           $i++;

            $latest_deal = getField("deals","deal_name","WHERE account_id = {$account['id']} ORDER BY id DESC limit 1");


                  $owner = getField("users","username","WHERE id = {$account['userid']}");

                  if(!empty($account['assign_to']) && $account['assign_to'] > 0)
                  {
                      $employee = getElement("employees","WHERE id = {$account['assign_to']}");
                      if(empty($employee))
                      {
                          $owner = $owner['username'];
                      }else{
                          $owner = $employee['full_name'];
                      }

                   } else{ $owner = $owner['username']; }


            if(empty($latest_deal['deal_name']))
            {
                $latest_deal['deal_name'] = "Not Specified";
            }
              if($account['account_type'] === "Corporate")
              {
                  $name = $account['company_name'];
              }else{
                  $name = $account['full_name'];
              }




              if($account['primary_account'] == 0){
                      $primary_account_name = "Primary";
              } else{ $primary_account_name = ""; }
              $latest_deal = getElement("deals", "WHERE link_related_to_account = {$account['id']} ORDER BY id DESC LIMIT 1");

              $sub_accounts_of_this_account = getElements("accounts", "WHERE primary_account = {$account['id']}");
            echo "<tr id='".$account['id']."'>
                     <td>" . $i . "</td>
                     <td><a href='single-account.php?account=644372-111".$account['id']."'>" . $name ." <span style='font-size: 10px;color:#c69923'> ".$primary_account_name ."</span></a></td>
                     <td>" . $account['phone'] . "</td>
                     <td>" . $account['email'] . "</td>
                     <td>" . $latest_deal['deal_name'] . "</td>
                     <td>" . $owner . "</td>";
            if(!isset($_POST['report_table_search']))
            {
                     ?>
<td> <span type='button' class='togg actions account-info-btn' data-id="<?php echo $account['id'];?>"  id='togglebutton'>...</span> <?php if(!empty($sub_accounts_of_this_account)){ ?> <i class='fa fa-angle-down plus-sub-accounts' data-id="<?php echo $account['id'];?>"></i> <?php } ?>

                          <div class="actions-list hidden">
                              <span class="pseudo"></span>
                              <ul>

                                 <li data-class='Edit'><a class='btn btn-link li_edit' href="accounts.php?edit=account&id=2345-555<?php echo $account['id'];?>">Edit</a></li>
                                 <?php

                               if($_SESSION['email'] == "r.amin@freezoner.net" || $_SESSION['email'] == "relghoul@freezoner.net")
                               {
                             ?>
                                 <li data-class='Delete'><button class='btn btn-link account_buttons li_delete delete' data-toggle='modal' data-target='#accountDelete' data-id="<?php echo $account['id'];?>">Delete</button></li>
                             <?php
                              }
                             ?>
                                 </ul>
                           </div>
                   </td>

<?php
            }

            echo "</tr>";  $j = 0;
          foreach($sub_accounts_of_this_account as $sub_account)
          { $j++;
           $owner = getField("users","username","WHERE id = {$sub_account['userid']}");

           if(!empty($sub_account['assign_to']) && $sub_account['assign_to'] > 0)
           {
              $employee = getElement("employees","WHERE id = {$sub_account['assign_to']}");
              if(empty($employee))
              {
                  $owner = $owner['username'];
              }else{
                  $owner = $employee['full_name'];
              }

           } else{ $owner = $owner['username']; }

 $latest_deal = getField("deals","deal_name","WHERE link_related_to_account = {$sub_account['id']} AND deleted = 0 ORDER BY id DESC limit 1");

            if(empty($latest_deal['deal_name']))
            {
                $latest_deal['deal_name'] = "Not Specified";
            }
              if($sub_account['account_type'] === "Corporate")
              {
                  $name = $sub_account['company_name'];
              }else{
                  $name = $sub_account['full_name'];
              }


              if($sub_account['primary_account'] == 0){
                      $primary_account_name = "Primary";
              } else{ $primary_account_name = ""; }
              $latest_deal = getElement("deals", "WHERE link_related_to_account = {$sub_account['id']} ORDER BY id DESC LIMIT 1");

               echo "<tr id='".$sub_account['id']."' class='sub-account hidden ".$account['id']."' style=' background: #f5f5f5;'>
                     <td> </td>
                     <td><a href='single-account.php?account=644372-111".$sub_account['id']."'>" . $j.'-  ' . $name ." <span style='font-size: 10px;color:#c69923'> ".$primary_account_name ."</span></a></td>
                     <td>" . $sub_account['phone'] . "</td>
                     <td>" . $sub_account['email'] . "</td>
                     <td>" . $latest_deal['deal_name'] . "</td>
                     <td>" . $owner . "</td>";
          ?>
           <td> <span class='togg actions account-info-btn' data-id="<?php echo $sub_account['id'];?>"  id='togglebutton' style=' background: #f5f5f5;'>...</span>

                  <div class="actions-list hidden">
                      <span class="pseudo"></span>
                      <ul>

                         <li data-class='Edit'><a class='btn btn-link li_edit' href="accounts.php?edit=account&id=2345-555<?php echo $sub_account['id'];?>">Edit</a></li>
                         <?php

                       if($_SESSION['email'] == "r.amin@freezoner.net" || $_SESSION['email'] == "relghoul@freezoner.net")
                       {
                     ?>
                         <li data-class='Delete'><button class='btn btn-link account_buttons li_delete delete' data-toggle='modal' data-target='#accountDelete' data-id="<?php echo $sub_account['id'];?>">Delete</button></li>
                     <?php
                      }
                     ?>
                         </ul>
                   </div>
           </td> <?php }
        }
      }else{
            echo "<tr><td class='text-center' colspan='100%'><b>No Accounts Found with ".$search."</b></td></tr>";
      }
}

/*********************************** Show list Accounts ************************************/
if(isset($_POST['show_accounts']) || $_POST['table'] == "accounts" && isset($_POST['show_report']))
{
    if(isset($_POST['show_accounts']))
    {
       $show = $_POST['show_accounts'];

    }else if(isset($_POST['show_report']))
    {
        $show = $_POST['show_report'];
    }


    $table = $_POST['table'];
    if($show == 'all')
    {
         $accounts = getElements($table,"ORDER BY id DESC");
    }else{
         $accounts = getElements($table,"ORDER BY id DESC LIMIT {$show}");
    }

    if(!empty($accounts))
    {
        $i = 0;
        foreach($accounts as $account)
        {
           $i++;
            $account_owner = getElement("employees","WHERE id={$account['assign_to']}");
            $latest_deal = getField("deals","deal_name","WHERE account_id = {$account['id']} ORDER BY id DESC limit 1");
            if(empty($account_owner['full_name']))
            {
                $account_owner['full_name'] = "Not Specified";
            }
            if(empty($latest_deal['deal_name']))
            {
                $latest_deal['deal_name'] = "Not Specified";
            }

              if($account['account_type'] === "Corporate")
              {
                  $name = $account['company_name'];
              }else{
                  $name = $account['full_name'];
              }
                      if($account['primary_account'] > 0){
                          $primary_account = getElement("accounts","WHERE id = {$account['primary_account']}");
                          if($primary_account['account_type'] == "Corporate")
                          {
                              $primary_account_name = $primary_account['company_name'];
                          }else{
                              $primary_account_name = $primary_account['full_name'];
                          }
                              $primary_account_name = "#".$primary_account_name;
                      }
                      $latest_deal = getElement("deals", "WHERE link_related_to_account = {$account['id']} ORDER BY id DESC LIMIT 1");
                      if(!empty($latest_deal['deal_name'])){ $latest_deal_name = '#' . $latest_deal['deal_name']; }else{$latest_deal_name = ""; }


            echo "<tr id='".$account['id']."'>
                     <td>" . $i . "</td>
                     <td><a href='single-account.php?account=644372-111".$account['id']."'>" . $name ." <span style='font-size: 10px;color:#c69923'> ".$primary_account_name ."</span><br><span style='font-size: 10px;color:#c69923'>".$latest_deal_name."</span></a></td>
                     <td>" . $account['phone'] . "</td>
                     <td>" . $account['email'] . "</td>
                     <td>" . $account['category'] . "</td>
                     <td>" . $latest_deal['deal_name'] . "</td>
                     <td>" . $account_owner['full_name'] . "</td>";
                     ?>
<td> <span type='button' class='togg actions account-info-btn' data-id="<?php echo $account['id'];?>"  id='togglebutton'>...</span>

                          <div class="actions-list hidden">
                              <span class="pseudo"></span>
                              <ul>
                                 <?php
//                                 if($_SESSION['email'] == "r.amin@freezoner.net" || $account['added_by'] == $_SESSION['userid'] || $account['assign_to'] == $user['hr_id'])
//                                 {
                                 ?>
                                 <li data-class='Edit'><a class='btn btn-link li_edit' href="accounts.php?edit=account&id=2345-555<?php echo $account['id'];?>">Edit</a></li>
                                 <?php
                                // }
                               if($_SESSION['email'] == "r.amin@freezoner.net" || $_SESSION['email'] == "relghoul@freezoner.net")
                               {
                             ?>
                                 <li data-class='Delete'><button class='btn btn-link account_buttons li_delete delete' data-toggle='modal' data-target='#accountDelete' data-id="<?php echo $account['id'];?>">Delete</button></li>
                             <?php
                              }
                             ?>
                                 </ul>
                           </div>
                   </td>

<?php
            echo "</tr>";
        }
      }else{
            echo "<tr><td class='text-center' colspan='100%'><b>No Accounts Found </b></td></tr>";
      }
}

/*********************************** Search hr ************************************/
if(isset($_POST['hr_search']))
{

  $search = $_POST['hr_search'];

  $hrs = getElements("employees","WHERE (first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR full_name LIKE  '%$search%' OR personal_email_1 LIKE '%$search%' OR phone LIKE '%$search%' OR job_title LIKE '%$search%') ORDER BY id DESC");

    if(!empty($hrs))
    {
        $hr_privileges = explode(" - ",$online_user['hr_privileges']);
        $i = 0;
        foreach($hrs as $hr)
        {
            $i++;
            echo "<tr id='".$hr['id']."'>
                        <td>" . $i . "</td>
                             <td><a href='single-hr.php?hr=644372-111" . $hr['id'] . "'>" . $hr['full_name'] . "</a></td>
                             <td>" . $hr['employee_id'] . "</td>
                             <td>" . $hr['job_title'] . "</td>
                             <td>" . $hr['joining_date'] . "</td>
                             <td>" . $hr['visa_expiry_date'] . "</td>
                             <td>" . $hr['contract_effective_date'] . "</td>";
                              ?>
                         <td> <span class='actions togg hr-info-btn' data-id="<?php echo $hr['id'];?>"  id='togglebutton'>...</span>

                              <div class="actions-list hidden">
                                  <span class="pseudo"></span>
                                  <ul>
                                  <?php
                                  if(in_array("Edit Employee", $hr_privileges))
                                  {
                                  ?>
                                     <li data-class='Edit'><a class='btn btn-link li_edit' href="hr.php?edit=hr&id=2345-555<?php echo $hr['id'];?>">Edit</a></li>
                                 <?php
                                  }
                                  if(in_array("Delete", $hr_privileges))
                                  {
                                  ?>
                                     <li data-class='Delete'><button class='btn btn-link hr_buttons li_delete delete' data-toggle='modal' data-target='#hrDelete' data-id="<?php echo $hr['id'];?>">Delete</button></li>
                                 <?php
                                  }
                                  ?>
                                     </ul>
                               </div>

                         </td>
                       <?php
                         echo  "</tr>";
        }
      }else{
            echo "<tr><td class='text-center' colspan='100%'><b>No HR Found with ".$search."</b></td></tr>";
      }
}

/*********************************** Show list HR ************************************/
if(isset($_POST['show_hrs']))
{
    $show = $_POST['show_hrs'];
    $table = $_POST['table'];
    if($show == 'all')
    {
         $hrs = getElements($table,"ORDER BY id DESC");
    }else{
         $hrs = getElements($table,"ORDER BY id DESC LIMIT {$show}");
    }

    if(!empty($hrs))
    {
         $hr_privileges = explode(" - ",$online_user['hr_privileges']);
        $i = 0;
        foreach($hrs as $hr)
        {
           $i++;
            echo "<tr id='".$hr['id']."'>
                             <td>" . $i . "</td>
                             <td><a href='single-hr.php?hr=644372-111" . $hr['id'] . "'>" . $hr['full_name'] . "</a></td>
                             <td>" . $hr['employee_id'] . "</td>
                             <td>" . $hr['job_title'] . "</td>
                             <td>" . $hr['joining_date'] . "</td>
                             <td>" . $hr['visa_expiry_date'] . "</td>
                             <td>" . $hr['contract_effective_date'] . "</td>";
                               ?>
                         <td> <span class='actions togg hr-info-btn' data-id="<?php echo $hr['id'];?>"  id='togglebutton'>...</span>

                              <div class="actions-list hidden">
                                  <span class="pseudo"></span>
                                  <ul>
                                  <?php
                                  if(in_array("Edit Employee", $hr_privileges))
                                  {
                                  ?>
                                     <li data-class='Edit'><a class='btn btn-link li_edit' href="hr.php?edit=hr&id=2345-555<?php echo $hr['id'];?>">Edit</a></li>
                                 <?php
                                  }
                                  if(in_array("Delete", $hr_privileges))
                                  {
                                  ?>
                                     <li data-class='Delete'><button class='btn btn-link hr_buttons li_delete delete' data-toggle='modal' data-target='#hrDelete' data-id="<?php echo $hr['id'];?>">Delete</button></li>
                                 <?php
                                  }
                                  ?>
                                     </ul>
                               </div>

                         </td>
                       <?php
                                 echo  "</tr>";

        }
      }else{
            echo "<tr><td class='text-center' colspan='100%'><b>No HR Found </b></td></tr>";
      }
}

/*********************************** Search renewals ************************************/
if(isset($_POST['renewals_search']))
{

  $search = $_POST['renewals_search'];

  $renewals = getElements("renewals","WHERE (item LIKE '%$search%' OR name LIKE '%$search%' OR source LIKE '%$search%' OR status LIKE '%$search%' OR date LIKE '%$search%') AND expiry_date IS NOT NULL AND expiry_date != '0000-00-00' ORDER BY
  (expiry_date < NOW()) ASC,
  (greatest(expiry_date, NOW())) ASC,
  (least(expiry_date, NOW())) DESC");

                if(!empty($renewals))
                {
                   $i = 0;
                   foreach($renewals as $renewal)
                   {
                       $i++;

                       if($renewal['account_id'] > 0)
                       {
                           $link = "https://tatweeratmatah.com/freezonercrm/single-account.php?account=644372-111".$renewal['account_id']."&renewal=renewal";

                       }else if($renewal['deal_id'] > 0){
                            $link = "https://tatweeratmatah.com/freezonercrm/single-deal.php?deal=644372-111".$renewal['deal_id']."&renewal=renewal";


                       }else{
                             $link = "https://tatweeratmatah.com/freezonercrm/single-renewals.php?renewals=644372-111".$renewal['id']."&renewal=renewal";
                        }
                       //set expiry date less than or = 45 day with red color
                        $secondsInAMinute = 60;
                        $secondsInAnHour  = 60 * $secondsInAMinute;
                        $secondsInADay    = 24 * $secondsInAnHour;
                        $expiry_date = strtotime($renewal['expiry_date']);
                        $now = time();
                        $distance = $expiry_date - $now;
                        $days = floor($distance / $secondsInADay);

                        if($days <= 30 )
                        {

                            $icon=" <span style='color: #fff; background: #c69923;height:40px;width: 40px;border-radius: 50%;padding: 4px;'>30</span>";
                        }
                        else if($days > 30 && $days <= 45 )
                        {

                            $icon=" <span style='color: #fff; background: #c69923;height:40px;width: 40px;border-radius: 50%;padding: 4px;'>45</span>";
                        }else if($days > 45 && $days <= 60)
                        {

                            $icon=" <span style='color: #fff; background: #c69923;height:40px;width: 40px;padding: 4px;border-radius: 50%;padding: 4px;'>60</span>";

                        }else{
                             $icon=' ';
                        }


                               echo "<tr id='".$renewal['id']."'>
                                 <td>" . $i . "</td>
                                 <td>" . $renewal['source'] . "</td>
                                 <td> <a href='".$link."' target='_blank'>" . $renewal['item']  ."</a></td>
                                 <td>" . $icon . "</td>
                                 <td>" . $renewal['expiry_date'] . "</td>";



                              ?>

                  <td> <span class='actions togg renewal-info-btn ' data-id="<?php echo $renewal['id'];?>"  id='togglebutton'>...</span>
                    <div class="actions-list hidden">
                          <span class="pseudo"></span>
                          <ul>
                              <li data-class="Delegate"><button class='btn btn-link li_delegation' data-toggle='modal' data-target="#myModalDelegatesearch<?php echo $renewal['id'];?>" data-id="<?php echo $renewal['id'];?>">Delegate</button></li>

                              <li data-class="Qualify"><button class='btn btn-link li_qualify' data-toggle='modal' data-target="#myModalQualifysearch<?php echo $renewal['id'];?>" data-id="<?php echo $renewal['id'];?>">Qualify</button></li>

                           </ul>
                     </div>

                                      <!-- Delegate Modal -->
                                      <div class="modal fade myModalDelegate" id="myModalDelegatesearch<?php echo $renewal['id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                        <div class="modal-dialog" role="document">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                  <span aria-hidden="true">&times;</span>
                                              </button>
                                              <h4 class="modal-title">Delegate</h4>
                                            </div>
                                            <div class="modal-body clearfix">
                                              <div class="alert alert-success text-center hidden"></div>
                                              <div class="alert alert-danger text-center hidden"><ul></ul></div>
                                             <form method="post" action="" class="delegate_renewals" id="delegate_renewals">
                                                  <label for="employee_id">Choose Employee<span style="color:#dd3333;font-weight:600">*</span></label>
                                                  <select name="employee_id" id="employee_id" required class="form-control">
                                                    <option value=''></option>
                                                  <?php
                                                  $employees = getElements("employees","ORDER BY full_name ASC");
                                                  foreach($employees as $employee)
                                                  {
                                                    echo "<option value='".$employee['id']."'";
                                                    if($employee['id'] == $renewal['owner'])
                                                    {
                                                        echo " selected";
                                                    }
                                                    echo ">" . $employee['full_name'] . "</option>";
                                                  }
                                                  ?>
                                                  </select>
                                                  <input type="hidden" name="renewal_id" value="<?php echo $renewal['id'];?>">
                                                  <input type="hidden" name="delegate_renewals" value="delegate_renewals">
                                                  <p>&nbsp;</p>
                                                  <input type="submit" value="Submit" class="btn background-color pull-right">
                                             </form>
                                            </div>

                                          </div>
                                        </div>
                                      </div>
                                      <!-- End Delegate Modal -->

                                      <!-- Qualify Modal -->
                                      <div class="modal fade myModalQualify" id="myModalQualifysearch<?php echo $renewal['id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                        <div class="modal-dialog" role="document">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                  <span aria-hidden="true">&times;</span>
                                              </button>
                                              <h4 class="modal-title">Qualify</h4>
                                            </div>
                                            <div class="modal-body clearfix">
                                              <form method="post" action="" id="formqualify<?php echo $renewal['id'];?>" class="qualify_renewals">

                                                  <label for="qualify">Qualify<span style="color:#dd3333;font-weight:600">*</span></label>
                                                  <select name="qualify" required class="form-control qualify">
                                                    <option value=''></option>
                                                    <option value='COLD' <?php if($renewal['status'] == "COLD"){ echo " selected"; }?> data-class="lost-reason">COLD</option>
                                                    <option value='HOT' data-class="HOT" <?php if($renewal['status'] == "HOT"){ echo " selected"; $hidden="";}else{$hidden="hidden";}?>>HOT</option>
                                                    <option value='WARM' <?php if($renewal['status'] == "WARM"){ echo " selected"; }?>>WARM</option>

                                                  </select>
                                                  <!--
                                                  <div class="dataClass HOT  <?php echo $hidden;?>">

                                                      <label for="deal_id">Choose Deal</label>
                                                      <select name="deal_id" id="deal_id" class="form-control">
                                                        <option value=''></option>
                                                      <?php
                                                      $deals = getElements("deals","ORDER BY deal_name ASC");
                                                      foreach($deals as $deal)
                                                      {
                                                        echo "<option value='".$deal['id']."'";
                                                        if($deal['id'] == $renewal['new_deal'])
                                                        {
                                                            echo " selected";
                                                        }
                                                        echo ">".$deal['deal_name']."</option>";
                                                      }
                                                      ?>
                                                      </select>

                                                  </div>
                                                  -->

                                                  <textarea name="lost-reason" class="form-control lost-reason dataClass COLD <?php  if($renewal['status'] != "COLD"){ echo " hidden"; }?>" placeholder="Lost Reason"><?php echo $renewal['lost_reason']; ?></textarea>


                                                  <input type="hidden" name="renewal_id" value="<?php echo $renewal['id'];?>">
                                                  <input type="hidden" name="opp_id" class="opp_id" value="<?php echo $renewal['opp_id'];?>">
                                                  <input type="hidden" name="qualify_renewals" value="qualify_renewals">
                                                  <p>&nbsp;</p>
                                                  <input type="submit" value="Submit" class="btn background-color pull-right">
                                              </form>
                                            </div>

                                          </div>
                                        </div>
                                      </div>
                                      <!-- End Qualify Modal -->


                                      </td>
                               </tr>
                             <?php

                    }

                }else{
                    echo "<tr><td class='text-center' colspan='100%'><b>No Renewals Found for ".$search."</b></td></tr>";
                }
}


/*********************************** Search contacts ************************************/
if(isset($_POST['contact_search']))
{
  $search = $_POST['contact_search'];
  //$admin = getElement("admins","WHERE name LIKE '%$search%'");


   $type = $_POST['type'];
   if($type == "Existing_Client"){ $type = " AND tags LIKE '%Existing Client%' "; }else if($type == "not_Existing_Client"){ $type = " AND tags NOT LIKE '%Existing Client%' "; }else{  $type = ""; }

  $contacts = getElements("contacts","WHERE (first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR full_name LIKE '%$search%' OR email LIKE '%$search%' OR phone LIKE '%$search%' OR nationality LIKE '%$search%' OR residency LIKE '%$search%' OR contact_type LIKE '%$search%' OR contact_owner LIKE '%$search%') {$type} ORDER BY id DESC");


    if(!empty($contacts))
    {
        $j = 0;
        foreach($contacts as $contact)
        {
            $j++;
            $full_name = $contact['full_name'];
            $tags = explode(",",$contact['tags']);
                        $tag = '';
                        $existing_tags = array();
                        for($i=0;$i<count($tags);$i++)
                        {
                              if(!empty($tags[$i]))
                              {

                                  if(!in_array($tags[$i], $existing_tags))
                                  {
                                    $tag.= "<p class='badge-design'>".$tags[$i]."</p>";
                                  }
                                  $existing_tags[]= $tags[$i];

                              }

                        }

                        //$contact_owner = getElement("accounts","WHERE id={$contact['account_id']}");
                        echo "<tr id='".$contact['id']."'>
                                 <td>" . $j . "</td>
                                 <td><a href='single-contact.php?contact=644372-111".$contact['id']."'>" . $contact['full_name'] . "</a></td>
                                 <td>" . $tag . "</td>
                                 <td>" . $contact['contact_type'] . "</td>
                                 <td>" . $contact['phone'] . "</td>
                                 <td>" . $contact['email'] . "</td>
                                 <td>" . $contact['category'] . "</td>
                                 <td>" . $contact['contact_owner'] . "</td>";
                  ?>
                   <td> <span class='actions togg contact-info-btn' data-id="<?php echo $contact['id'];?>"  id='togglebutton'>...</span>
                    <div class="actions-list hidden">
                                      <span class="pseudo"></span>
                                      <ul>
                                        <?php
                                        if(in_array("Edit Contacts", $contacts_privileges))
                                        {
                                        ?>
                                         <li data-class='Edit'><a class='btn btn-link li_edit' href="contacts.php?edit=contact&id=2345-555<?php echo $contact['id'];?>">Edit</a></li>
                                        <?php
                                        }
                                        if(in_array("Delete Contacts", $contacts_privileges))
                                        {
                                        ?>
                                         <li data-class='Delete'><button class='btn btn-link contact_buttons li_delete delete' data-toggle='modal' data-target='#contactDelete' data-id="<?php echo $contact['id'];?>">Delete</button></li>
                                     <?php
                                        }
                                      ?>
                                       </ul>
                                   </div>
                   </td>
                 <?php

                 echo "</tr>";
        }
      }else{
            echo "<tr><td class='text-center' colspan='100%'><b>No Contacts Found with ".$search."</b></td></tr>";
      }
}

/*********************************** Search Deals ************************************/
if(isset($_POST['deals_search']))
{

  $search = $_POST['deals_search'];
  //$admin = getElement("admins","WHERE name LIKE '%$search%'");

   $type = $_POST['type'];
   if($type == "Delivered"){ $type = " AND delivered_status = 'Delivered' "; }else if($type == "not_Delivered"){ $type = " AND delivered_status = 'Not Delivered' "; }else{  $type = ""; }

  $deals = getElements("deals","WHERE (deal_owner LIKE '%$search%' OR deal_name LIKE '%$search%' OR deal_amount LIKE '%$search%' OR service_type LIKE '%$search%' OR closing_date LIKE '%$search%' OR link_related_to_account IN (SELECT id FROM accounts WHERE full_name LIKE '%$search%' OR company_name LIKE '%$search%') OR contact_id IN (SELECT id FROM contacts WHERE full_name = '%$search%')) {$type} ORDER BY id DESC");

     echo " type: ".$type;
      if(!empty($deals))
      {
          $i = 0;
          foreach($deals as $deal)
          {
            $i++;
            //$owner = getElement("employees","WHERE id={$deal['assign_to']}");
            $account = getElement("accounts","WHERE id = {$deal['link_related_to_account']}");
            $contact = getElement("contacts","WHERE id = {$deal['contact_id']}");
            if(!empty($deal['original_document']))
                  {
                      $original_document = "Yes";
                  }else{
                      $original_document = "No";
                  }
               if($account['account_type'] == "Corporate")
               {
                  $name = $account['company_name'];
               }else{
                  $name = $account['full_name'];
               }

                      $categories = getElements("categories","WHERE table_name = 'deals' AND table_id = {$deal['id']} ORDER BY id ASC LIMIT 3");


                      $deal_categories="";
                      if(!empty($categories))
                      {
                              $deal_categories.="#";
                              foreach($categories as $category)
                              {
                                 $deal_categories.= $category['service_category'] .  " - ";

                              }

                      }


            echo "<tr id='".$deal['id']."'>
                     <td>" . $i . "</td>
                     <td><a href='single-deal.php?deal=644372-111".$deal['id']."'>" . $deal['deal_name'] ." <br><span style='font-size: 10px;color: #c69923;'>" . $deal_categories .  "</span></a></td>
                     <td>" . $deal['deal_amount'] . "</td>
                     <td>" . $deal['closing_date'] . "</td>
                     <td>" . $name . "</td>
                     <td>" . $contact['full_name'] . "</td>
                     <td>" . $deal['deal_owner'] . "</td>
                     <td>" . $original_document . "</td>";
               ?>
                            <td> <span class='actions togg deal-info-btn' data-id="<?php echo $deal['id'];?>"  id='togglebutton'>...</span>

                              <div class="actions-list hidden">
                                  <span class="pseudo"></span>
                                  <ul>
                                     <?php
//                                     if($_SESSION['email'] == "r.amin@freezoner.net" || $deal['added_by'] == $_SESSION['userid'])
//                                     {
                                     ?>
                                     <li data-class='Edit'><a class='btn btn-link li_edit' href="deals.php?edit=deal&id=2345-555<?php echo $deal['id'];?>">Edit</a></li>
                                    <?php
                                    // }
                                     if($_SESSION['email'] == "r.amin@freezoner.net" || $_SESSION['email'] == "relghoul@freezoner.net")
                                     {
                                     ?>
                                     <li data-class='Delete'><button class='btn btn-link deal_buttons li_delete delete' data-toggle='modal' data-target='#dealDelete' data-id="<?php echo $deal['id'];?>">Delete</button></li>
                                    <?php
                                     }
                                    ?>
                                     </ul>
                               </div>


                            </td>
                          <?php
                 echo "</tr>";
        }
     }else{
        echo "<tr><td class='text-center' colspan='100%'><b>No Deals Found for ".$search."</b></td></tr>";
    }
}

/*********************************** Show list Deals ************************************/
if(isset($_POST['show_deals']))
{
    $show = $_POST['show_deals'];
    $table = $_POST['table'];
    if($show == 'all')
    {
         $deals = getElements($table,"ORDER BY id DESC");
    }else{
         $deals = getElements($table,"ORDER BY id DESC LIMIT {$show}");
    }

    if(!empty($deals))
    {
        $i = 0;
        foreach($deals as $deal)
        {
           $i++;
            //$account_owner = getElement("employees","WHERE id={$account['assign_to']}");
            $account = getElement("accounts","WHERE id = {$deal['link_related_to_account']}");
            $contact = getElement("contacts","WHERE id = {$deal['contact_id']}");
             if(!empty($deal['original_document']))
                  {
                      $original_document = "Yes";
                  }else{
                      $original_document = "No";
                  }
               if($account['account_type'] == "Corporate")
               {
                  $name = $account['company_name'];
               }else{
                  $name = $account['full_name'];
               }
            echo "<tr id='".$deal['id']."'>
                     <td>" . $i . "</td>
                     <td><a href='single-deal.php?deal=644372-111".$deal['id']."'>" . $deal['deal_name'] . "</a></td>
                     <td>" . $deal['deal_amount'] . "</td>
                     <td>" . $deal['closing_date'] . "</td>
                     <td>" . $name . "</td>
                     <td>" . $contact['full_name'] . "</td>
                     <td>" . $deal['deal_owner'] . "</td>

                     <td>" . $original_document . "</td>
                     <td>" . $deal['delivered_status'] . "</td>";
              ?>
                            <td> <span class='actions togg deal-info-btn' data-id="<?php echo $deal['id'];?>"  id='togglebutton'>...</span>

                              <div class="actions-list hidden">
                                  <span class="pseudo"></span>
                                  <ul>
                                     <?php
//                                     if($_SESSION['email'] == "r.amin@freezoner.net" || $deal['added_by'] == $_SESSION['userid'])
//                                     {
                                     ?>
                                     <li data-class='Edit'><a class='btn btn-link li_edit' href="deals.php?edit=deal&id=2345-555<?php echo $deal['id'];?>">Edit</a></li>
                                    <?php
                                    // }
                                     if($_SESSION['email'] == "r.amin@freezoner.net" || $_SESSION['email'] == "relghoul@freezoner.net")
                                     {
                                     ?>
                                     <li data-class='Delete'><button class='btn btn-link deal_buttons li_delete delete' data-toggle='modal' data-target='#dealDelete' data-id="<?php echo $deal['id'];?>">Delete</button></li>
                                    <?php
                                     }
                                    ?>
                                     </ul>
                               </div>


                            </td>
                          <?php
                 echo "</tr>";
        }
      }else{
            echo "<tr><td class='text-center' colspan='100%'><b>No Deals Exist </b></td></tr>";
      }
}

/*********************************** Search Users ************************************/
if(isset($_POST['users_search']))
{

  $search = $_POST['users_search'];
  //$admin = getElement("admins","WHERE name LIKE '%$search%'");

  $users = getElements("users","WHERE (login_name LIKE '%$search%' OR username LIKE '%$search%' OR email LIKE '%$search%' OR phone LIKE '%$search%' OR job_title LIKE '%$search%') ORDER BY id DESC");

      if(!empty($users))
      {
          $i = 0;
          foreach($users as $user)
          {
            $i++;
           echo "<tr id='".$user['id']."'>
                     <td>" . $i . "</td>
                     <td><a href='single-user.php?user=644372-111".$user['id']."'>000" . $user['id'] . "</a></td>
                     <td>" . $user['username'] . "</td>
                     <td>" . $user['login_name'] . "</td>
                     <td>" . $user['date'] . "</td>
                     <td>" . $user['last_login'] . "</td>
                     <td class='user-status ";

                     if($user['status'] == "Active"){echo 'green';}else{echo 'red';}
                     echo "'>" .$user['status']. "</td>";

                     ?>
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
                           <?php
                 echo "</tr>";
          }
     }else{
        echo "<tr><td class='text-center' colspan='100%'><b>No Users Found for ".$search."</b></td></tr>";
    }
}

/*********************************** Show list Users ************************************/
if(isset($_POST['show_users']))
{
    $show = $_POST['show_users'];
    $table = $_POST['table'];
    if($show == 'all')
    {
         $users = getElements($table,"ORDER BY id DESC");
    }else{
         $users = getElements($table,"ORDER BY id DESC LIMIT {$show}");
    }

    if(!empty($users))
    {
        $i = 0;
        foreach($users as $user)
        {
           $i++;
            echo "<tr id='".$user['id']."'>
                     <td>" . $i . "</td>
                     <td><a href='single-user.php?user=644372-111".$user['id']."'>000" . $user['id'] . "</a></td>
                     <td>" . $user['username'] . "</td>
                     <td>" . $user['login_name'] . "</td>
                     <td>" . $user['date'] . "</td>
                     <td>" . $user['last_login'] . "</td>
                     <td class='user-status ";
                     if($user['status'] == "Active"){echo 'green';}else{echo 'red';}
                     echo "'>" .$user['status']. "</td>";

                      ?>
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
                           <?php
                 echo "</tr>";
        }
      }else{
            echo "<tr><td class='text-center' colspan='100%'><b>No Users Found </b></td></tr>";
      }
}

/*********************************** Search Company Asset ************************************/
if(isset($_POST['company_asset_search']))
{

  $search = $_POST['company_asset_search'];
  //$admin = getElement("admins","WHERE name LIKE '%$search%'");

  $company = getElements("company","WHERE (type LIKE '%$search%' OR name LIKE '%$search%' OR country LIKE '%$search%' OR unit_no LIKE '%$search%' OR contract_type LIKE '%$search%' OR contract_duration LIKE '%$search%' OR stackholder_type LIKE '%$search%' OR stackholder_name LIKE '%$search%' OR stackholder_company LIKE '%$search%' OR stackholder_email LIKE '%$search%' OR stackholder_phone LIKE '%$search%' OR payment_intervals LIKE '%$search%' OR total_amount LIKE '%$search%' OR interval_amount LIKE '%$search%' OR payment_amount LIKE '%$search%') ORDER BY id DESC");
      if(!empty($company))
      {
          $i = 0;
          foreach($company as $c)
          {
            $i++;
            echo "<tr id='".$c['id']."'>
                     <td>" . $i . "</td>
                     <td><a href='single-company-asset.php?company=644372-111".$c['id']."'>" . $c['name'] . "</a></td>
                     <td>" . $c['type'] . "</td>
                     <td>" . $c['effective_date'] . "</td>
                     <td>" . $c['contract_expiry_date'] . "</td>
                     <td>" . $c['payment_intervals'] . "</td>
                     <td>" . $c['due_dates'] . "</td>";

                ?>
                         <td> <span class='actions togg company-info-btn' data-id="<?php echo $c['id'];?>"  id='togglebutton'>...</span>

                         <div class="actions-list hidden">
                                  <span class="pseudo"></span>
                           <ul>
           <?php
           if(in_array("Edit", $company_assets_privileges))
           {
           ?>
                              <li data-class='Edit'><a class='btn btn-link li_edit' href="company_assets.php?edit=company&id=2345-555<?php echo $c['id'];?>">Edit</a></li>
           <?php
            }
            if(in_array("Delete", $company_assets_privileges))
            {
           ?>
                              <li data-class='Delete'><button class='btn btn-link company_buttons li_delete delete' data-toggle='modal' data-target='#companyDelete' data-id="<?php echo $c['id'];?>">Delete</button></li>
           <?php
           }
           ?>
                           </ul></div>

                         </td>
                       <?php

                echo '</tr>';
          }
     }else{
        echo "<tr><td class='text-center' colspan='100%'><b>No Company Assets Exist</b></td></tr>";
    }
}

/*********************************** Show list Company Asset ************************************/
if(isset($_POST['show_company_asset']))
{
    $show = $_POST['show_company_asset'];
    $table = $_POST['table'];
    if($show == 'all')
    {
         $company = getElements($table,"ORDER BY id DESC");
    }else{
         $company = getElements($table,"ORDER BY id DESC LIMIT {$show}");
    }

       if(!empty($company))
      {
          $i = 0;
          foreach($company as $c)
          {
            $i++;
            echo "<tr id='".$c['id']."'>
                     <td>" . $i . "</td>
                     <td><a href='single-company-asset.php?company=644372-111".$c['id']."'>" . $c['name'] . "</a></td>
                     <td>" . $c['type'] . "</td>
                     <td>" . $c['effective_date'] . "</td>
                     <td>" . $c['contract_expiry_date'] . "</td>
                     <td>" . $c['payment_intervals'] . "</td>
                     <td>" . $c['due_dates'] . "</td>";
                    ?>
                         <td> <span class='actions togg company-info-btn' data-id="<?php echo $c['id'];?>"  id='togglebutton'>...</span>

                         <div class="actions-list hidden">
                                  <span class="pseudo"></span>
                           <ul>
           <?php
           if(in_array("Edit", $company_assets_privileges))
           {
           ?>
                              <li data-class='Edit'><a class='btn btn-link li_edit' href="company_assets.php?edit=company&id=2345-555<?php echo $c['id'];?>">Edit</a></li>
           <?php
            }
            if(in_array("Delete", $company_assets_privileges))
            {
           ?>
                              <li data-class='Delete'><button class='btn btn-link company_buttons li_delete delete' data-toggle='modal' data-target='#companyDelete' data-id="<?php echo $c['id'];?>">Delete</button></li>
           <?php
           }
           ?>
                           </ul></div>

                         </td>
                       <?php
                echo '</tr>';
          }
     }else{
        echo "<tr><td class='text-center' colspan='100%'><b>No Company Assets Exist</b></td></tr>";
    }
}

/*********************************** Search finance ************************************/
if(isset($_POST['finance_search']) || ($_POST['table'] == "finance" && isset($_POST['search_report'])))
{
    if(isset($_POST['finance_search']))
    {
        $search = $_POST['finance_search'];

    }else if(isset($_POST['report_table_search']))
    {
        $search = $_POST['report_table_search'];
    }

  $table = $_POST['table'];
  $admin = getElement("admins","WHERE name LIKE '%$search%'");
  if(!empty($admin))
  {
      $admin_id = $admin['id'];
  }else{
      $admin_id = 0;
  }

  $finances = searchElements($table,"*","WHERE (type_of_service LIKE '%$search%' OR vat LIKE '%$search%' OR price LIKE '%$search%' OR quantity LIKE '%$search%' OR total_without_vat LIKE '%$search%' OR total_after_vat LIKE '%$search%') ORDER BY id DESC");

    if(!empty($finances))
    {
        $i = 0;
          foreach($finances as $finance)
          {
            $i++;
            echo "<tr id='".$finance['id']."'>
                     <td>" . $i . "</td>
                     <td>" . $finance['type_of_service'] . "</td>
                     <td>" . $finance['vat'] . "</td>
                     <td>" . $finance['price'] . "</td>
                     <td>" . $finance['quantity'] . "</td>
                     <td>" . $finance['total_without_vat'] . "</td>
                     <td>" . $finance['total_after_vat'] . "</td>";
               if(!isset($_POST['report_table_search']))
               {
               ?>
                           <td> <span class='actions togg finance-info-btn' data-id="<?php echo $finance['id'];?>"  id='togglebutton'>...</span>

                              <div class="actions-list hidden">
                                  <span class="pseudo"></span>
                                  <ul>
                                    <?php
                                   if(in_array("Edit", $finance_privileges))
                                   {
                                       ?>
                                    <li data-class='Edit'><button class='btn btn-link finance_buttons li_edit' data-toggle='modal' data-target="#financeEdit<?php echo $finance['id'];?>" data-id="<?php echo $finance['id'];?>" data-class='finance_edit'>Edit</button></li>
                                  <?php
                                   }
                                   if(in_array("Delete", $finance_privileges))
                                   {
                                       ?>
                                    <li data-class='Delete'><button class='btn btn-link finance_buttons li_delete delete' data-toggle='modal' data-target='#financeDelete' data-id="<?php echo $finance['id'];?>">Delete</button></li>
                                    <?php
                                   }
                                   ?>

                                     </ul>
                                </div>

                           </td>
                         <?php
               }

           echo "</tr>";

          }
      }else{
            echo "<tr><td class='text-center' colspan='100%'><b>No Finance Found with ".$search."</b></td></tr>";
      }
}

/*********************************** Show list finance ************************************/
if(isset($_POST['show_finance']) || ($_POST['table'] == "finance" && isset($_POST['show_report'])))
{
    if(isset($_POST['show_report']))
    {
        $show = $_POST['show_report'];

    }else if(isset($_POST['show_finance']))
    {
        $show = $_POST['show_finance'];
    }

    $table = $_POST['table'];

    if($show == 'all')
    {
         $finances = getElements($table,"ORDER BY id DESC");
    }else{
         $finances = getElements($table,"ORDER BY id DESC LIMIT {$show}");
    }

    if(!empty($finances))
    {
        $i = 0;
          foreach($finances as $finance)
          {
            $i++;
            echo "<tr id='".$finance['id']."'>
                     <td>" . $i . "</td>
                     <td>" . $finance['type_of_service'] . "</td>
                     <td>" . $finance['vat'] . "</td>
                     <td>" . $finance['price'] . "</td>
                     <td>" . $finance['quantity'] . "</td>
                     <td>" . $finance['total_without_vat'] . "</td>
                     <td>" . $finance['total_after_vat'] . "</td>";
                     ?>
                           <td> <span class='actions togg finance-info-btn' data-id="<?php echo $finance['id'];?>"  id='togglebutton'>...</span>

                              <div class="actions-list hidden">
                                  <span class="pseudo"></span>
                                  <ul>
                                    <?php
                                   if(in_array("Edit", $finance_privileges))
                                   {
                                       ?>
                                    <li data-class='Edit'><button class='btn btn-link finance_buttons li_edit' data-toggle='modal' data-target="#financeEdit<?php echo $finance['id'];?>" data-id="<?php echo $finance['id'];?>" data-class='finance_edit'>Edit</button></li>
                                  <?php
                                   }
                                   if(in_array("Delete", $finance_privileges))
                                   {
                                       ?>
                                    <li data-class='Delete'><button class='btn btn-link finance_buttons li_delete delete' data-toggle='modal' data-target='#financeDelete' data-id="<?php echo $finance['id'];?>">Delete</button></li>
                                    <?php
                                   }
                                   ?>

                                     </ul>
                                </div>

                           </td>
                         <?php
           echo "</tr>";

          }
      }else{
            echo "<tr><td class='text-center' colspan='100%'><b>No Finance Found </b></td></tr>";
      }
}


/*********************************** Search Templates ************************************/
if(isset($_POST['templates_search']))
{

  $search = $_POST['templates_search'];

  $table = $_POST['table'];
  $user = getElement("users","WHERE username LIKE '%$search%'");
  if(!empty($user))
  {
      $user_id = $user['id'];
  }else{
      $user_id = 0;
  }

  $templates = searchElements($table,"*","WHERE (template_name LIKE '%$search%' OR document LIKE '%$search%' OR added_by LIKE {$user_id}) ORDER BY id DESC");

    if(!empty($templates))
    {
        $i = 0;
          foreach($templates as $template)
          {
            $i++;
            $document = explode("_file_",$template['document']);
                       if(empty($document[1]))
                       {
                           $document[1] = "";
                       }
                       $owner = getField("users","id,username","WHERE id = {$template['added_by']}");
                     echo "<tr id='".$template['id']."'>
                             <td>" . $i . "</td>
                             <td>" . $owner['username'] . "</a></td>
                             <td>" . $template['template_name']. "</td>
                             <td>" . "<a href='download.php?file=" . $template['document'] . "' tabindex='-1' target='_blank' class='downfile'>" . $document[1] . "</a>". "</td>
                             <td>" . $template['updated_date'] . "</td>";

                       ?>
                        <td> <span class='actions togg template-info-btn' data-id="<?php echo $template['id'];?>"  id='togglebutton'>...</span>

                              <div class="actions-list hidden">
                                  <span class="pseudo"></span>
                                  <ul>
                                  <?php
                                  if(in_array("Edit Templates", $templates_privileges))
                                  {
                                  ?>
                                    <li data-class='Edit'><button class='btn btn-link template_buttons li_edit' data-toggle='modal' data-target="#templateEdit" data-id="<?php echo $template['id'];?>" data-class='template_edit'>Edit</button></li>
                                  <?php
                                  }
                                  if(in_array("Delete Templates", $templates_privileges))
                                  {
                                  ?>
                                    <li data-class='Delete'><button class='btn btn-link template_buttons li_delete delete' data-toggle='modal' data-target='#templateDelete' data-id="<?php echo $template['id'];?>">Delete</button></li>
                                 <?php
                                 }
                                 ?>

                                     </ul>
                                </div>

                        </td>
                      <?php

                      echo "</tr>";
          }
      }else{
            echo "<tr><td class='text-center' colspan='100%'><b>No Template Found with ".$search."</b></td></tr>";
      }
}

/*********************************** Search events ************************************/
if(isset($_POST['event_search']))
{

  $search = $_POST['event_search'];

  $table = $_POST['table'];


  $events = searchElements($table,"*","WHERE (title LIKE '%$search%' OR location LIKE '%$search%' OR event_date LIKE '%$search%' OR start_time LIKE '%$search%' OR end_time LIKE '%$search%' OR slots LIKE '%$search%' OR attendees LIKE '%$search%' OR vacant_slots LIKE '%$search%' OR status LIKE '%$search%') ORDER BY id DESC");

    if(!empty($events))
    {
        $i = 0;
          foreach($events as $event)
             {  $i++;
                $attendees = getCounts("event_registers", "WHERE event_id = {$event['id']}");

                $vacant_slots = $event['slots'] - $attendees ;
$added_by = getField("users","id, username", "WHERE id = {$event['added_by']}");

                    echo "<tr id='".$event['id']."'>
                             <td>" . $i . "</td>
                             <td><a href='single-event.php?event=644372-111".$event['id']."'>" . $event['title'] . "</a></td>
                             <td>" . $event['event_date'] . "</td>
                             <td>" . $event['location'] . "</td>
                             <td>" . $event['start_time'] . "</td>
                             <td>" . $event['end_time'] . "</td>
                             <td>" . $added_by['username'] . "</td>
                             <td>" . $event['slots'] . "</td>
                             <td>" . $attendees . "</td>
                             <td>" . $vacant_slots . "</td>
                             <td>" . $event['status'] . "</td>";
                  ?>
                   <td> <span class='togg actions account-info-btn' data-id="<?php echo $event['id'];?>"  id='togglebutton'>...</span>

                          <div class="actions-list hidden">
                              <span class="pseudo"></span>
                              <ul>

                                 <li data-class='Edit'><button class='btn btn-link li_edit' data-toggle='modal' data-target="#editevent<?php echo $event['id'];?>">Edit</button></li>

                                 <li data-class='Delete'><button class='btn btn-link event_buttons li_delete delete' data-toggle='modal' data-target='#eventDelete' data-id="<?php echo $event['id'];?>">Delete</button></li>

                              </ul>
                           </div>
                   </td>
                 <?php

                 echo "</tr>"; ?>

                <!--  edit event Modal -->
                <div id="editevent<?php echo $event['id'];?>" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                           <div class="modal-header modal-h-back">
                                <div class="col-xs-12">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Event</h4>
                                </div>
                            </div>

                            <form method="post" class="edit_event">
                              <div class="modal-body">
                                <form>
                                    <div class="row ">
                                        <div class="col-sm-6 col-xs-12">
                                            <label class="pure-material-textfield-outlined">
                                                <input placeholder=" " maxlength="80" name="title" required value="<?php echo $event['title'];?>">
                                                <span>Event title</span>
                                            </label>
                                        </div>

                                        <div class="col-sm-6 col-xs-12">
                                            <label class="pure-material-textfield-outlined">
                                                <input placeholder=" " type="date" required name="event_date" value="<?php echo $event['event_date'];?>">
                                                <span>Date</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="row ">
                                        <div class="col-sm-6 col-xs-12">
                                            <label class="pure-material-textfield-outlined">
                                                <input placeholder=" " type="time" required name="start_stime" style="margin-top: 6px;" value="<?php echo $event['start_time'];?>">
                                                <span>Start Time</span>
                                            </label>
                                        </div>

                                        <div class="col-sm-6 col-xs-12">
                                            <label class="pure-material-textfield-outlined">
                                                <input placeholder=" " type="time" required name="end_time" style="margin-top: 6px;" value="<?php echo $event['end_time'];?>">
                                                <span>End Time</span>
                                            </label>
                                        </div>
                                    </div>


                                    <div class="row ">
                                        <div class="col-xs-12">
                                            <label class="tacolor">Descrption</label>
                                            <textarea maxlength="600" class="form-control description" name="description" rows="">  <?php echo $event['description'];?></textarea>
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-sm-6 col-xs-12">
                                            <label class="pure-material-textfield-outlined">
                                                <input placeholder=" " name="location" required value="<?php echo $event['location'];?>">
                                                <span>Location</span>
                                            </label>
                                        </div>

                                        <div class="col-sm-6 col-xs-12">
                                            <label class="pure-material-textfield-outlined">
                                                <input placeholder=" " type="text" name="fees" value="<?php echo $event['fees'];?>">
                                                <span>Fees</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6 col-xs-12">
                                           <label class="pure-material-textfield-outlined">
                                                <input placeholder=" " type="number" name="slots" required value="<?php echo $event['slots'];?>">
                                                <span>Maximum No of Attendees</span>
                                            </label>

                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                           <div class='form-group text-center'>
                                             <label class="add-avatar">
                                                <div class="camera-img text-center">
                                                 <img src="<?php if(!empty($event['image'])){ echo 'layout/uploades/' . $event['image'];}else{echo 'layout/img/camera.svg';}?>" class="uploadImg img-responsive" id="uploadImg">
                                                 <input type="file" name="image" id="file" style="display: none;">
                                                </div>

                                                <h6 class='text-center'><b> 1100px * 250px</b></h6>
                                              </label>
                                            </div>
                                         </div>
                                    </div>



                                    <div class="row mt">
                                        <div class="col-xs-12 text-right">
                                            <input type="hidden" name="edit_event" value="edit_event">
                                            <input type="hidden" name="id" value="<?php echo $event['id'];?>">
                                            <button type="submit" class="btn btn-primary btn-grey ">Save</button>
                                        </div>
                                    </div>

                                </form>
                            </div>
                            </form>

                        </div>
                    </div>
                 </div>
            <?php
               }
      }else{
            echo "<tr><td class='text-center' colspan='100%'><b>No Template Found with ".$search."</b></td></tr>";
      }
}

/*********************************** Show list Templates ************************************/
if(isset($_POST['show_templates']))
{

    $show = $_POST['show_templates'];

    $table = $_POST['table'];

    if($show == 'all')
    {
         $templates = getElements($table,"ORDER BY id DESC");
    }else{
         $templates = getElements($table,"ORDER BY id DESC LIMIT {$show}");
    }

    if(!empty($templates))
    {
        $i = 0;
          foreach($templates as $template)
          {
            $i++;
         $document = explode("_file_",$template['document']);
                       if(empty($document[1]))
                       {
                           $document[1] = "";
                       }
                       $owner = getField("users","id,username","WHERE id = {$template['added_by']}");
                     echo "<tr id='".$template['id']."'>
                             <td>" . $i . "</td>
                             <td>" . $owner['username'] . "</a></td>
                             <td>" . $template['template_name']. "</td>
                             <td>" . "<a href='download.php?file=" . $template['document'] . "' tabindex='-1' target='_blank' class='downfile'>" . $document[1] . "</a>". "</td>
                             <td>" . $template['updated_date'] . "</td>";

                        ?>
                        <td> <span class='actions togg template-info-btn' data-id="<?php echo $template['id'];?>"  id='togglebutton'>...</span>

                              <div class="actions-list hidden">
                                  <span class="pseudo"></span>
                                  <ul>
                                  <?php
                                  if(in_array("Edit Templates", $templates_privileges))
                                  {
                                  ?>
                                    <li data-class='Edit'><button class='btn btn-link template_buttons li_edit' data-toggle='modal' data-target="#templateEdit" data-id="<?php echo $template['id'];?>" data-class='template_edit'>Edit</button></li>
                                  <?php
                                  }
                                  if(in_array("Delete Templates", $templates_privileges))
                                  {
                                  ?>
                                    <li data-class='Delete'><button class='btn btn-link template_buttons li_delete delete' data-toggle='modal' data-target='#templateDelete' data-id="<?php echo $template['id'];?>">Delete</button></li>
                                 <?php
                                 }
                                 ?>

                                     </ul>
                                </div>

                        </td>
                      <?php
                      echo "</tr>";
          }
      }else{
            echo "<tr><td class='text-center' colspan='100%'><b>No Template Found </b></td></tr>";
      }
}


/*********************************** Search home on contacts and accounts ************************************/
if(isset($_POST['search_value']) && isset($_POST['home_search_accounts_contacts']))
{

  $search = $_POST['search_value'];

    $stmthomecontactsfetchs = getElements("contacts", " WHERE first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR full_name LIKE '%$search%' OR email LIKE '%$search%' OR phone LIKE '%$search%' OR nationality LIKE '%$search%' OR residency LIKE '%$search%' OR contact_type LIKE '%$search%' OR contact_owner LIKE '%$search%' ");

    $stmthomeaccountsfetchs = getElements("accounts", " WHERE first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR full_name LIKE '%$search%' OR email LIKE '%$search%' OR phone LIKE '%$search%' OR category LIKE '%$search%' OR nationality LIKE '%$search%' OR residency LIKE '%$search%'  OR company_name LIKE '%$search%'  OR company_location LIKE '%$search%' OR source_type LIKE '%$search%' OR source_name LIKE '%$search%' OR account_type LIKE '%$search%' ");

    if(!empty($stmthomecontactsfetchs) && !empty($stmthomeaccountsfetchs))
    {

        foreach($stmthomecontactsfetchs as $result)
        {
           echo "<button class='task'>" . $result['full_name'] . " </button>";
        }

        foreach($stmthomeaccountsfetchs as $result2)
        {
              if($result2['account_type'] === "Corporate")
              {
                 echo "<button class='task'>" . $result2['company_name'] . "</button>";
              }else{
                  echo "<button class='task'>" . $result2['full_name'] . "</button>";
              }

        }
      }else{
            echo "<button class='task'>No resault Found with ".$search."</button>";
      }
}
/*********************************** Search website ************************************/
if(isset($_POST['website_search']))
{

  $search = $_POST['website_search'];

  $table = $_POST['table'];

  $websites = searchElements($table,"*","WHERE (phone LIKE '%$search%' OR email LIKE '%$search%' OR latest_service LIKE '%$search%') ORDER BY id DESC");

    if(!empty($websites))
    {
        $i = 0;
          foreach($websites as $website)
         {  $i++;

                    echo "<tr id='".$website['id']."'>
                    <td>" . $i . "</td>
                    <td>" . "</td>
                    <td>" . $website['phone'] . "</td>
                    <td>" . $website['email'] . "</td>
                    <td>" . $website['latest_service'] . "</td>
                    <td>" .  "</td>";
         ?>
          <td> <span class='togg actions ' data-id="<?php echo $website['id'];?>"  id='togglebutton'>...</span>

                 <div class="actions-list hidden">
                     <span class="pseudo"></span>
                     <ul>

                        <li data-class='Edit'><a class='btn btn-link li_edit' data-toggle='modal' data-target='#editWebsite<?php echo $website['id'];?>'>Edit</a></li>
                     <?php
                      if($_SESSION['email'] == "r.amin@freezoner.net" || $_SESSION['email'] == "relghoul@freezoner.net")
                      {
                    ?>
                        <li data-class='Delete'><button class='btn btn-link website_buttons li_delete delete' data-toggle='modal' data-target='#websiteDelete' data-id="<?php echo $website['id'];?>">Delete</button></li>
                    <?php
                     }
                    ?>
                        </ul>
                  </div>
          </td>
                    <div id="editWebsite<?php echo $website['id'];?>" class="modal fade" role="dialog">
                      <div class="modal-dialog modal-md">

                          <!-- Modal content-->
                          <div class="modal-content">
                              <div class="modal-header modal-h-back">

                                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                                      <h4 class="modal-title">Edit Website</h4>

                              </div>

                              <div class="modal-body">
                                  <form method="post" class="editWebsite">
                                      <div class="row mt">
                                          <div class="col-md-6 col-sm-6">
                                              <label class="pure-material-textfield-outlined">
                                                  <input placeholder=" " type="text" name="phone" value="<?php echo $website['phone'];?>">
                                                  <span>Phone</span>
                                              </label>
                                          </div>
                                          <div class="col-md-6 col-sm-6">
                                              <label class="pure-material-textfield-outlined">
                                                  <input placeholder=" " type="email" name="email" value="<?php echo $website['email'];?>">
                                                  <span>Email</span>
                                              </label>
                                          </div>

                                      </div>


                                      <div class="row mt">

                                              <div class="col-md-6 col-sm-6">
                                                  <label class="pure-material-textfield-outlined">
                                                      <input placeholder=" " type="text" name="latest_service" value="<?php echo $website['latest_service'];?>">
                                                      <span>Latest Service</span>
                                                  </label>
                                              </div>

                                      </div>

                                      <div class="row">
                                          <div class="col-xs-12 text-right"><input type="hidden" name="edit-website" value="edit-website"><input type="hidden" name="id" value="<?php echo $website['id'];?>">
                                              <button class="btn btn-defaut btn-ta-1" >Submit</button>
                                          </div>
                                      </div>



                                  </form>
                              </div>

                          </div>
                      </div>
                  </div>


         <?php  echo "</tr>";
         }
      }else{
            echo "<tr><td class='text-center' colspan='100%'><b>No website Found with ".$search."</b></td></tr>";
      }
}

/*********************************** Search newsletter ************************************/
if(isset($_POST['newsletter_search']))
{

  $search = $_POST['newsletter_search'];

  $table = $_POST['table'];

  $newsletters = searchElements($table,"*","WHERE (name LIKE '%$search%' OR type LIKE '%$search%' OR start_date LIKE '%$search%' OR audience_size LIKE '%$search%' OR status LIKE '%$search%') ORDER BY id DESC");

    if(!empty($newsletters))
    {
        $i = 0;
          foreach($newsletters as $newsletter)
         {  $i++;

                    echo "<tr id='".$newsletter['id']."'>
                    <td>" . $i . "</td>
                    <td>" .$newsletter['name'] ."</td>
                    <td>" . $newsletter['type'] . "</td>
                    <td>" . $newsletter['audience_size'] . "</td>
                    <td>" . $newsletter['start_date'] . "</td>
                    <td>" . $newsletter['status']. "</td>";
         ?>
          <td> <span class='togg actions account-info-btn' data-id="<?php echo $newsletter['id'];?>"  id='togglebutton'>...</span>

                 <div class="actions-list hidden">
                     <span class="pseudo"></span>
                     <ul>
                        <?php
//                                 if($_SESSION['email'] == "r.amin@freezoner.net" || $account['added_by'] == $_SESSION['userid'] || $account['assign_to'] == $user['hr_id'])
//                                 {
                        ?>
                        <li data-class='Edit'><a class='btn btn-link li_edit' data-toggle='modal' data-target='#editNewsletter<?php echo $newsletter['id'];?>'>Edit</a></li>
                        <?php
                        //}
                      if($_SESSION['email'] == "r.amin@freezoner.net" || $_SESSION['email'] == "relghoul@freezoner.net")
                      {
                    ?>
                        <li data-class='Delete'><button class='btn btn-link newsletter_buttons li_delete delete' data-toggle='modal' data-target='#newsletterDelete' data-id="<?php echo $newsletter['id'];?>">Delete</button></li>
                    <?php
                     }
                    ?>
                        </ul>
                  </div>
          </td>
                        <div id="editNewsletter<?php echo $newsletter['id'];?>" class="modal fade" role="dialog">
                          <div class="modal-dialog modal-md">

                              <!-- Modal content-->
                              <div class="modal-content">
                                  <div class="modal-header modal-h-back">

                                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                                          <h4 class="modal-title">Edit Newsletter</h4>

                                  </div>

                                  <div class="modal-body">
                                      <form method="post" class="editNewsletter">
                                          <div class="row mt">
                                              <div class="col-md-6 col-sm-6">
                                                  <label class="pure-material-textfield-outlined">
                                                      <input placeholder=" " type="text" name="name" value="<?php echo $newsletter['name'];?>">
                                                      <span>Name</span>
                                                  </label>
                                              </div>

                                              <div class="col-md-6 col-sm-6">
                                                  <div class="select">
                                                      <select class="select-text" required name="type">
                                                        <option value="">--Select--</option>
                                                        <option value="Facebook" <?php if($campaign['method'] == "Facebook"){ echo 'selected';} ?>>Facebook</option>
                                                        <option value="Instagram" <?php if($campaign['method'] == "Instagram"){ echo 'selected';} ?>>Instagram</option>
                                                        <option value="LinkedIn" <?php if($campaign['method'] == "LinkedIn"){ echo 'selected';} ?>>LinkedIn</option>
                                                        <option value="Mail" <?php if($campaign['method'] == "Mail"){ echo 'selected';} ?>>Mail</option>
                                                        <option value="google-ads" <?php if($campaign['method'] == "google-ads"){ echo 'selected';} ?>>Google Ads</option>
                                                      </select>
                                                      <label class="select-label">Type</label>
                                                  </div>
                                              </div>
                                          </div>


                                          <div class="row mt">

                                                  <div class="col-md-6 col-sm-6">
                                                      <label class="pure-material-textfield-outlined">
                                                          <input placeholder=" " type="text" name="audience_size" value="<?php echo $newsletter['audience_size'];?>">
                                                          <span>Audience Size</span>
                                                      </label>
                                                  </div>


                                                      <div class="col-md-6 col-sm-6">
                                                          <label class="pure-material-textfield-outlined">
                                                              <input placeholder=" " type="date" name="start_date" value="<?php echo $newsletter['start_date'];?>">
                                                              <span>Start date</span>
                                                          </label>
                                                      </div>

                                          </div>

                                          <div class="row mt">

                                                  <div class="col-md-6 col-sm-6">
                                                      <label class="pure-material-textfield-outlined">
                                                          <input placeholder=" " type="text" name="status" value="<?php echo $newsletter['status'];?>">
                                                          <span>Status</span>
                                                      </label>
                                                  </div>

                                          </div>




                                  <div class="row">
                                      <div class="col-xs-12 text-right"><input type="hidden" name="edit-newsletter" value="edit-newsletter"><input type="hidden" name="id" value="<?php echo $newsletter['id'];?>">
                                          <button type="submit" class="btn btn-defaut btn-ta-1" >Submit</button>
                                      </div>
                                  </div>



                                      </form>
                                  </div>

                              </div>
                          </div>
                      </div>



        <?php echo "</tr>";
         }
      }else{
            echo "<tr><td class='text-center' colspan='100%'><b>No Newsletter Found with ".$search."</b></td></tr>";
      }
}


/*********************************** Search newsletter ************************************/
if(isset($_POST['campaign_search']))
{

  $search = $_POST['campaign_search'];

  $table = $_POST['table'];

  $campaigns = searchElements($table,"*","WHERE (name LIKE '%$search%' OR method LIKE '%$search%' OR start_date LIKE '%$search%' OR end_date LIKE '%$search%' OR offer LIKE '%$search%') ORDER BY id DESC");

    if(!empty($campaigns))
    {
        $i = 0;
          foreach($campaigns as $campaign)
         {  $i++;

                    echo "<tr id='".$campaign['id']."'>
                    <td>" . $i . "</td>
                    <td>" . $campaign['name'] ."</td>
                    <td>" . $campaign['method'] . "</td>
                    <td>" . $campaign['start_date'] . "</td>
                    <td>" . $campaign['end_date'] . "</td>
                    <td>" . $campaign['status'] . "</td>";
         ?>
          <td> <span class='togg actions account-info-btn' data-id="<?php echo $campaign['id'];?>"  id='togglebutton'>...</span>

                 <div class="actions-list hidden">
                     <span class="pseudo"></span>
                     <ul>
                        <?php
 //                                 if($_SESSION['email'] == "r.amin@freezoner.net" || $account['added_by'] == $_SESSION['userid'] || $account['assign_to'] == $user['hr_id'])
 //                                 {
                        ?>
                        <li data-class='Edit'><a class='btn btn-link li_edit' data-toggle='modal' data-target='#editCampain<?php echo $campaign['id'];?>'>Edit</a></li>
                        <?php
                        //}
                      if($_SESSION['email'] == "r.amin@freezoner.net" || $_SESSION['email'] == "relghoul@freezoner.net")
                      {
                    ?>
                        <li data-class='Delete'><button class='btn btn-link campaign_buttons li_delete delete' data-toggle='modal' data-target='#campaignDelete' data-id="<?php echo $campaign['id'];?>">Delete</button></li>
                    <?php
                     }
                    ?>
                        </ul>
                  </div>
          </td>
                <div id="editCampain<?php echo $campaign['id'];?>" class="modal fade" role="dialog">
                  <div class="modal-dialog modal-md">

                      <!-- Modal content-->
                      <div class="modal-content">
                          <div class="modal-header modal-h-back">

                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                  <h4 class="modal-title">Edit Campaign</h4>

                          </div>

                          <div class="modal-body">
                              <form method="post" class="editCampaign">
                                  <div class="row mt">
                                      <div class="col-md-6 col-sm-6">
                                          <label class="pure-material-textfield-outlined">
                                              <input placeholder=" " type="text" name="name" value="<?php echo $campaign['name'];?>">
                                              <span>Name</span>
                                          </label>
                                      </div>

                                      <div class="col-md-6 col-sm-6">
                                          <div class="select">
                                              <select class="select-text" required name="method">
                                                  <option value="">--Select--</option>
                                                  <option value="Facebook" <?php if($campaign['method'] == "Facebook"){ echo 'selected';} ?>>Facebook</option>
                                                  <option value="Instagram" <?php if($campaign['method'] == "Instagram"){ echo 'selected';} ?>>Instagram</option>
                                                  <option value="LinkedIn" <?php if($campaign['method'] == "LinkedIn"){ echo 'selected';} ?>>LinkedIn</option>
                                                  <option value="Mail" <?php if($campaign['method'] == "Mail"){ echo 'selected';} ?>>Mail</option>
                                                  <option value="google-ads" <?php if($campaign['method'] == "google-ads"){ echo 'selected';} ?>>Google Ads</option>
                                              </select>
                                              <label class="select-label">Method</label>
                                          </div>
                                      </div>
                                  </div>


                                  <div class="row mt">

                                          <div class="col-md-6 col-sm-6">
                                              <label class="pure-material-textfield-outlined">
                                                  <input placeholder=" " type="date" name="start_date" value="<?php echo $campaign['start_date'];?>">
                                                  <span>Start Date</span>
                                              </label>
                                          </div>


                                          <div class="col-md-6 col-sm-6">
                                              <label class="pure-material-textfield-outlined">
                                                  <input placeholder=" " type="date" name="end_date" value="<?php echo $campaign['end_date'];?>">
                                                  <span>End Date</span>
                                              </label>
                                          </div>

                                  </div>
                                  <div class="row mt">
                                      <div class="col-xs-12">
                                      <label>Offer Details</label>
                                      <textarea class="form-control description" name="offer" rows=""><?php echo $campaign['offer'];?></textarea>
          </div>


          </div>






                          <div class="row">
                              <div class="col-xs-12 text-right"><input type="hidden" name="edit-compaign" value="edit-compaign"><input type="hidden" name="id" value="<?php echo $campaign['id'];?>">
                                  <button class="btn btn-defaut btn-ta-1" >Submit</button>
                              </div>
                          </div>



                              </form>
                          </div>

                      </div>
                  </div>
              </div>

        <?php
        echo "</tr>";
         }
      }else{
            echo "<tr><td class='text-center' colspan='100%'><b>No campaign Found with ".$search."</b></td></tr>";
      }
}

?>
