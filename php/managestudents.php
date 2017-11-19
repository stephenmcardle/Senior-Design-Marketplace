<?php require_once('Connections/sdmarket.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "Advisor";
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
  $MM_referrer = $_SERVER['PATH_INFO'];
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

if ((isset($_POST['id_reject'])) && ($_POST['id_reject'] != "")) {
  $deleteSQL = sprintf("DELETE FROM projectrelation WHERE id=%s",
                       GetSQLValueString($_POST['id_reject'], "int"));

  mysql_select_db($database_sdmarket, $sdmarket);
  $Result1 = mysql_query($deleteSQL, $sdmarket) or die(mysql_error());
}

$editFormAction = $_SERVER['PATH_INFO'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form_approve")) {
  $updateSQL = sprintf("UPDATE projectrelation SET `role`=%s WHERE id=%s",
                       GetSQLValueString($_POST['role_approve'], "int"),
                       GetSQLValueString($_POST['id_approve'], "int"));

  $deleteSQL = sprintf("DELETE FROM projectrelation WHERE `userid`=%s AND role='0'",
                       GetSQLValueString($_POST['userid_approve'], "int"));
					   
  mysql_select_db($database_sdmarket, $sdmarket);
  $Result1 = mysql_query($updateSQL, $sdmarket) or die(mysql_error());
  
  mysql_select_db($database_sdmarket, $sdmarket);
  $Result2 = mysql_query($deleteSQL, $sdmarket) or die(mysql_error());






//Begin HUGE check to see if all project requirements are met...........................................//
//Gather the number of students per major currently working on the approved project.....................//
  	$query_getBME = sprintf("SELECT COUNT(projectrelation.id) AS count FROM projectrelation JOIN user_profile ON projectrelation.userid = user_profile.userid WHERE user_profile.major = 'Biomedical Engineering' AND projectrelation.role = '1' AND projectrelation.idprojects = %s", GetSQLValueString($_POST['idprojects_approve'], "int"));
	$getBME = mysql_query($query_getBME, $sdmarket) or die(mysql_error());
	$row_getBME = mysql_fetch_assoc($getBME);
	$totalRows_getBME = mysql_num_rows($getBME);
	
 	mysql_select_db($database_sdmarket, $sdmarket);
	$query_getBT = sprintf("SELECT COUNT(projectrelation.id) AS count FROM projectrelation JOIN user_profile ON projectrelation.userid = user_profile.userid WHERE user_profile.major = 'Business & Technology' AND projectrelation.role = '1' AND projectrelation.idprojects = %s", GetSQLValueString($_POST['idprojects_approve'], "int"));
	$getBT = mysql_query($query_getBT, $sdmarket) or die(mysql_error());
	$row_getBT = mysql_fetch_assoc($getBT);
	$totalRows_getBT = mysql_num_rows($getBT);
	
	$query_getChemE = sprintf("SELECT COUNT(projectrelation.id) AS count FROM projectrelation JOIN user_profile ON projectrelation.userid = user_profile.userid WHERE user_profile.major = 'Chemical Engineering' AND projectrelation.role = '1' AND projectrelation.idprojects = %s", GetSQLValueString($_POST['idprojects_approve'], "int"));
	$getChemE = mysql_query($query_getChemE, $sdmarket) or die(mysql_error());
	$row_getChemE = mysql_fetch_assoc($getChemE);
	$totalRows_getChemE = mysql_num_rows($getChemE);
	
	$query_getCE = sprintf("SELECT COUNT(projectrelation.id) AS count FROM projectrelation JOIN user_profile ON projectrelation.userid = user_profile.userid WHERE user_profile.major = 'Civil Engineering' AND projectrelation.role = '1' AND projectrelation.idprojects = %s", GetSQLValueString($_POST['idprojects_approve'], "int"));
	$getCE = mysql_query($query_getCE, $sdmarket) or die(mysql_error());
	$row_getCE = mysql_fetch_assoc($getCE);
	$totalRows_getCE = mysql_num_rows($getCE);
	
	$query_getCPE = sprintf("SELECT COUNT(projectrelation.id) AS count FROM projectrelation JOIN user_profile ON projectrelation.userid = user_profile.userid WHERE user_profile.major = 'Computer Engineering' AND projectrelation.role = '1' AND projectrelation.idprojects = %s", GetSQLValueString($_POST['idprojects_approve'], "int"));
	$getCPE = mysql_query($query_getCPE, $sdmarket) or die(mysql_error());
	$row_getCPE = mysql_fetch_assoc($getCPE);
	$totalRows_getCPE = mysql_num_rows($getCPE);
	
	$query_getEE = sprintf("SELECT COUNT(projectrelation.id) AS count FROM projectrelation JOIN user_profile ON projectrelation.userid = user_profile.userid WHERE user_profile.major = 'Electrical Engineering' AND projectrelation.role = '1' AND projectrelation.idprojects = %s", GetSQLValueString($_POST['idprojects_approve'], "int"));
	$getEE = mysql_query($query_getEE, $sdmarket) or die(mysql_error());
	$row_getEE = mysql_fetch_assoc($getEE);
	$totalRows_getEE = mysql_num_rows($getEE);
	
	$query_getEnvE = sprintf("SELECT COUNT(projectrelation.id) AS count FROM projectrelation JOIN user_profile ON projectrelation.userid = user_profile.userid WHERE user_profile.major = 'Environmental Engineering' AND projectrelation.role = '1' AND projectrelation.idprojects = %s", GetSQLValueString($_POST['idprojects_approve'], "int"));
	$getEnvE = mysql_query($query_getEnvE, $sdmarket) or die(mysql_error());
	$row_getEnvE = mysql_fetch_assoc($getEnvE);
	$totalRows_getEnvE = mysql_num_rows($getEnvE);
	
	$query_getEM = sprintf("SELECT COUNT(projectrelation.id) AS count FROM projectrelation JOIN user_profile ON projectrelation.userid = user_profile.userid WHERE user_profile.major = 'Engineering Management' AND projectrelation.role = '1' AND projectrelation.idprojects = %s", GetSQLValueString($_POST['idprojects_approve'], "int"));
	$getEM = mysql_query($query_getEM, $sdmarket) or die(mysql_error());
	$row_getEM = mysql_fetch_assoc($getEM);
	$totalRows_getEM = mysql_num_rows($getEM);
	
	$query_getIS = sprintf("SELECT COUNT(projectrelation.id) AS count FROM projectrelation JOIN user_profile ON projectrelation.userid = user_profile.userid WHERE user_profile.major = 'Information Systems' AND projectrelation.role = '1' AND projectrelation.idprojects = %s", GetSQLValueString($_POST['idprojects_approve'], "int"));
	$getIS = mysql_query($query_getIS, $sdmarket) or die(mysql_error());
	$row_getIS = mysql_fetch_assoc($getIS);
	$totalRows_getIS = mysql_num_rows($getIS);
	
	$query_getME = sprintf("SELECT COUNT(projectrelation.id) AS count FROM projectrelation JOIN user_profile ON projectrelation.userid = user_profile.userid WHERE user_profile.major = 'Mechanical Engineering' AND projectrelation.role = '1' AND projectrelation.idprojects = %s", GetSQLValueString($_POST['idprojects_approve'], "int"));
	$getME = mysql_query($query_getME, $sdmarket) or die(mysql_error());
	$row_getME = mysql_fetch_assoc($getME);
	$totalRows_getME = mysql_num_rows($getME);
	
	$query_getNE = sprintf("SELECT COUNT(projectrelation.id) AS count FROM projectrelation JOIN user_profile ON projectrelation.userid = user_profile.userid WHERE user_profile.major = 'Naval Engineering' AND projectrelation.role = '1' AND projectrelation.idprojects = %s", GetSQLValueString($_POST['idprojects_approve'], "int"));
	$getNE = mysql_query($query_getNE, $sdmarket) or die(mysql_error());
	$row_getNE = mysql_fetch_assoc($getNE);
	$totalRows_getNE = mysql_num_rows($getNE);
	
	$query_getSYS = sprintf("SELECT COUNT(projectrelation.id) AS count FROM projectrelation JOIN user_profile ON projectrelation.userid = user_profile.userid WHERE user_profile.major = 'Systems Engineering' AND projectrelation.role = '1' AND projectrelation.idprojects = %s", GetSQLValueString($_POST['idprojects_approve'], "int"));
	$getSYS = mysql_query($query_getSYS, $sdmarket) or die(mysql_error());
	$row_getSYS = mysql_fetch_assoc($getSYS);
	$totalRows_getSYS = mysql_num_rows($getSYS);
	
	$query_getQTYs = sprintf("SELECT BMEqty, BTqty, ChemEqty, CEqty, CPEqty, EEqty, EMqty, EnvEqty, ISqty, MEqty, NEqty, SYSqty FROM projects WHERE idprojects = %s", GetSQLValueString($_POST['idprojects_approve'], "int"));
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
 			                      GetSQLValueString($_POST['idprojects_approve'], "int"));
			 mysql_select_db($database_sdmarket, $sdmarket);
 			 $statusupdate = mysql_query($query_statusupdate, $sdmarket) or die(mysql_error());

		//Also, delete any outstanding applications in the system for this flipped project....................................
			 $query_deleteoutstanding = sprintf("DELETE FROM projectrelation WHERE idprojects = %s AND role = 0", 
			  					  GetSQLValueString($_POST['idprojects_approve'], "int"));
  			 mysql_select_db($database_sdmarket, $sdmarket);
  			 $deleteoutstanding = mysql_query($query_deleteoutstanding, $sdmarket) or die(mysql_error());
	 }
}
//End the HUGE check to see if all project requirements are met...........................................//




$email_getDept = "-1";
if (isset($_SESSION['MM_Username'])) {
  $email_getDept = $_SESSION['MM_Username'];
}

mysql_select_db($database_sdmarket, $sdmarket);
$query_getDept = sprintf("SELECT department FROM user_profile WHERE email = %s", GetSQLValueString($email_getDept, "text"),GetSQLValueString($email_getDept, "text"));
$getDept = mysql_query($query_getDept, $sdmarket) or die(mysql_error());
$row_getDept = mysql_fetch_assoc($getDept);
$totalRows_getDept = mysql_num_rows($getDept);

mysql_select_db($database_sdmarket, $sdmarket);
$query_getAwaiting = sprintf("SELECT user_profile.userid, user_profile.firstname, user_profile.lastname, user_profile.image, projects.idprojects, projects.photo, projects.title, projects.summary, projects.BTqty, projects.BMEqty, projects.CEqty, projects.CPEqty, projects.EEqty, projects.EnvEqty, projects.MEqty, projects.EMqty, projects.NEqty, projects.SYSqty, projects.ChemEqty, projects.ISqty, projects.minsize, projects.maxsize, projectrelation.id, projectrelation.role FROM user_profile JOIN projectrelation on user_profile.userid = projectrelation.userid JOIN projects on projects.idprojects = projectrelation.idprojects WHERE user_profile.major = %s AND projectrelation.role = '0'", GetSQLValueString($row_getDept['department'], "text"));
$getAwaiting = mysql_query($query_getAwaiting, $sdmarket) or die(mysql_error());
$row_getAwaiting = mysql_fetch_assoc($getAwaiting);
$totalRows_getAwaiting = mysql_num_rows($getAwaiting);

mysql_select_db($database_sdmarket, $sdmarket);
$query_getUnassigned = sprintf("SELECT user_profile.userid, user_profile.firstname, user_profile.lastname, user_profile.image, user_profile.skill1, user_profile.skill2, user_profile.skill3, user_profile.skill4, user_profile.skill5 FROM user_profile LEFT OUTER JOIN projectrelation ON user_profile.userid = projectrelation.userid WHERE user_profile.major = %s AND user_profile.role = '1' AND projectrelation.userid IS NULL", GetSQLValueString($row_getDept['department'], "text"));
$getUnassigned = mysql_query($query_getUnassigned, $sdmarket) or die(mysql_error());
$row_getUnassigned = mysql_fetch_assoc($getUnassigned);
$totalRows_getUnassigned = mysql_num_rows($getUnassigned);

mysql_select_db($database_sdmarket, $sdmarket);
$query_getAssigned = sprintf("SELECT user_profile.userid, user_profile.firstname, user_profile.lastname, user_profile.image, projects.idprojects, projects.photo, projects.title, projects.summary, projects.CEqty, projects.CPEqty, projects.EEqty, projects.EnvEqty, projects.MEqty, projects.EMqty, projects.NEqty, projects.SYSqty, projects.BMEqty, projects.ChemEqty, projects.ISqty, projects.status, projects.minsize, projects.maxsize, projectrelation.role, projectstatus.description, projectstatus.image AS indicator FROM user_profile JOIN projectrelation on user_profile.userid = projectrelation.userid JOIN projects on projects.idprojects = projectrelation.idprojects JOIN projectstatus on projects.status = projectstatus.status WHERE user_profile.major = %s AND projectrelation.role = '1'", GetSQLValueString($row_getDept['department'], "text"));
$getAssigned = mysql_query($query_getAssigned, $sdmarket) or die(mysql_error());
$row_getAssigned = mysql_fetch_assoc($getAssigned);
$totalRows_getAssigned = mysql_num_rows($getAssigned);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Senior Design Marketplace | Manage Students</title>
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
<h1>Manage Students (<?php echo $row_getDept['department']; ?>)</h1>
<hr size="1"/>
<h2>Students Awaiting Approval</h2>
<?php 
//first check to see if there is anything in the recordset....
if (mysql_num_rows($getAwaiting)==0) {echo 'There are currently no students with pending project applications.';}

//else, begin the script to display the results in an table....
else { ?>
<table width="100%" border="1" cellpadding="5">
  <tr>
    <th scope="col">&nbsp;</th> 
    <th scope="col">First Name</th>
    <th scope="col">Last Name</th>
    <th scope="col">&nbsp;</th>
    <th scope="col">Project Title</th>
    <th scope="col">Project Summary</th>
    <th scope="col">Requested Majors</th>
    <th scope="col">Min. Size</th>
    <th scope="col">Max. Size</th>
    <th scope="col">Approve/ Reject</th>
  </tr>
<?php do { ?>  
  <tr>
    <td width="75" align="center"><a href="viewprofile.php?userid=<?php echo $row_getAwaiting['userid']; ?>"><img src="images/profileupload/<?php echo $row_getAwaiting['image']; ?>" width="85" height="85" /></a></td>
    <td><?php echo $row_getAwaiting['firstname']; ?></td>
    <td><?php echo $row_getAwaiting['lastname']; ?></td>
    <td><a href="viewproject.php?idprojects=<?php echo $row_getAwaiting['idprojects']; ?>"><img src="images/projectupload/<?php echo $row_getAwaiting['photo']; ?>" height="75" /></a></td>
    <td width="200"><?php echo $row_getAwaiting['title']; ?></td>
    <td><?php echo $row_getAwaiting['summary']; ?></td>
    <td width="175"><?php if ($row_getAwaiting['BMEqty'] > 0) echo "Biomedical Engineering <br />"; 
		 	if ($row_getAwaiting['CEqty'] > 0) echo "Civil Engineering <br />"; 
			if ($row_getAwaiting['ChemEqty'] > 0) echo "Chemical Engineering <br />"; 
		    if ($row_getAwaiting['CPEqty'] > 0) echo "Computer Engineering <br />"; 
			if ($row_getAwaiting['EEqty'] > 0) echo "Electrical Engineering <br />"; 
			if ($row_getAwaiting['EMqty'] > 0) echo "Engineering Management <br />"; 
			if ($row_getAwaiting['EnvEqty'] > 0) echo "Environmental Engineering <br />"; 
			if ($row_getAwaiting['ISqty'] > 0) echo "Information Systems <br />"; 
			if ($row_getAwaiting['MEqty'] > 0) echo "Mechanical Engineering <br />"; 
			if ($row_getAwaiting['NEqty'] > 0) echo "Naval Engineering <br />"; 
			if ($row_getAwaiting['SYSqty'] > 0) echo "Systems Engineering <br />";?></td>
    <td><?php echo $row_getAwaiting['minsize']; ?></td>
    <td><?php echo $row_getAwaiting['maxsize']; ?></td>
    <td align="center" width="140">
      <form action="<?php echo $editFormAction; ?>" id="form_approve" name="form_approve" method="POST" onsubmit="return confirm('Approving the selected student to work on this project will reject all of their other outstanding applications. Do you want to proceed?')">
    	<input name="id_approve" type="hidden" value="<?php echo $row_getAwaiting['id']; ?>" />
        <input name="idprojects_approve" type="hidden" value="<?php echo $row_getAwaiting['idprojects']; ?>" />
        <input name="userid_approve" type="hidden" value="<?php echo $row_getAwaiting['userid']; ?>" />
        <input name="role_approve" type="hidden" value="1" />
    	<input type="submit" name="action_approve" id="approve" value="Approve"/>
        <input type="hidden" name="MM_update" value="form_approve" />
      </form> 
      <form action="" id="form_reject" name="form_reject" method="POST">
        <input name="id_reject" type="hidden" value="<?php echo $row_getAwaiting['id']; ?>" />
        <input type="submit" name="action_reject" id="reject" value="Reject"/>
      </form>
    </td>
  </tr>


<?php } while($row_getAwaiting = mysql_fetch_assoc($getAwaiting)) ?>
</table>
<?php } ?>

<p>&nbsp;</p>

<h2>Unassigned Students</h2>
<?php
//first check to see if there is anything in the recordset....
if (mysql_num_rows($getUnassigned)==0) {echo 'All students are currently assigned or have applied to a project.';}

//else, begin the script to display the results in an table....
else { ?>
<table width="1000" border="1" cellpadding="5">
  <tr>
    <th scope="col">&nbsp;</th>
    <th scope="col">First Name</th>
    <th scope="col">Last Name</th>
    <th scope="col">Skills</th>
    <th scope="col">Recommend</th>
    <th scope="col">Assign</th>
  </tr>
<?php do { ?>  
  <tr>
    <td width="75" align="center"><a href="viewprofile.php?userid=<?php echo $row_getUnassigned['userid']; ?>"><img src="images/profileupload/<?php echo $row_getUnassigned['image']; ?>" width="85" height="85" /></a></td>
    <td><?php echo $row_getUnassigned['firstname']; ?></td>
    <td><?php echo $row_getUnassigned['lastname']; ?></td>
    <td width="200">
		<?php echo $row_getUnassigned['skill1']; ?>
        <br /><?php echo $row_getUnassigned['skill2']; ?>
        <br /><?php echo $row_getUnassigned['skill3']; ?>
        <br /><?php echo $row_getUnassigned['skill4']; ?>
        <br /><?php echo $row_getUnassigned['skill5']; ?>
    </td>
    <td align="center"><input type="button" name="button_recommend" id="button_recommend" value="Recommend a Project" onclick="location.href='recommend.php'"/></td>
      <td align="center"><input type="button" name="button_assign" id="button_assign" value="Assign to Project" onclick="location.href='assign.php?userid=<?php echo $row_getUnassigned['userid'];?>'"/></td>
</tr>
<?php } while($row_getUnassigned = mysql_fetch_assoc($getUnassigned)) ?>
</table>
<?php } ?>

<h2>&nbsp;</h2>
<h2>Students Already Assigned</h2>
<?php
//first check to see if there is anything in the recordset....
if (mysql_num_rows($getAssigned)==0) {echo 'There are currently no students assigned to projects.';}

//else, begin the script to display the results in an table....
else { ?>
<table width="100%" border="1" cellpadding="5">
  <tr>
    <th scope="col">&nbsp;</th>
    <th scope="col">First Name</th>
    <th scope="col">Last Name</th>
    <th scope="col">&nbsp;</th>
    <th scope="col">Project Title</th>
    <th scope="col">Project Summary</th>
    <th scope="col">Requested Majors</th>
    <th scope="col">Status</th>
    <th scope="col">Min. Size</th>
    <th scope="col">Max. Size</th>
  </tr>
<?php do { ?>  
  <tr>
    <td width="75" align="center"><a href="viewprofile.php?userid=<?php echo $row_getAssigned['userid']; ?>"><img src="images/profileupload/<?php echo $row_getAssigned['image']; ?>" width="85" height="85" /></a></td>
    <td><?php echo $row_getAssigned['firstname']; ?></td>
    <td><?php echo $row_getAssigned['lastname']; ?></td>
    <td><a href="viewproject.php?idprojects=<?php echo $row_getAssigned['idprojects']; ?>"><img src="images/projectupload/<?php echo $row_getAssigned['photo']; ?>" height="75" /></a></td>
    <td width="200"><?php echo $row_getAssigned['title']; ?></td>
    <td><?php echo $row_getAssigned['summary']; ?></td>
    <td width="175"><?php if ($row_getAssigned['BMEqty'] > 0) echo "Biomedical Engineering <br />"; 
		 	if ($row_getAssigned['CEqty'] > 0) echo "Civil Engineering <br />"; 
			if ($row_getAssigned['ChemEqty'] > 0) echo "Chemical Engineering <br />"; 
		    if ($row_getAssigned['CPEqty'] > 0) echo "Computer Engineering <br />"; 
			if ($row_getAssigned['EEqty'] > 0) echo "Electrical Engineering <br />"; 
			if ($row_getAssigned['EMqty'] > 0) echo "Engineering Management <br />"; 
			if ($row_getAssigned['EnvEqty'] > 0) echo "Environmental Engineering <br />"; 
			if ($row_getAssigned['ISqty'] > 0) echo "Information Systems <br />"; 
			if ($row_getAssigned['MEqty'] > 0) echo "Mechanical Engineering <br />"; 
			if ($row_getAssigned['NEqty'] > 0) echo "Naval Engineering <br />"; 
			if ($row_getAssigned['SYSqty'] > 0) echo "Systems Engineering <br />";?></td>
    <td width="125" align="center">
	  <img src="images/<?php echo $row_getAssigned['indicator'];?>" width="100"/><br />
	  <?php echo $row_getAssigned['description']; ?>
    </td>
    <td><?php echo $row_getAssigned['minsize']; ?></td>
    <td><?php echo $row_getAssigned['maxsize']; ?></td>
  </tr>
<?php } while($row_getAssigned = mysql_fetch_assoc($getAssigned)) ?>
</table>
<p>
  <?php } ?>
</p>
<p><?php echo 'bmecount', $row_getBME['count']; 
		 echo 'bmeqty', $row_getQTYs['BMEqty'];
		 echo 'btcount', $row_getBT['count']; 
		 echo 'btqty', $row_getQTYs['BTqty'];
		 echo 'chemecount', $row_getChemE['count']; 
	  	 echo 'chemeqty', $row_getQTYs['ChemEqty'];
	 	 echo 'cecount', $row_getCE['count']; 
	 	 echo 'ceqty', $row_getQTYs['CEqty'];
	 	 echo 'cpecount', $row_getCPE['count']; 
		 echo 'cpeqty', $row_getQTYs['CPEqty'];
		 echo 'eecount', $row_getEE['count']; 
		 echo 'eeqty', $row_getQTYs['EEqty'];
		 echo 'emcount', $row_getEM['count']; 
		 echo 'emqty', $row_getQTYs['EMqty'];
		 echo 'envecount', $row_getEnvE['count']; 
		 echo 'enveqty', $row_getQTYs['EnvEqty'];
		 echo 'iscount', $row_getIS['count']; 
		 echo 'isqty', $row_getQTYs['ISqty'];
		 echo 'mecount', $row_getME['count']; 
		 echo 'meqty', $row_getQTYs['MEqty'];
		 echo 'necount', $row_getNE['count']; 
		 echo 'neqty', $row_getQTYs['NEqty'];
		 echo 'syscount', $row_getSYS['count']; 
		 echo 'sysqty', $row_getQTYs['SYSqty']; ?></p>
</body>
</html>
<?php
mysql_free_result($getDept);
mysql_free_result($getAwaiting);
mysql_free_result($getAssigned);

?>
