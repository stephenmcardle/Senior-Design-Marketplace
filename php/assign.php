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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_assign")) {
  $insertSQL = sprintf("INSERT INTO projectrelation (idprojects, userid, `role`) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['idprojects_assign'], "int"),
                       GetSQLValueString($_POST['userid_assign'], "int"),
                       GetSQLValueString($_POST['role_assign'], "int"));

  mysql_select_db($database_sdmarket, $sdmarket);
  $Result1 = mysql_query($insertSQL, $sdmarket) or die(mysql_error());

  $insertGoTo = "success_assign.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo)); 
  
}

mysql_select_db($database_sdmarket, $sdmarket);
$query_getStudent = sprintf("SELECT user_profile.userid, user_profile.`role`, user_profile.firstname, user_profile.lastname, user_profile.middle, user_profile.email, user_profile.major, user_profile.skill1, user_profile.skill2, user_profile.skill3, user_profile.skill4, user_profile.skill5, user_profile.image FROM user_profile WHERE user_profile.userid = %s", GetSQLValueString($_GET['userid'], "text"));
$getStudent = mysql_query($query_getStudent, $sdmarket) or die(mysql_error());
$row_getStudent = mysql_fetch_assoc($getStudent);
$totalRows_getStudent = mysql_num_rows($getStudent);

if ($row_getStudent['major'] == "Business & Technology") {
$query_getProjects = "SELECT projects.idprojects, projects.title, projects.summary, projects.photo, projects.BMEqty, projects.BTqty, projects.CEqty, projects.ChemEqty, projects.CPEqty, projects.EEqty, projects.EMqty, projects.EnvEqty, projects.ISqty, projects.MEqty, projects.NEqty, projects.SYSqty, projects.minsize, projects.maxsize, projectstatus.image FROM projects JOIN projectstatus ON projects.status = projectstatus.status WHERE (projects.status= '2' AND projects.sizeunknown LIKE '%x%') OR (projects.status='2' AND projects.BTqty>'0')";

$getProjects = mysql_query($query_getProjects, $sdmarket) or die(mysql_error());
$totalRows_getProjects = mysql_num_rows($getProjects);
$row_getProjects = mysql_fetch_assoc($getProjects);
}

if ($row_getStudent['major'] == "Biomedical Engineering") {
$query_getProjects = "SELECT projects.idprojects, projects.title, projects.summary, projects.photo, projects.BMEqty, projects.BTqty, projects.CEqty, projects.ChemEqty, projects.CPEqty, projects.EEqty, projects.EMqty, projects.EnvEqty, projects.ISqty, projects.MEqty, projects.NEqty, projects.SYSqty, projects.minsize, projects.maxsize, projectstatus.image FROM projects JOIN projectstatus ON projects.status = projectstatus.status WHERE (projects.status= '2' AND projects.sizeunknown LIKE '%x%') OR (projects.status='2' AND projects.BMEqty>'0')";

$getProjects = mysql_query($query_getProjects, $sdmarket) or die(mysql_error());
$totalRows_getProjects = mysql_num_rows($getProjects);
$row_getProjects = mysql_fetch_assoc($getProjects);
}

elseif ($row_getStudent['major'] == "Civil Engineering") {
$query_getProjects = "SELECT projects.idprojects, projects.title, projects.summary, projects.photo, projects.BMEqty, projects.BTqty, projects.CEqty, projects.ChemEqty, projects.CPEqty, projects.EEqty, projects.EMqty, projects.EnvEqty, projects.ISqty, projects.MEqty, projects.NEqty, projects.SYSqty, projects.minsize, projects.maxsize, projectstatus.image FROM projects JOIN projectstatus ON projects.status = projectstatus.status WHERE (projects.status = '2' AND projects.sizeunknown LIKE '%x%') OR (projects.status = '2' AND projects.CEqty>'0')";

$getProjects = mysql_query($query_getProjects, $sdmarket) or die(mysql_error());
$totalRows_getProjects = mysql_num_rows($getProjects);
$row_getProjects = mysql_fetch_assoc($getProjects);
}

elseif ($row_getStudent['major'] == "Chemical Engineering") {
$query_getProjects = "SELECT projects.idprojects, projects.title, projects.summary, projects.photo, projects.BMEqty, projects.BTqty, projects.CEqty, projects.ChemEqty, projects.CPEqty, projects.EEqty, projects.EMqty, projects.EnvEqty, projects.ISqty, projects.MEqty, projects.NEqty, projects.SYSqty, projects.minsize, projects.maxsize, projectstatus.image FROM projects JOIN projectstatus ON projects.status = projectstatus.status WHERE (projects.status = '2' AND projects.sizeunknown LIKE '%x%') OR (projects.status = '2' AND projects.ChemEqty>'0')";

$getProjects = mysql_query($query_getProjects, $sdmarket) or die(mysql_error());
$totalRows_getProjects = mysql_num_rows($getProjects);
$row_getProjects = mysql_fetch_assoc($getProjects);
}

elseif ($row_getStudent['major'] == "Computer Engineering") {
$query_getProjects = "SELECT projects.idprojects, projects.title, projects.summary, projects.photo, projects.BMEqty, projects.BTqty, projects.CEqty, projects.ChemEqty, projects.CPEqty, projects.EEqty, projects.EMqty, projects.EnvEqty, projects.ISqty, projects.MEqty, projects.NEqty, projects.SYSqty, projects.minsize, projects.maxsize, projectstatus.image FROM projects JOIN projectstatus ON projects.status = projectstatus.status WHERE (projects.status = '2' AND projects.sizeunknown LIKE '%x%') OR (projects.status = '2' AND projects.CPEqty>'0')";

$getProjects = mysql_query($query_getProjects, $sdmarket) or die(mysql_error());
$totalRows_getProjects = mysql_num_rows($getProjects);
$row_getProjects = mysql_fetch_assoc($getProjects);
}

elseif ($row_getStudent['major'] == "Electrical Engineering") {
$query_getProjects = "SELECT projects.idprojects, projects.title, projects.summary, projects.photo, projects.BMEqty, projects.BTqty, projects.CEqty, projects.ChemEqty, projects.CPEqty, projects.EEqty, projects.EMqty, projects.EnvEqty, projects.ISqty, projects.MEqty, projects.NEqty, projects.SYSqty, projects.minsize, projects.maxsize, projectstatus.image FROM projects JOIN projectstatus ON projects.status = projectstatus.status WHERE (projects.status = '2' AND projects.sizeunknown LIKE '%x%') OR (projects.status = '2' AND projects.EEqty>'0')";

$getProjects = mysql_query($query_getProjects, $sdmarket) or die(mysql_error());
$totalRows_getProjects = mysql_num_rows($getProjects);
$row_getProjects = mysql_fetch_assoc($getProjects);
}

elseif ($row_getStudent['major'] == "Engineering Management") {
$query_getProjects = "SELECT projects.idprojects, projects.title, projects.summary, projects.photo, projects.BMEqty, projects.BTqty, projects.CEqty, projects.ChemEqty, projects.CPEqty, projects.EEqty, projects.EMqty, projects.EnvEqty, projects.ISqty, projects.MEqty, projects.NEqty, projects.SYSqty, projects.minsize, projects.maxsize, projectstatus.image FROM projects JOIN projectstatus ON projects.status = projectstatus.status WHERE (projects.status = '2' AND projects.sizeunknown LIKE '%x%') OR (projects.status = '2' AND projects.EMqty>'0')";

$getProjects = mysql_query($query_getProjects, $sdmarket) or die(mysql_error());
$totalRows_getProjects = mysql_num_rows($getProjects);
$row_getProjects = mysql_fetch_assoc($getProjects);
}

elseif ($row_getStudent['major'] == "Environmental Engineering") {
$query_getProjects = "SELECT projects.idprojects, projects.title, projects.summary, projects.photo, projects.BMEqty, projects.BTqty, projects.CEqty, projects.ChemEqty, projects.CPEqty, projects.EEqty, projects.EMqty, projects.EnvEqty, projects.ISqty, projects.MEqty, projects.NEqty, projects.SYSqty, projects.minsize, projects.maxsize, projectstatus.image FROM projects JOIN projectstatus ON projects.status = projectstatus.status WHERE (projects.status = '2' AND projects.sizeunknown LIKE '%x%') OR (projects.status = '2' AND projects.EnvEqty>'0')";

$getProjects = mysql_query($query_getProjects, $sdmarket) or die(mysql_error());
$totalRows_getProjects = mysql_num_rows($getProjects);
$row_getProjects = mysql_fetch_assoc($getProjects);
}


elseif ($row_getStudent['major'] == "Information Systems") {
$query_getProjects = "SELECT projects.idprojects, projects.title, projects.summary, projects.photo, projects.BMEqty, projects.BTqty, projects.CEqty, projects.ChemEqty, projects.CPEqty, projects.EEqty, projects.EMqty, projects.EnvEqty, projects.ISqty, projects.MEqty, projects.NEqty, projects.SYSqty, projects.minsize, projects.maxsize, projectstatus.image FROM projects JOIN projectstatus ON projects.status = projectstatus.status WHERE (projects.status = '2' AND projects.sizeunknown LIKE '%x%') OR (projects.status = '2' AND projects.ISqty>'0')";

$getProjects = mysql_query($query_getProjects, $sdmarket) or die(mysql_error());
$totalRows_getProjects = mysql_num_rows($getProjects);
$row_getProjects = mysql_fetch_assoc($getProjects);
}

elseif ($row_getStudent['major'] == "Mechanical Engineering") {
$query_getProjects = "SELECT projects.idprojects, projects.title, projects.summary, projects.photo, projects.BMEqty, projects.BTqty, projects.CEqty, projects.ChemEqty, projects.CPEqty, projects.EEqty, projects.EMqty, projects.EnvEqty, projects.ISqty, projects.MEqty, projects.NEqty, projects.SYSqty, projects.minsize, projects.maxsize, projectstatus.image FROM projects JOIN projectstatus ON projects.status = projectstatus.status WHERE (projects.status = '2' AND projects.sizeunknown LIKE '%x%') OR (projects.status = '2' AND projects.MEqty>'0')";

$getProjects = mysql_query($query_getProjects, $sdmarket) or die(mysql_error());
$totalRows_getProjects = mysql_num_rows($getProjects);
$row_getProjects = mysql_fetch_assoc($getProjects);
}

elseif ($row_getStudent['major'] == "Naval Engineering") {
$query_getProjects = "SELECT projects.idprojects, projects.title, projects.summary, projects.photo, projects.BMEqty, projects.BTqty, projects.CEqty, projects.ChemEqty, projects.CPEqty, projects.EEqty, projects.EMqty, projects.EnvEqty, projects.ISqty, projects.MEqty, projects.NEqty, projects.SYSqty, projects.minsize, projects.maxsize, projectstatus.image FROM projects JOIN projectstatus ON projects.status = projectstatus.status WHERE (projects.status = '2' AND projects.sizeunknown LIKE '%x%') OR (projects.status = '2' AND projects.NEqty>'0')";

$getProjects = mysql_query($query_getProjects, $sdmarket) or die(mysql_error());
$totalRows_getProjects = mysql_num_rows($getProjects);
$row_getProjects = mysql_fetch_assoc($getProjects);
}

elseif ($row_getStudent['major'] == "Systems Engineering") {
$query_getProjects = "SELECT projects.idprojects, projects.title, projects.summary, projects.photo, projects.BMEqty, projects.BTqty, projects.CEqty, projects.ChemEqty, projects.CPEqty, projects.EEqty, projects.EMqty, projects.EnvEqty, projects.ISqty, projects.MEqty, projects.NEqty, projects.SYSqty, projects.minsize, projects.maxsize, projectstatus.image FROM projects JOIN projectstatus ON projects.status = projectstatus.status WHERE (projects.status = '2' AND projects.sizeunknown LIKE '%x%') OR (projects.status = '2' AND projects.SYSqty>'0')";

$getProjects = mysql_query($query_getProjects, $sdmarket) or die(mysql_error());
$totalRows_getProjects = mysql_num_rows($getProjects);
$row_getProjects = mysql_fetch_assoc($getProjects);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Senior Design Marketplace | Assign Student</title>
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
h2 {
	font-family: Verdana, Geneva, sans-serif;
	color: #900;
}
</style>

<body>
<h2>Where would you like to assign this student:</h2>
<h1><?php echo $row_getStudent['firstname']; ?> <?php echo $row_getStudent['lastname']; ?></h1>
<p><img src="images/profileupload/<?php echo $row_getStudent['image']; ?>" height="200" /></p>
<hr />

<?php 
//first check to see if there is anything in the recordset....
if (mysql_num_rows($getProjects)==0) {echo 'There are currently no projects open for student assignation. <br />Please approve an existing project idea or create a new project.';}

//else, begin the HTML
else {?>
<table width="100%" border="1" cellpadding="5">
  <tr>
    <th scope="col">&nbsp;</th>
    <th scope="col">Project Title</th>
    <th scope="col">Project Summary</th>
    <th scope="col">Requested Majors</th>
    <th scope="col">Status</th>
    <th scope="col">Min. Size</th>
    <th scope="col">Max. Size</th>
    <th scope="col">Assign</th>
  </tr>
<?php do { ?>  

  <tr>
  
    <td><a href="viewproject.php?idprojects=<?php echo $row_getProjects['idprojects']; ?>"><img src="images/projectupload/<?php echo $row_getProjects['photo']; ?>" height="75" /></a></td>
    <td width="200"><?php echo $row_getProjects['title']; ?></td>
    <td><?php echo $row_getProjects['summary']; ?></td>
    <td width="175"><?php if ($row_getProjects['BTqty'] > 0) echo "Business & Technology <br />"; 
		 	if ($row_getProjects['BMEqty'] > 0) echo "Biomedical Engineering <br />";
			if ($row_getProjects['CEqty'] > 0) echo "Civil Engineering <br />"; 
			if ($row_getProjects['ChemEqty'] > 0) echo "Chemical Engineering <br />"; 
		    if ($row_getProjects['CPEqty'] > 0) echo "Computer Engineering <br />"; 
			if ($row_getProjects['EEqty'] > 0) echo "Electrical Engineering <br />"; 
			if ($row_getProjects['EMqty'] > 0) echo "Engineering Management <br />"; 
			if ($row_getProjects['EnvEqty'] > 0) echo "Environmental Engineering <br />"; 
			if ($row_getProjects['ISqty'] > 0) echo "Information Systems <br />"; 
			if ($row_getProjects['MEqty'] > 0) echo "Mechanical Engineering <br />"; 
			if ($row_getProjects['NEqty'] > 0) echo "Naval Engineering <br />"; 
			if ($row_getProjects['SYSqty'] > 0) echo "Systems Engineering <br />";?></td>
    <td width="125" align="center">
	  <img src="images/<?php echo $row_getProjects['image'];?>" width="100"/><br />
	  <?php echo $row_getProjects['description']; ?>
    </td>
    <td><?php echo $row_getProjects['minsize']; ?></td>
    <td><?php echo $row_getProjects['maxsize']; ?></td>
    <td><form id="form_assign" name="form_assign" method="POST" action="success_assign.php" onsubmit="return confirm('Are you sure you wish to assign the student to this project?')">
        	<input name="idprojects_assign" type="hidden" value="<?php echo $row_getProjects['idprojects']; ?>" />
      		<input name="userid_assign" type="hidden" value="<?php echo $row_getStudent['userid']; ?>" />
       		<input name="role_assign" type="hidden" value="1" />
            <input type="submit" name="button" id="button" value="Assign" />
            <input type="hidden" name="MM_insert" value="form_assign" />
    </form></td>
  </tr>
<?php } while($row_getProjects = mysql_fetch_assoc($getProjects)) ?>
</table>
<?php } ?>

<p><?php echo 'c', $row_getBME['count'], 'q', $row_getQTYs['BMEqty'], 
			'c', $row_getBT['count'], 'q', $row_getQTYs['BTqty'],
		'c', $row_getChemE['count'], 'q', $row_getQTYs['ChemEqty'],
		'c', $row_getCE['count'], 'q', $row_getQTYs['CEqty'],
		'c', $row_getCPE['count'], 'q', $row_getQTYs['CPEqty'],
		'c', $row_getEE['count'], 'q', $row_getQTYs['EEqty'],
		'c', $row_getEM['count'], 'q', $row_getQTYs['EMqty'],
		'c', $row_getEnvE['count'], 'q', $row_getQTYs['EnvEqty'],
		'c', $row_getIS['count'], 'q', $row_getQTYs['ISqty'],
		'c', $row_getME['count'], 'q', $row_getQTYs['MEqty'],
		'c', $row_getNE['count'], 'q', $row_getQTYs['NEqty'],
		'c', $row_getSYS['count'], 'q', $row_getQTYs['SYSqty'];?></p>
</body>
</html>
<?php
mysql_free_result($getStudent);
?>
