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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$colname_get_Userid = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_get_Userid = $_SESSION['MM_Username'];
}
mysql_select_db($database_sdmarket, $sdmarket);
$query_get_Userid = sprintf("SELECT userid, department FROM user_profile WHERE email = %s", GetSQLValueString($colname_get_Userid, "text"));
$get_Userid = mysql_query($query_get_Userid, $sdmarket) or die(mysql_error());
$row_get_Userid = mysql_fetch_assoc($get_Userid);
$totalRows_get_Userid = mysql_num_rows($get_Userid);
mysql_select_db($database_sdmarket, $sdmarket);

if ((isset($_POST["action"])) && ($_POST["action"] == "Approve")) {
  if(isset($_POST['checkbox'])){ 
//	echo $_POST['checkbox'];
	foreach($_POST['checkbox'] as $app_id){
		$query_get_Projects = sprintf("INSERT INTO projectrelation (idprojects, userid, role) VALUES ('$app_id', %s, 2)", GetSQLValueString($row_get_Userid['userid'], "text"));
		$get_Projects = mysql_query($query_get_Projects);

  $updateSQL = sprintf("UPDATE projects SET status=%s WHERE idprojects=%s",
                       GetSQLValueString($_POST['statusupdate'], "int"),
                       GetSQLValueString($app_id, "int"));

  mysql_select_db($database_sdmarket, $sdmarket);
  $Result1 = mysql_query($updateSQL, $sdmarket) or die(mysql_error());
	}
  }
} elseif ((isset($_POST["action"])) && ($_POST["action"] == "Delete")) {
	if(isset($_POST['checkbox'])){ 
//	echo $_POST['checkbox'];
	foreach($_POST['checkbox'] as $app_id){
  		$deleteSQL = sprintf("DELETE FROM projects WHERE idprojects=%s",
                       GetSQLValueString($app_id, "int"));

  mysql_select_db($database_sdmarket, $sdmarket);
  $Result1 = mysql_query($deleteSQL, $sdmarket) or die(mysql_error());
	}
	}
}

if ($row_get_Userid['department'] == "Business & Technology") {
$query_get_Projects = "SELECT projects.idprojects, projects.title, projects.summary, projects.photo, projectstatus.image FROM projects JOIN projectstatus ON projects.status = projectstatus.status WHERE projects.status= '1' AND ((projects.BTqty = '0' AND projects.CEqty = '0' AND projects.CPEqty = '0' AND projects.EEqty = '0' AND projects.EnvEqty = '0' AND projects.MEqty = '0' AND projects.EMqty = '0' AND projects.NEqty = '0' AND projects.SYSqty = '0' AND projects.BMEqty = '0' AND projects.ChemEqty = '0' AND projects.ISqty = '0') OR projects.BTqty>'0')";

$get_Projects = mysql_query($query_get_Projects, $sdmarket) or die(mysql_error());
$totalRows_get_Projects = mysql_num_rows($get_Projects);
$row_get_Projects = mysql_fetch_assoc($get_Projects);
}

if ($row_get_Userid['department'] == "Biomedical Engineering") {
$query_get_Projects = "SELECT projects.idprojects, projects.title, projects.summary, projects.photo, projectstatus.image FROM projects JOIN projectstatus ON projects.status = projectstatus.status WHERE projects.status= '1' AND ((projects.BTqty = '0' AND projects.CEqty = '0' AND projects.CPEqty = '0' AND projects.EEqty = '0' AND projects.EnvEqty = '0' AND projects.MEqty = '0' AND projects.EMqty = '0' AND projects.NEqty = '0' AND projects.SYSqty = '0' AND projects.BMEqty = '0' AND projects.ChemEqty = '0' AND projects.ISqty = '0') OR projects.BMEqty>'0')";

$get_Projects = mysql_query($query_get_Projects, $sdmarket) or die(mysql_error());
$totalRows_get_Projects = mysql_num_rows($get_Projects);
$row_get_Projects = mysql_fetch_assoc($get_Projects);
}

elseif ($row_get_Userid['department'] == "Civil Engineering") {
$query_get_Projects = "SELECT projects.idprojects, projects.title, projects.summary, projects.photo, projectstatus.image FROM projects JOIN projectstatus ON projects.status = projectstatus.status WHERE projects.status= '1' AND ((projects.BTqty = '0' AND projects.CEqty = '0' AND projects.CPEqty = '0' AND projects.EEqty = '0' AND projects.EnvEqty = '0' AND projects.MEqty = '0' AND projects.EMqty = '0' AND projects.NEqty = '0' AND projects.SYSqty = '0' AND projects.BMEqty = '0' AND projects.ChemEqty = '0' AND projects.ISqty = '0') OR projects.CEqty>'0')";

$get_Projects = mysql_query($query_get_Projects, $sdmarket) or die(mysql_error());
$totalRows_get_Projects = mysql_num_rows($get_Projects);
$row_get_Projects = mysql_fetch_assoc($get_Projects);
}

elseif ($row_get_Userid['department'] == "Chemical Engineering") {
$query_get_Projects = "SELECT projects.idprojects, projects.title, projects.summary, projects.photo, projectstatus.image FROM projects JOIN projectstatus ON projects.status = projectstatus.status WHERE projects.status= '1' AND ((projects.BTqty = '0' AND projects.CEqty = '0' AND projects.CPEqty = '0' AND projects.EEqty = '0' AND projects.EnvEqty = '0' AND projects.MEqty = '0' AND projects.EMqty = '0' AND projects.NEqty = '0' AND projects.SYSqty = '0' AND projects.BMEqty = '0' AND projects.ChemEqty = '0' AND projects.ISqty = '0') OR projects.ChemEqty>'0')";

$get_Projects = mysql_query($query_get_Projects, $sdmarket) or die(mysql_error());
$totalRows_get_Projects = mysql_num_rows($get_Projects);
$row_get_Projects = mysql_fetch_assoc($get_Projects);
}

elseif ($row_get_Userid['department'] == "Computer Engineering") {
$query_get_Projects = "SELECT projects.idprojects, projects.title, projects.summary, projects.photo, projectstatus.image FROM projects JOIN projectstatus ON projects.status = projectstatus.status WHERE projects.status= '1' AND ((projects.BTqty = '0' AND projects.CEqty = '0' AND projects.CPEqty = '0' AND projects.EEqty = '0' AND projects.EnvEqty = '0' AND projects.MEqty = '0' AND projects.EMqty = '0' AND projects.NEqty = '0' AND projects.SYSqty = '0' AND projects.BMEqty = '0' AND projects.ChemEqty = '0' AND projects.ISqty = '0') OR projects.CPEqty>'0')";

$get_Projects = mysql_query($query_get_Projects, $sdmarket) or die(mysql_error());
$totalRows_get_Projects = mysql_num_rows($get_Projects);
$row_get_Projects = mysql_fetch_assoc($get_Projects);
}

elseif ($row_get_Userid['department'] == "Electrical Engineering") {
$query_get_Projects = "SELECT projects.idprojects, projects.title, projects.summary, projects.photo, projectstatus.image FROM projects JOIN projectstatus ON projects.status = projectstatus.status WHERE projects.status= '1' AND ((projects.BTqty = '0' AND projects.CEqty = '0' AND projects.CPEqty = '0' AND projects.EEqty = '0' AND projects.EnvEqty = '0' AND projects.MEqty = '0' AND projects.EMqty = '0' AND projects.NEqty = '0' AND projects.SYSqty = '0' AND projects.BMEqty = '0' AND projects.ChemEqty = '0' AND projects.ISqty = '0') OR projects.EEqty>'0')";

$get_Projects = mysql_query($query_get_Projects, $sdmarket) or die(mysql_error());
$totalRows_get_Projects = mysql_num_rows($get_Projects);
$row_get_Projects = mysql_fetch_assoc($get_Projects);
}

elseif ($row_get_Userid['department'] == "Engineering Management") {
$query_get_Projects = "SELECT projects.idprojects, projects.title, projects.summary, projects.photo, projectstatus.image FROM projects JOIN projectstatus ON projects.status = projectstatus.status WHERE projects.status= '1' AND ((projects.BTqty = '0' AND projects.CEqty = '0' AND projects.CPEqty = '0' AND projects.EEqty = '0' AND projects.EnvEqty = '0' AND projects.MEqty = '0' AND projects.EMqty = '0' AND projects.NEqty = '0' AND projects.SYSqty = '0' AND projects.BMEqty = '0' AND projects.ChemEqty = '0' AND projects.ISqty = '0') OR projects.EMqty>'0')";

$get_Projects = mysql_query($query_get_Projects, $sdmarket) or die(mysql_error());
$totalRows_get_Projects = mysql_num_rows($get_Projects);
$row_get_Projects = mysql_fetch_assoc($get_Projects);
}

elseif ($row_get_Userid['department'] == "Environmental Engineering") {
$query_get_Projects = "SELECT projects.idprojects, projects.title, projects.summary, projects.photo, projectstatus.image FROM projects JOIN projectstatus ON projects.status = projectstatus.status WHERE projects.status= '1' AND ((projects.BTqty = '0' AND projects.CEqty = '0' AND projects.CPEqty = '0' AND projects.EEqty = '0' AND projects.EnvEqty = '0' AND projects.MEqty = '0' AND projects.EMqty = '0' AND projects.NEqty = '0' AND projects.SYSqty = '0' AND projects.BMEqty = '0' AND projects.ChemEqty = '0' AND projects.ISqty = '0') OR projects.EnvEqty>'0')";

$get_Projects = mysql_query($query_get_Projects, $sdmarket) or die(mysql_error());
$totalRows_get_Projects = mysql_num_rows($get_Projects);
$row_get_Projects = mysql_fetch_assoc($get_Projects);
}

elseif ($row_get_Userid['department'] == "Information Systems") {
$query_get_Projects = "SELECT projects.idprojects, projects.title, projects.summary, projects.photo, projectstatus.image FROM projects JOIN projectstatus ON projects.status = projectstatus.status WHERE projects.status= '1' AND ((projects.BTqty = '0' AND projects.CEqty = '0' AND projects.CPEqty = '0' AND projects.EEqty = '0' AND projects.EnvEqty = '0' AND projects.MEqty = '0' AND projects.EMqty = '0' AND projects.NEqty = '0' AND projects.SYSqty = '0' AND projects.BMEqty = '0' AND projects.ChemEqty = '0' AND projects.ISqty = '0') OR projects.ISqty>'0')";

$get_Projects = mysql_query($query_get_Projects, $sdmarket) or die(mysql_error());
$totalRows_get_Projects = mysql_num_rows($get_Projects);
$row_get_Projects = mysql_fetch_assoc($get_Projects);
}

elseif ($row_get_Userid['department'] == "Mechanical Engineering") {
$query_get_Projects = "SELECT projects.idprojects, projects.title, projects.summary, projects.photo, projectstatus.image FROM projects JOIN projectstatus ON projects.status = projectstatus.status WHERE projects.status= '1' AND ((projects.BTqty = '0' AND projects.CEqty = '0' AND projects.CPEqty = '0' AND projects.EEqty = '0' AND projects.EnvEqty = '0' AND projects.MEqty = '0' AND projects.EMqty = '0' AND projects.NEqty = '0' AND projects.SYSqty = '0' AND projects.BMEqty = '0' AND projects.ChemEqty = '0' AND projects.ISqty = '0') OR projects.MEqty>'0')";

$get_Projects = mysql_query($query_get_Projects, $sdmarket) or die(mysql_error());
$totalRows_get_Projects = mysql_num_rows($get_Projects);
$row_get_Projects = mysql_fetch_assoc($get_Projects);
}

elseif ($row_get_Userid['department'] == "Naval Engineering") {
$query_get_Projects = "SELECT projects.idprojects, projects.title, projects.summary, projects.photo, projectstatus.image FROM projects JOIN projectstatus ON projects.status = projectstatus.status WHERE projects.status= '1' AND ((projects.BTqty = '0' AND projects.CEqty = '0' AND projects.CPEqty = '0' AND projects.EEqty = '0' AND projects.EnvEqty = '0' AND projects.MEqty = '0' AND projects.EMqty = '0' AND projects.NEqty = '0' AND projects.SYSqty = '0' AND projects.BMEqty = '0' AND projects.ChemEqty = '0' AND projects.ISqty = '0') OR projects.NEqty>'0')";

$get_Projects = mysql_query($query_get_Projects, $sdmarket) or die(mysql_error());
$totalRows_get_Projects = mysql_num_rows($get_Projects);
$row_get_Projects = mysql_fetch_assoc($get_Projects);
}

elseif ($row_get_Userid['department'] == "Systems Engineering") {
$query_get_Projects = "SELECT projects.idprojects, projects.title, projects.summary, projects.photo, projectstatus.image FROM projects JOIN projectstatus ON projects.status = projectstatus.status WHERE projects.status= '1' AND ((projects.BTqty = '0' AND projects.CEqty = '0' AND projects.CPEqty = '0' AND projects.EEqty = '0' AND projects.EnvEqty = '0' AND projects.MEqty = '0' AND projects.EMqty = '0' AND projects.NEqty = '0' AND projects.SYSqty = '0' AND projects.BMEqty = '0' AND projects.ChemEqty = '0' AND projects.ISqty = '0') OR projects.SYSqty>'0')";

$get_Projects = mysql_query($query_get_Projects, $sdmarket) or die(mysql_error());
$totalRows_get_Projects = mysql_num_rows($get_Projects);
$row_get_Projects = mysql_fetch_assoc($get_Projects);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript">
<!--
function SetAllCheckBoxes(form_project, FieldName, CheckValue)
{
	if(!document.forms[form_project])
		return;
	var objCheckBoxes = document.forms[form_project].elements[FieldName];
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
function checkChecks(form_project)
{
	if(!document.forms[form_project])
		return false;
	var objCheckBoxes = document.forms[form_project].elements['checkbox'];
	if(!objCheckBoxes)
		return false;
	var countCheckBoxes = objCheckBoxes.length;
	// set the check value for all check boxes
	for(var i = 0; i < countCheckBoxes; i++) {
		if (objCheckBoxes[i].checked) {
			return confirm('Are you sure you want to Approve/Delete the selected project(s)?');
		}
	}
	alert("Please select at least one project");
	return false;
}
// -->
</script>
<title>Senior Design Marketplace | Manage Projects</title>
</head>
<style type="text/css">
body,td,th {
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 14px;
}
h1 {
	font-family: Verdana, Geneva, sans-serif;
	color: #900;
}
</style>

<body>
<h1>Manage Projects (<?php echo $row_get_Userid['department']; ?>)</h1>
<hr size="1"/>
<input type="button" onclick="SetAllCheckBoxes('form_project', 'checkbox', true);" value="Select All" />
<input type="button" onclick="SetAllCheckBoxes('form_project', 'checkbox', false);" value="Deselect All" />
<form action="<?php echo $editFormAction; ?>" id="form_project" name="form_project" method="POST" onsubmit="return checkChecks('form_project');">

  <br />
<?php  //first check to see if there is anything in the recordset....
if (mysql_num_rows($get_Projects)==0) {echo 'There are currently no projects pending approval.';}

//else, begin the HTML
else { ?>
    <table width="100%" border="1" cellpadding="5">
<?php do { 
 // 	$row_get_Projects = mysql_fetch_assoc($get_Projects);
	if ($row_get_Projects === false){
		break;
	}
	?>
      <tr>
        <td width="5%"><input type="checkbox" name="checkbox[]" id="checkbox" value="<?php echo $row_get_Projects['idprojects']; $app_id = $row_get_Projects['idprojects']?>" />
        <label for="checkbox">
          <input type="hidden" name="statusupdate" id="hiddenField" value="2"/>
        </label></td>
        <td width="100"><img src="images/<?php echo $row_get_Projects['image']; ?>" width="100" height="40" /></td>
        <td width="50"><a href="viewproject.php?idprojects=<?php echo $row_get_Projects['idprojects'];?>"><img src="images/projectupload/<?php echo $row_get_Projects['photo']; ?>" width="150" /></a></td>
        <td width="28%"><?php echo $row_get_Projects['title']; ?></td>
        <td width="22%"><?php echo $row_get_Projects['summary']; ?></td>
      </tr>
  <?php } while ($row_get_Projects = mysql_fetch_assoc($get_Projects));?>
    </table>
    <?php } ?>
    <p><br />


  <input type="button" onclick="SetAllCheckBoxes('form_project', 'checkbox', true);" value="Select All" />
  <input type="button" onclick="SetAllCheckBoxes('form_project', 'checkbox', false);" value="Deselect All" />
    </p>
    <p>
      <input type="submit" name="action" id="approve" value="Approve"/>
      <input type="submit" name="action" id="delete" value="Delete" />
    </p>
    <input type="hidden" name="MM_update" value="form_project" />
</form>
<p>
</p>
<p>
</p>
</body>
</html>
<?php
mysql_free_result($get_Userid);

mysql_free_result($get_Projects);
?>
