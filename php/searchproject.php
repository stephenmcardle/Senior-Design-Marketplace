<?php require_once('Connections/sdmarket.php'); ?>
<?php require_once('Connections/sdmarket.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

mysql_select_db($database_sdmarket, $sdmarket);
$query_getStatus = "SELECT status, `description` FROM projectstatus";
$getStatus = mysql_query($query_getStatus, $sdmarket) or die(mysql_error());
$row_getStatus = mysql_fetch_assoc($getStatus);
$totalRows_getStatus = mysql_num_rows($getStatus);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script type="text/javascript">
<!--
function SetAllCheckBoxes(form_search, FieldName, CheckValue)
{
	if(!document.forms[form_search])
		return;
	var objCheckBoxes = document.forms[form_search].elements[FieldName];
	if(!objCheckBoxes)
		return;
	var countCheckBoxes = objCheckBoxes.length;
	if(!countCheckBoxes)
		objCheckBoxes.checked = CheckValue;
	else
		// set the check value for all check boxes
		for(var i = 0; i < countCheckBoxes; i++)
			objCheckBoxes[i].checked = CheckValue;
}
// -->
</script>

<title>Senior Design Marketplace | Search Projects</title>
<style type="text/css">
body,td,th {
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 14px;
}
h1 {
	font-family: Tahoma, Geneva, sans-serif;
	color: #900;
}
</style>
</head>

<body>
<h1>Search for a Project</h1>
<p>Enter your desired search criteria below...</p>
<form action="searchresults.php" method="post" name="form_search" target="_self" id="form_search">
  <table width="400">
    <tr>
      <td width="100" align="center"><label for="title2">Project Title:</label></td>
      <td width="288"><input type="text" name="title" id="title" /></td>
    </tr>
    <tr>
      <td align="center"><label for="status2">Project Status:</label></td>
      <td><select name="status" id="status">
        <option value=""></option>
        <?php
do {  
?>
        <option value="<?php echo $row_getStatus['status']?>"><?php echo $row_getStatus['description']?></option>
        <?php
} while ($row_getStatus = mysql_fetch_assoc($getStatus));
  $rows = mysql_num_rows($getStatus);
  if($rows > 0) {
      mysql_data_seek($getStatus, 0);
	  $row_getStatus = mysql_fetch_assoc($getStatus);
  }
?>
      </select></td>
    </tr>
  </table>
  <p><strong>Select Majors:</strong></p>
  <p>
    <input name="BMEqty" type="checkbox" id="BMEqty" value="100" checked="checked" />
    <label for="BMEqty">Biomedical Engineering</label>
    <br />
    <input name="BTqty" type="checkbox" id="BTqty" value="100" checked="checked" />
    <label for="BTqty">Business & Technology</label>
    <br />
  <input name="CEqty" type="checkbox" id="CEqty" value="100" checked="checked" />
    <label for="CEqty">Civil Engineering</label>
    <br />
  <input name="ChemEqty" type="checkbox" id="ChemEqty" value="100" checked="checked" />
    <label for="ChemEqty">Chemical Engineering</label>
    <br />
  <input name="CPEqty" type="checkbox" id="CPEqty" value="100" checked="checked" />
    <label for="CPEqty">Computer Engineering</label>
    <br />
  <input name="EEqty" type="checkbox" id="EEqty" value="100" checked="checked" />
    <label for="EEqty">Electrical Engineering</label>
    <br />
  <input name="EMqty" type="checkbox" id="EMqty" value="100" checked="checked" />
    <label for="EMqty">Engineering Management</label>
    <br />
  <input name="EnvEqty" type="checkbox" id="EnvEqty" value="100" checked="checked" />
    <label for="EnvEqty">Environmental Engineering</label>
    <br />
  <input name="ISqty" type="checkbox" id="ISqty" value="100" checked="checked" />
    <label for="ISqty">Information Systems</label>
    <br />
  <input name="MEqty" type="checkbox" id="MEqty" value="100" checked="checked" />
    <label for="MEqty">Mechanical Engineering</label>
    <br />
  <input name="NEqty" type="checkbox" id="NEqty" value="100" checked="checked" />
    <label for="NEqty">Naval Engineering</label>
    <br />
  <input name="SYSqty" type="checkbox" id="SYSqty" value="100" checked="checked" />
    <label for="SYSqty">Systems Engineering</label>
  </p>
    <input type="button" onclick="SetAllCheckBoxes('form_search', 'BTqty', true);
    							  SetAllCheckBoxes('form_search', 'BMEqty', true);
    							  SetAllCheckBoxes('form_search', 'CEqty', true);
                                  SetAllCheckBoxes('form_search', 'ChemEqty', true);
                                  SetAllCheckBoxes('form_search', 'CPEqty', true);
                                  SetAllCheckBoxes('form_search', 'EEqty', true);
                                  SetAllCheckBoxes('form_search', 'EMqty', true);
                                  SetAllCheckBoxes('form_search', 'EnvEqty', true);
                                  SetAllCheckBoxes('form_search', 'ISqty', true);
                                  SetAllCheckBoxes('form_search', 'MEqty', true);
                                  SetAllCheckBoxes('form_search', 'NEqty', true);
                                  SetAllCheckBoxes('form_search', 'SYSqty', true);" value="Select All">
     <input type="button" onclick="SetAllCheckBoxes('form_search', 'BTqty', false);
     							  SetAllCheckBoxes('form_search', 'BMEqty', false);
    							  SetAllCheckBoxes('form_search', 'CEqty', false);
                                  SetAllCheckBoxes('form_search', 'ChemEqty', false);
                                  SetAllCheckBoxes('form_search', 'CPEqty', false);
                                  SetAllCheckBoxes('form_search', 'EEqty', false);
                                  SetAllCheckBoxes('form_search', 'EMqty', false);
                                  SetAllCheckBoxes('form_search', 'EnvEqty', false);
                                  SetAllCheckBoxes('form_search', 'ISqty', false);
                                  SetAllCheckBoxes('form_search', 'MEqty', false);
                                  SetAllCheckBoxes('form_search', 'NEqty', false);
                                  SetAllCheckBoxes('form_search', 'SYSqty', false);" value="Deselect All"> 
  <p>
    <input type="submit" name="submit" id="submit" value="Search" />
    <br />
  </p>

</form>
</body>
</html>
<?php
mysql_free_result($getStatus);
?>
