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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_assign")) {
  $insertSQL = sprintf("INSERT INTO projectrelation (idprojects, userid, `role`) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['idprojects_assign'], "int"),
                       GetSQLValueString($_POST['userid_assign'], "int"),
                       GetSQLValueString($_POST['role_assign'], "int"));

  mysql_select_db($database_sdmarket, $sdmarket);
  $Result1 = mysql_query($insertSQL, $sdmarket) or die(mysql_error());
  
  
  
  
//Begin HUGE check to see if all project requirements are met...........................................//
//Gather the number of students per major currently working on the approved project.....................//
  	$query_getBME = sprintf("SELECT COUNT(projectrelation.id) AS count FROM projectrelation JOIN user_profile ON projectrelation.userid = user_profile.userid WHERE user_profile.major = 'Biomedical Engineering' AND projectrelation.role = '1' AND projectrelation.idprojects = %s", GetSQLValueString($_POST['idprojects_assign'], "int"));
	$getBME = mysql_query($query_getBME, $sdmarket) or die(mysql_error());
	$row_getBME = mysql_fetch_assoc($getBME);
	$totalRows_getBME = mysql_num_rows($getBME);
	
 	mysql_select_db($database_sdmarket, $sdmarket);
	$query_getBT = sprintf("SELECT COUNT(projectrelation.id) AS count FROM projectrelation JOIN user_profile ON projectrelation.userid = user_profile.userid WHERE user_profile.major = 'Business & Technology' AND projectrelation.role = '1' AND projectrelation.idprojects = %s", GetSQLValueString($_POST['idprojects_assign'], "int"));
	$getBT = mysql_query($query_getBT, $sdmarket) or die(mysql_error());
	$row_getBT = mysql_fetch_assoc($getBT);
	$totalRows_getBT = mysql_num_rows($getBT);
	
	$query_getChemE = sprintf("SELECT COUNT(projectrelation.id) AS count FROM projectrelation JOIN user_profile ON projectrelation.userid = user_profile.userid WHERE user_profile.major = 'Chemical Engineering' AND projectrelation.role = '1' AND projectrelation.idprojects = %s", GetSQLValueString($_POST['idprojects_assign'], "int"));
	$getChemE = mysql_query($query_getChemE, $sdmarket) or die(mysql_error());
	$row_getChemE = mysql_fetch_assoc($getChemE);
	$totalRows_getChemE = mysql_num_rows($getChemE);
	
	$query_getCE = sprintf("SELECT COUNT(projectrelation.id) AS count FROM projectrelation JOIN user_profile ON projectrelation.userid = user_profile.userid WHERE user_profile.major = 'Civil Engineering' AND projectrelation.role = '1' AND projectrelation.idprojects = %s", GetSQLValueString($_POST['idprojects_assign'], "int"));
	$getCE = mysql_query($query_getCE, $sdmarket) or die(mysql_error());
	$row_getCE = mysql_fetch_assoc($getCE);
	$totalRows_getCE = mysql_num_rows($getCE);
	
	$query_getCPE = sprintf("SELECT COUNT(projectrelation.id) AS count FROM projectrelation JOIN user_profile ON projectrelation.userid = user_profile.userid WHERE user_profile.major = 'Computer Engineering' AND projectrelation.role = '1' AND projectrelation.idprojects = %s", GetSQLValueString($_POST['idprojects_assign'], "int"));
	$getCPE = mysql_query($query_getCPE, $sdmarket) or die(mysql_error());
	$row_getCPE = mysql_fetch_assoc($getCPE);
	$totalRows_getCPE = mysql_num_rows($getCPE);
	
	$query_getEE = sprintf("SELECT COUNT(projectrelation.id) AS count FROM projectrelation JOIN user_profile ON projectrelation.userid = user_profile.userid WHERE user_profile.major = 'Electrical Engineering' AND projectrelation.role = '1' AND projectrelation.idprojects = %s", GetSQLValueString($_POST['idprojects_assign'], "int"));
	$getEE = mysql_query($query_getEE, $sdmarket) or die(mysql_error());
	$row_getEE = mysql_fetch_assoc($getEE);
	$totalRows_getEE = mysql_num_rows($getEE);
	
	$query_getEnvE = sprintf("SELECT COUNT(projectrelation.id) AS count FROM projectrelation JOIN user_profile ON projectrelation.userid = user_profile.userid WHERE user_profile.major = 'Environmental Engineering' AND projectrelation.role = '1' AND projectrelation.idprojects = %s", GetSQLValueString($_POST['idprojects_assign'], "int"));
	$getEnvE = mysql_query($query_getEnvE, $sdmarket) or die(mysql_error());
	$row_getEnvE = mysql_fetch_assoc($getEnvE);
	$totalRows_getEnvE = mysql_num_rows($getEnvE);
	
	$query_getEM = sprintf("SELECT COUNT(projectrelation.id) AS count FROM projectrelation JOIN user_profile ON projectrelation.userid = user_profile.userid WHERE user_profile.major = 'Engineering Management' AND projectrelation.role = '1' AND projectrelation.idprojects = %s", GetSQLValueString($_POST['idprojects_assign'], "int"));
	$getEM = mysql_query($query_getEM, $sdmarket) or die(mysql_error());
	$row_getEM = mysql_fetch_assoc($getEM);
	$totalRows_getEM = mysql_num_rows($getEM);
	
	$query_getIS = sprintf("SELECT COUNT(projectrelation.id) AS count FROM projectrelation JOIN user_profile ON projectrelation.userid = user_profile.userid WHERE user_profile.major = 'Information Systems' AND projectrelation.role = '1' AND projectrelation.idprojects = %s", GetSQLValueString($_POST['idprojects_assign'], "int"));
	$getIS = mysql_query($query_getIS, $sdmarket) or die(mysql_error());
	$row_getIS = mysql_fetch_assoc($getIS);
	$totalRows_getIS = mysql_num_rows($getIS);
	
	$query_getME = sprintf("SELECT COUNT(projectrelation.id) AS count FROM projectrelation JOIN user_profile ON projectrelation.userid = user_profile.userid WHERE user_profile.major = 'Mechanical Engineering' AND projectrelation.role = '1' AND projectrelation.idprojects = %s", GetSQLValueString($_POST['idprojects_assign'], "int"));
	$getME = mysql_query($query_getME, $sdmarket) or die(mysql_error());
	$row_getME = mysql_fetch_assoc($getME);
	$totalRows_getME = mysql_num_rows($getME);
	
	$query_getNE = sprintf("SELECT COUNT(projectrelation.id) AS count FROM projectrelation JOIN user_profile ON projectrelation.userid = user_profile.userid WHERE user_profile.major = 'Naval Engineering' AND projectrelation.role = '1' AND projectrelation.idprojects = %s", GetSQLValueString($_POST['idprojects_assign'], "int"));
	$getNE = mysql_query($query_getNE, $sdmarket) or die(mysql_error());
	$row_getNE = mysql_fetch_assoc($getNE);
	$totalRows_getNE = mysql_num_rows($getNE);
	
	$query_getSYS = sprintf("SELECT COUNT(projectrelation.id) AS count FROM projectrelation JOIN user_profile ON projectrelation.userid = user_profile.userid WHERE user_profile.major = 'Systems Engineering' AND projectrelation.role = '1' AND projectrelation.idprojects = %s", GetSQLValueString($_POST['idprojects_assign'], "int"));
	$getSYS = mysql_query($query_getSYS, $sdmarket) or die(mysql_error());
	$row_getSYS = mysql_fetch_assoc($getSYS);
	$totalRows_getSYS = mysql_num_rows($getSYS);
	
	$query_getQTYs = sprintf("SELECT BMEqty, BTqty, ChemEqty, CEqty, CPEqty, EEqty, EMqty, EnvEqty, ISqty, MEqty, NEqty, SYSqty FROM projects WHERE idprojects = %s", GetSQLValueString($_POST['idprojects_assign'], "int"));
	$getQTYs = mysql_query($query_getQTYs, $sdmarket) or die(mysql_error());
	$row_getQTYs = mysql_fetch_assoc($getQTYs);
	$totalRows_getQTYs = mysql_num_rows($getQTYs);
	
//If the number of students currently working on the project is greater than or equal to the Requested quantities.....
	if ($row_getBME['count'] >= $row_getQTYs['BMEqty']
		&& $row_getBT['count'] >= $row_getQTYs['BTqty']
		&& $row_getChemE['count'] >= $row_getQTYs['ChemEqty']
		&& $row_getCE['count'] >= $row_getQTYs['CEqty']
		&& $row_getCPE['count'] >= $row_getQTYs['CPEqty']
		&& $row_getEE['count'] >= $row_getQTYs['EEqty']
		&& $row_getEM['count'] >= $row_getQTYs['EMqty']
		&& $row_getEnvE['count'] >= $row_getQTYs['EnvEqty']
		&& $row_getIS['count'] >= $row_getQTYs['ISqty']
		&& $row_getME['count'] >= $row_getQTYs['MEqty']
		&& $row_getNE['count'] >= $row_getQTYs['NEqty']
		&& $row_getSYS['count'] >= $row_getQTYs['SYSqty']) 
	 
	 {
		 //Then Flip this project from status '2' to status '3'................................................................
 			 $query_statusupdate = sprintf("UPDATE projects SET status = 3 WHERE idprojects = %s",
 			                      GetSQLValueString($_POST['idprojects_assign'], "int"));
			 mysql_select_db($database_sdmarket, $sdmarket);
 			 $statusupdate = mysql_query($query_statusupdate, $sdmarket) or die(mysql_error());

		//Also, delete any outstanding applications in the system for this flipped project....................................
			 $query_deleteoutstanding = sprintf("DELETE FROM projectrelation WHERE idprojects = %s AND role = 0", 
			  					  GetSQLValueString($_POST['idprojects_assign'], "int"));
  			 mysql_select_db($database_sdmarket, $sdmarket);
  			 $deleteoutstanding = mysql_query($query_deleteoutstanding, $sdmarket) or die(mysql_error());
	 }
//End the HUGE check to see if all project requirements are met...........................................//
}





mysql_select_db($database_sdmarket, $sdmarket);
$query_getStudent = sprintf("SELECT firstname, lastname, image FROM user_profile WHERE userid = %s", GetSQLValueString($_POST['userid_assign'], "int"));
$getStudent = mysql_query($query_getStudent, $sdmarket) or die(mysql_error());
$row_getStudent = mysql_fetch_assoc($getStudent);
$totalRows_getStudent = mysql_num_rows($getStudent);

mysql_select_db($database_sdmarket, $sdmarket);
$query_getProject = sprintf("SELECT title, photo FROM projects WHERE idprojects = %s", GetSQLValueString($_POST['idprojects_assign'], "int"));
$getProject = mysql_query($query_getProject, $sdmarket) or die(mysql_error());
$row_getProject = mysql_fetch_assoc($getProject);
$totalRows_getProject = mysql_num_rows($getProject);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Senior Design Marketplace | Student Assigned Successfully</title>
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
<h2><?php echo $row_getStudent['firstname']; ?> <?php echo $row_getStudent['lastname']; ?> has successfully been assigned to project: &quot;<?php echo $row_getProject['title']; ?>&quot;!
</h2>
<p>&nbsp;</p>
<table width="600" border="0" cellpadding="5">
  <tr>
    <td align="right"><a href="viewprofile.php?userid=<?php echo $_POST['userid_assign'];?>"><img src="images/profileupload/<?php echo $row_getStudent['image']; ?>" height="200" align="right" /></a></td>
    <td align="center" valign="top"><p><img src="images/checkmark.png" width="50"/><br /><img src="images/chain.png"/>
      <br />
    </p></td>
    <td align="left"><a href="viewproject.php?idprojects=<?php echo $_POST['idprojects_assign'];?>"><img src="images/projectupload/<?php echo $row_getProject['photo']; ?>" height="200" align="left" /></a></td>
  </tr>
</table>
<p><a href="managestudents.php" target="_top">Return to Manage Students</a></p>
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

mysql_free_result($getProject);
?>
