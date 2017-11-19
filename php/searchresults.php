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
<?php require_once('Connections/sdmarket.php'); ?>
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








if (isset($_POST['idprojects']) && empty($_POST['idprojects'])) {
$_POST['idprojects'] = '%';
}
if (isset($_POST['title']) && empty($_POST['title'])) {
$_POST['title'] = '%';
}
if (isset($_POST['status']) && empty($_POST['status'])) {
$_POST['status'] = '%';
}
if (empty($_POST['BMEqty'])) {
$_POST['BMEqty'] = '-1';
}
if (empty($_POST['CEqty'])) {
$_POST['CEqty'] = '-1';
}
if (empty($_POST['ChemEqty'])) {
$_POST['ChemEqty'] = '-1';
}
if (empty($_POST['CPEqty'])) {
$_POST['CPEqty'] = '-1';
}
if (empty($_POST['EEqty'])) {
$_POST['EEqty'] = '-1';
}
if (empty($_POST['EMqty'])) {
$_POST['EMqty'] = '-1';
}
if (empty($_POST['EnvEqty'])) {
$_POST['EnvEqty'] = '-1';
}
if (empty($_POST['ISqty'])) {
$_POST['ISqty'] = '-1';
}
if (empty($_POST['MEqty'])) {
$_POST['MEqty'] = '-1';
}
if (empty($_POST['NEqty'])) {
$_POST['NEqty'] = '-1';
}
if (empty($_POST['SYSqty'])) {
$_POST['SYSqty'] = '-1';
}



$colidprojects = $_POST['idprojects'];
$coltitle = $_POST['title'];
$colstatus = $_POST['status'];
$colBMEqty = $_POST['BMEqty'];
$colCEqty = $_POST['CEqty'];
$colChemEqty = $_POST['ChemEqty'];
$colCPEqty = $_POST['CPEqty'];
$colEEqty = $_POST['EEqty'];
$colEMqty = $_POST['EMqty'];
$colEnvEqty = $_POST['EnvEqty'];
$colISqty = $_POST['ISqty'];
$colMEqty = $_POST['MEqty'];
$colNEqty = $_POST['NEqty'];
$colSYSqty = $_POST['SYSqty'];

mysql_select_db($database_sdmarket, $sdmarket);
$query_getProjects = "SELECT projects.idprojects, projects.title, projects.summary, projects.status, projects.views, projects.photo, projectstatus.image, projects.BTqty, projects.BMEqty, projects.CEqty, projects.ChemEqty, projects.CPEqty, projects.EEqty, projects.EMqty, projects.EnvEqty, projects.ISqty, projects.MEqty, projects.NEqty, projects.SYSqty
FROM projects 
JOIN projectstatus ON projects.status=projectstatus.status
WHERE projects.idprojects LIKE '%$colidprojects%' 
AND projects.title LIKE '%$coltitle%' 
AND projects.status LIKE '%$colstatus%'
AND (projects.BTqty BETWEEN 1 AND '$colBTqty'
OR projects.BMEqty BETWEEN 1 AND '$colBMEqty'
OR projects.CEqty BETWEEN 1 AND '$colCEqty'
OR projects.ChemEqty BETWEEN 1 AND '$colChemEqty'
OR projects.CPEqty BETWEEN 1 AND '$colCPEqty'
OR projects.EEqty BETWEEN 1 AND '$colEEqty'
OR projects.EMqty BETWEEN 1 AND '$colEMqty'
OR projects.EnvEqty BETWEEN 1 AND '$colEnvEqty'
OR projects.ISqty BETWEEN 1 AND '$colISqty'
OR projects.MEqty BETWEEN 1 AND '$colMEqty'
OR projects.NEqty BETWEEN 1 AND '$colNEqty'
OR projects.SYSqty BETWEEN 1 AND '$colSYSqty'
OR projects.sizeunknown LIKE '%x%')
ORDER BY projects.created DESC";
$getProjects = mysql_query($query_getProjects, $sdmarket) or die(mysql_error());
$row_getProjects = mysql_fetch_assoc($getProjects);
$totalRows_getProjects = mysql_num_rows($getProjects);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Senior Design Marketplace | Projects</title>
<style type="text/css">
body,td,th {
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 14px;
}
pt {
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 24px;
}
</style>
</head>

<body>



<!--//-------------------------BEGIN "DISPLAY RESULTS IN ARRAY" SCRIPT-------------------->
<?php 
//first check to see if there is anything in the recordset....
if (mysql_num_rows($getProjects)==0) {echo 'Your search has not reurned any results. <a href="searchproject.php">Please try your search again. </a>';}

//else, begin the script to display the results in an array....
else {
$count_projects = mysql_num_rows($getProjects);?>  
<?php
$i = 1;
do {
	
mysql_select_db($database_sdmarket, $sdmarket);
$query_getLikes = sprintf("SELECT COUNT(idprojects) AS likes FROM project_likes WHERE idprojects = %s", 
GetSQLValueString($row_getProjects['idprojects'] , "int"));

$getLikes = mysql_query($query_getLikes, $sdmarket) or die(mysql_error());
$row_getLikes = mysql_fetch_assoc($getLikes);
$totalRows_getLikes = mysql_num_rows($getLikes);

?>

<table width="530" border="1" rules="none" frame="box" align="left" cellpadding="5">
  <tr height="100">  
    <td colspan="3" align="center" valign="top">
      <strong><pt><?php echo $row_getProjects['title']; ?></pt></strong> <hr /></td>
  </tr>
  <tr height="200">
    <td width="100" rowspan="2" align="center" valign="top"><img src="images/<?php echo $row_getProjects['image']; ?>" alt="" width="100" height="40"  />
      <p><img src="images/like.png" alt="" width="23" height="23" /> <?php echo $row_getLikes['likes']; ?></p>
      <p>Views: <?php echo $row_getProjects['views']; ?></p></td>
    <td width="200" valign="top">
      <a href="viewproject.php?idprojects=<?php echo $row_getProjects['idprojects']; ?>"><img src="images/projectupload/<?php echo $row_getProjects['photo']?>" height="150" /></a>
    </td>
    <td valign="top">
      <p><strong>Requested Major(s):</strong></p>
      <?php if ($row_getProjects['BMEqty'] > 0) echo "Biomedical Engineering <br />"; 
	  		if ($row_getProjects['BTqty'] > 0) echo "Business & Technology <br />";
		 	if ($row_getProjects['CEqty'] > 0) echo "Civil Engineering <br />"; 
			if ($row_getProjects['ChemEqty'] > 0) echo "Chemical Engineering <br />"; 
		    if ($row_getProjects['CPEqty'] > 0) echo "Computer Engineering <br />"; 
			if ($row_getProjects['EEqty'] > 0) echo "Electrical Engineering <br />"; 
			if ($row_getProjects['EMqty'] > 0) echo "Engineering Management <br />"; 
			if ($row_getProjects['EnvEqty'] > 0) echo "Environmental Engineering <br />"; 
			if ($row_getProjects['ISqty'] > 0) echo "Information Systems <br />"; 
			if ($row_getProjects['MEqty'] > 0) echo "Mechanical Engineering <br />"; 
			if ($row_getProjects['NEqty'] > 0) echo "Naval Engineering <br />"; 
			if ($row_getProjects['SYSqty'] > 0) echo "Systems Engineering <br />";?>
    </td>
  </tr>
  <tr height="100">
    <td colspan="2" valign="top"><?php echo $row_getProjects['summary']; ?>...</td>
  </tr>
</table>
   
<?php 
if ($i%3) { ?>

<table width="3" align="left">
  <tr height="100">  
    <td>
    </td>
  </tr>
  <tr height="200">
    <td width="3">
    </td>
    <td width="3">
    </td>
    <td>
    </td>
  </tr>
  <tr height="100">
    <td>
    </td>
  </tr>
</table>

<?php ;
} else { 
	if ($i >= $count_projects) { 
	} else { 
		?> <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p> <?php ;
			} 
	} 
$i++; 

} while($row_getProjects = mysql_fetch_assoc($getProjects)); 

}?>
<!--//-------------------------END "DISPLAY RESULTS IN ARRAY" SCRIPT-------------------->

<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($getLikes);

mysql_free_result($getProjects);
?>
