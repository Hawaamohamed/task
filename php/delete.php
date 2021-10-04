<?php
session_start();
include "../include/connect.php";
include "../include/function.php";

if(isset($_POST['table']) && isset($_POST['id']))
{

    if($_POST['table'] == "deals") //delete deliverables items for this deal
    {
        deleteElementWhere("deliverables_items","WHERE deal_id = {$_POST['id']}");
        deleteElementWhere("renewals","WHERE deal_id = {$_POST['id']}");

    }else if($_POST['table'] == "accounts")
    {
        deleteElementWhere("accounts_items","WHERE account_id = {$_POST['id']}");  //delete deliverables items for this accounts
        deleteElementWhere("renewals","WHERE account_id = {$_POST['id']}");  //delete renewals for this accounts
        updateElement("accounts","primary_account",0,"WHERE primary_account = {$_POST['id']}");  
    }else if($_POST['table'] == "deliverables_items")
    {
        deleteElementWhere("renewals","WHERE item_id = {$_POST['id']}"); //delete renewals for this deal
    }else if($_POST['table'] == "accounts_items")
    {
        deleteElementWhere("renewals","WHERE item_id = {$_POST['id']}"); //delete renewals for this deal
    }

    deleteElement($_POST['table'],$_POST['id']);
    echo "Deleted Successfully";

}
?>
