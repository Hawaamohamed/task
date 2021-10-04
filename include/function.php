<?php
include "connect.php";

 

function getTitle()
{
	global $pageTitle;

	if(isset($pageTitle))
	{
		echo $pageTitle;
	}
	else{
		echo "Default";
	}

}

// Function to check item in database
function checkItem($select,$from,$value)
{
	global $con;
	$statementch=$con->prepare("SELECT $select FROM $from WHERE $select=?");
	$statementch->execute(array($value));
	$count=$statementch->rowCount();
	return $count;
}
function getCount($table)
{
	global $con;
	$getCount=$con->prepare("SELECT * FROM $table");
	$getCount->execute();
	$count=$getCount->rowCount();
	return $count;
}
function getCounts($table,$where)
{
	global $con;
	$getCount=$con->prepare("SELECT * FROM $table $where");
	$getCount->execute();
	$count=$getCount->rowCount();
	return $count;
}


function getElement($table,$where = NULL)
{
  global $con;
  $stmtget1 = $con->prepare("SELECT * FROM $table $where");
  $stmtget1->execute();
  $elementt = $stmtget1->fetch();
  return $elementt;
}

function getElements($table,$where = NULL)
{
  global $con;
  $stmtget2 = $con->prepare("SELECT * FROM $table $where");
  $stmtget2->execute();
  $elements = $stmtget2->fetchAll();
  return $elements;
}
function getFields($table,$field,$where = NULL)
{
  global $con;
  $stmtf = $con->prepare("SELECT $field FROM $table $where");
  $stmtf->execute();
  $fields = $stmtf->fetchAll();
  return $fields;
}
function getField($table,$field,$where = NULL)
{
  global $con;
  $stmtf2 = $con->prepare("SELECT $field FROM $table $where");
  $stmtf2->execute();
  $field = $stmtf2->fetch();
  return $field;
}
/************Search function************/
function searchElements($table , $select , $where = NULL)
{
  global $con;
  $stmtsearch = $con->prepare("SELECT $select FROM $table $where");
  $stmtsearch->execute();
  $search = $stmtsearch->fetchAll();
  return $search;

}

function insertElement($table,$element,$value,$id)
{
    global $con;
    $stmtin = $con->prepare("UPDATE $table SET $element = ? WHERE id = ?");
    $stmtin->execute(array($value,$id));
}
function updateElement($table,$element,$value,$where)
{
    global $con;
    $stmtin = $con->prepare("UPDATE $table SET $element = ? $where");
    $stmtin->execute(array($value));
}

// Function get distinct values
function distinctItem($select,$from)
{
	global $con;
	$statementch=$con->prepare("SELECT $select FROM $from");
	$statementch->execute();
	$count=$statementch->rowCount();
	return $count;
}

//delete
function deleteElement($table,$id)
{
    global $con;
    $stmtdel = $con->prepare("DELETE FROM $table WHERE id = ?");
    $stmtdel->execute(array($id));
}
//delete
function deleteElementWhere($table,$where)
{
    global $con;
    $stmtdel2 = $con->prepare("DELETE FROM $table $where");
    $stmtdel2->execute();
}
//get largest array, this for deals
function max_count($d_array) {
    $max = 0;
    foreach($d_array as $child) {
        if(count($child) > $max) {
            $max = count($child);
        }
    }
    return $max;
}


function getReminingTime($date){
    $date = strtotime($date);
    $now = time();
    $remaining_time = $date - $now;
    $secondsInAMinute = 60;
    $secondsInAnHour  = 60 * $secondsInAMinute;
    $secondsInADay    = 24 * $secondsInAnHour;
    // extract days
    $days = floor($remaining_time / $secondsInADay);

    // extract hours
    $hourSeconds = $remaining_time % $secondsInADay;
    $hours = floor($hourSeconds / $secondsInAnHour);
    // extract minutes
    $minuteSeconds = $hourSeconds % $secondsInAnHour;
    $minutes = floor($minuteSeconds / $secondsInAMinute);

    // extract the remaining seconds
    $remainingSeconds = $minuteSeconds % $secondsInAMinute;
    $seconds = ceil($remainingSeconds);

    $remaining_time = array();
    $remaining_time['days'] = $days;
    $remaining_time['hours'] = $hours;
    $remaining_time['minutes'] = $minutes;

    return $remaining_time;
}

function getReminingHours($date){
    $date = strtotime($date);
    $now = time();
    $remaining_time =  $now - $date;
    $secondsInAMinute = 60;
    $secondsInAnHour  = 60 * $secondsInAMinute;
    $secondsInADay    = 24 * $secondsInAnHour;
    // extract days
    $days = floor($remaining_time / $secondsInADay);

    // extract hours
    $hourSeconds = $remaining_time % $secondsInADay;
    $hours = floor($hourSeconds / $secondsInAnHour);

    $hours = $days * 24 + $hours;
    return $hours;
}

?>
