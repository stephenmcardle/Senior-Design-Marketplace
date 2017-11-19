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

$editFormAction = $_SERVER['PATH_INFO'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_likes")) {
  $insertSQL = sprintf("INSERT INTO project_likes (idprojects, userid) VALUES (%s, %s)",
                       GetSQLValueString($_POST['likes_projectid'], "int"),
                       GetSQLValueString($_POST['likes_userid'], "int"));

  mysql_select_db($database_sdmarket, $sdmarket);
  $Result1 = mysql_query($insertSQL, $sdmarket) or die(mysql_error());
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form_unlike")) {
  $updateSQL = sprintf("DELETE FROM project_likes WHERE userid=%s AND idprojects=%s",
                       GetSQLValueString($_POST['likes_userid'], "int"),
                       GetSQLValueString($_POST['likes_projectid'], "int"));

  mysql_select_db($database_sdmarket, $sdmarket);
  $Result1 = mysql_query($updateSQL, $sdmarket) or die(mysql_error());
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_approve")) {
  $insertSQL = sprintf("INSERT INTO projectrelation (idprojects, userid, `role`) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['approve_idprojects'], "int"),
                       GetSQLValueString($_POST['approve_userid'], "int"),
                       GetSQLValueString($_POST['approve_role'], "int"));

  mysql_select_db($database_sdmarket, $sdmarket);
  $Result1 = mysql_query($insertSQL, $sdmarket) or die(mysql_error());
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_apply")) {
  $insertSQL = sprintf("INSERT INTO projectrelation (idprojects, userid, `role`) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['apply_idprojects'], "int"),
                       GetSQLValueString($_POST['apply_userid'], "int"),
                       GetSQLValueString($_POST['apply_role'], "int"));

  mysql_select_db($database_sdmarket, $sdmarket);
  $Result1 = mysql_query($insertSQL, $sdmarket) or die(mysql_error());
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form_comment")) {
  $insertSQL = sprintf("INSERT INTO projectcomments (idprojects, userid, `comment`) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['idprojects'], "int"),
                       GetSQLValueString($_POST['userid'], "int"),
                       GetSQLValueString($_POST['comment'], "text"));

  mysql_select_db($database_sdmarket, $sdmarket);
  $Result1 = mysql_query($insertSQL, $sdmarket) or die(mysql_error());
}

if ((isset($_POST["MM_delete"])) && ($_POST["MM_delete"] == "form_delete")) {
  $insertSQL = sprintf("DELETE FROM projects WHERE idprojects=%s ",
                       GetSQLValueString($_GET['idprojects'], "int"));

  mysql_select_db($database_sdmarket, $sdmarket);
  $Result1 = mysql_query($insertSQL, $sdmarket) or die(mysql_error());
  
  $insertGoTo = "success_delete.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$colname_getViewproject = "-1";
if (isset($_GET['idprojects'])) {
  $colname_getViewproject = $_GET['idprojects'];
}
mysql_select_db($database_sdmarket, $sdmarket);
$query_getViewproject = sprintf("SELECT projects.idprojects, projects.title, projects.summary, projects.description, projects.status, projects.minsize, projects.maxsize, projects.sizeunknown, projects.BTqty, projects.BMEqty, projects.CEqty, projects.CPEqty, projects.EEqty, projects.EnvEqty, projects.MEqty, projects.EMqty, projects.NEqty, projects.SYSqty, projects.ChemEqty, projects.ISqty, projects.views, projects.attachment, projects.photo, projects.created, projects.createdby, user_profile.userid, user_profile.firstname, user_profile.lastname, user_profile.email, projectstatus.image FROM projects JOIN user_profile ON projects.createdby = user_profile.email JOIN projectstatus ON projects.status = projectstatus.status WHERE idprojects = %s", GetSQLValueString($colname_getViewproject, "int"));
$getViewproject = mysql_query($query_getViewproject, $sdmarket) or die(mysql_error());
$row_getViewproject = mysql_fetch_assoc($getViewproject);
$totalRows_getViewproject = mysql_num_rows($getViewproject);

$colname_getRole = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_getRole = $_SESSION['MM_Username'];
}
mysql_select_db($database_sdmarket, $sdmarket);
$query_getRole = sprintf("SELECT role, userid FROM user_profile WHERE email = %s", GetSQLValueString($colname_getRole, "text"));
$getRole = mysql_query($query_getRole, $sdmarket) or die(mysql_error());
$row_getRole = mysql_fetch_assoc($getRole);
$totalRows_getRole = mysql_num_rows($getRole);

$varidproject_getLikes = "0";
if (isset($row_getViewproject['idprojects'])) {
  $varidproject_getLikes = $row_getViewproject['idprojects'];
}
$varuserid_getLikes = "0";
if (isset($row_getRole['userid'])) {
  $varuserid_getLikes = $row_getRole['userid'];
}
mysql_select_db($database_sdmarket, $sdmarket);
$query_getLikes = sprintf("SELECT * FROM project_likes WHERE idprojects = %s AND userid = %s", GetSQLValueString($varidproject_getLikes, "int"),GetSQLValueString($varuserid_getLikes, "int"));
$getLikes = mysql_query($query_getLikes, $sdmarket) or die(mysql_error());
$row_getLikes = mysql_fetch_assoc($getLikes);
$totalRows_getLikes = mysql_num_rows($getLikes);

$currentproject_getStudents = "-1";
if (isset($_GET['idprojects'])) {
  $currentproject_getStudents = $_GET['idprojects'];
}
mysql_select_db($database_sdmarket, $sdmarket);
$query_getStudents = sprintf("SELECT user_profile.userid, user_profile.firstname, user_profile.lastname, user_profile.image, user_profile.email, user_profile.major FROM projectrelation JOIN user_profile ON projectrelation.userid = user_profile.userid WHERE projectrelation.idprojects = %s AND projectrelation.role = 1", GetSQLValueString($currentproject_getStudents, "int"));
$getStudents = mysql_query($query_getStudents, $sdmarket) or die(mysql_error());
$row_getStudents = mysql_fetch_assoc($getStudents);
$totalRows_getStudents = mysql_num_rows($getStudents);

mysql_select_db($database_sdmarket, $sdmarket);
$query_getAdvisors = sprintf("SELECT user_profile.userid, user_profile.firstname, user_profile.lastname, user_profile.image, user_profile.email, user_profile.major FROM projectrelation JOIN user_profile ON projectrelation.userid = user_profile.userid WHERE projectrelation.idprojects = %s AND projectrelation.role = 2", GetSQLValueString($_GET['idprojects'], "int"));
$getAdvisors = mysql_query($query_getAdvisors, $sdmarket) or die(mysql_error());
$row_getAdvisors = mysql_fetch_assoc($getAdvisors);
$totalRows_getAdvisors = mysql_num_rows($getAdvisors);

$colname_getComment = "-1";
if (isset($_GET['idprojects'])) {
  $colname_getComment = $_GET['idprojects'];
}
mysql_select_db($database_sdmarket, $sdmarket);
$query_getComment = sprintf("SELECT projectcomments.idprojects, projectcomments.userid, projectcomments.`comment`, user_profile.firstname,  user_profile.lastname, user_profile.image FROM projectcomments
JOIN user_profile ON projectcomments.userid = user_profile.userid WHERE idprojects = %s", GetSQLValueString($colname_getComment, "int"));
$getComment = mysql_query($query_getComment, $sdmarket) or die(mysql_error());
$row_getComment = mysql_fetch_assoc($getComment);
$totalRows_getComment = mysql_num_rows($getComment);

$getRelations_userid = "-1";
if (isset($_SESSION['MM_Username'])) {
  $getRelations_userid = $_SESSION['MM_Username'];
}
$$getRelations_idprojects = "-1";
if (isset($_GET['idprojects'])) {
  $getRelations_idprojects = $_GET['idprojects'];
}

mysql_select_db($database_sdmarket, $sdmarket);
$query_checkRelations = sprintf("SELECT projectrelation.userid FROM projectrelation JOIN user_profile ON projectrelation.userid = user_profile.userid WHERE user_profile.email = %s", GetSQLValueString($getRelations_userid, "text"));
$checkRelations = mysql_query($query_checkRelations, $sdmarket) or die(mysql_error());
$row_checkRelations = mysql_fetch_assoc($checkRelations);
$totalRows_checkRelations = mysql_num_rows($checkRelations);

mysql_select_db($database_sdmarket, $sdmarket);
$query_getRelations = sprintf("SELECT projectrelation.userid, projectrelation.idprojects, projectrelation.role FROM projectrelation JOIN user_profile ON projectrelation.userid = user_profile.userid WHERE projectrelation.idprojects = %s AND user_profile.email = %s", GetSQLValueString($getRelations_idprojects, "int"),GetSQLValueString($getRelations_userid, "text"));
$getRelations = mysql_query($query_getRelations, $sdmarket) or die(mysql_error());
$row_getRelations = mysql_fetch_assoc($getRelations);
$totalRows_getRelations = mysql_num_rows($getRelations);

mysql_select_db($database_sdmarket, $sdmarket);
$query_updateViews = sprintf("UPDATE projects SET views=views+1 WHERE idprojects=%s", GetSQLValueString($currentproject_getStudents, "int"));
$updateViews = mysql_query($query_updateViews, $sdmarket) or die(mysql_error());

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Senior Design Marketplace | View Project</title>
<style type="text/css">
body,td,th {
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 14px;
}
#apDiv1 {
	position:absolute;
	left:640px;
	top:16px;
	width:500px;
	height:701px;
	z-index:1;
}
#apDiv2 {
	position:absolute;
	width:278px;
	height:702px;
	z-index:2;
	left: 1169px;
	top: 17px;
}
</style>
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

</head>

<body>
<div id="apDiv1">
  <h1><strong>Advisor(s)</strong></h1>


<!--//-------------------------BEGIN "DISPLAY RESULTS IN ARRAY" SCRIPT-------------------->
<?php
//first check to see if there is anything in the recordset....
if (mysql_num_rows($getAdvisors)==0) {echo 'There are currently no advisors assigned to this project.';}

//else, begin the script to display the results in an array....
else {
	
$count_advisors = mysql_num_rows($getAdvisors);
?>

<table border="0" align="left" cellpadding="10"> 
<tr>
<?php
$i = 1;

do {

?>
<td align="center" valign="middle"><a href="viewprofile.php?userid=<?php echo $row_getAdvisors['userid']; ?>" target="mainFrame"><img src="images\profileupload\<?php echo $row_getAdvisors['image']; ?>"width="128" border="1"></a>
<p> <strong><?php echo $row_getAdvisors['firstname'];?></strong>  <strong><?php echo $row_getAdvisors['lastname'];?></strong><br />
  <?php echo $row_getAdvisors['email'];?><br />
  <?php echo $row_getAdvisors['major'];?><br /></td>

<?php 
if ($i%3) { 
} else { 
	if ($i >= $count_advisors) { 
	} else { 
		echo "</tr><tr>"; 
			} 
	} 
$i++; 
} 
while($row_getAdvisors = mysql_fetch_assoc($getAdvisors));
?> 
</tr> 
</table> 
<?php } ?>
<!--//-------------------------END "DISPLAY RESULTS IN ARRAY" SCRIPT-------------------->

<h1>&nbsp;</h1>
<h1>&nbsp;</h1>
<h1>&nbsp;</h1>
<h1>&nbsp;</h1>
<h1 align="left">&nbsp;</h1>
<h1 align="left"><strong>  Students</strong></h1>

<!--//-------------------------BEGIN "DISPLAY RESULTS IN ARRAY" SCRIPT-------------------->
<?php
//first check to see if there is anything in the recordset....
if (mysql_num_rows($getStudents)==0) {echo 'There are currently no students assigned to this project.';}

//else, begin the script to display the results in an array....
else {

$count_students = mysql_num_rows($getStudents);
?>

<table border="0" align="left" cellpadding="10"> 
<tr>
<?php
$i = 1;

do {

?>
<td align="center" valign="middle"><a href="viewprofile.php?userid=<?php echo $row_getStudents['userid']; ?>" target="mainFrame"><img src="images\profileupload\<?php echo $row_getStudents['image']; ?>"width="128" border="1"></a>
<p> <strong><?php echo $row_getStudents['firstname'];?></strong>  <strong><?php echo $row_getStudents['lastname'];?></strong><br />
  <?php echo $row_getStudents['email'];?><br />
  <?php echo $row_getStudents['major'];?><br />
</p></td>

<?php 
if ($i%3) { 
} else { 
	if ($i >= $count_students) { 
	} else { 
		echo "</tr><tr>"; 
			} 
	} 
$i++; 
} 
while($row_getStudents = mysql_fetch_assoc($getStudents));
?> 
</tr> 
</table> 
<?php } ?>
<!--//-------------------------END "DISPLAY RESULTS IN ARRAY" SCRIPT-------------------->

  
  
</div>
<table width="600">
  <tr>
    <td height="29" align="left"><h1><?php echo $row_getViewproject['title']; ?> <br />
<img src="images\<?php echo $row_getViewproject['image']; ?>" height="29"/></h1></td>
  </tr>
</table>
<div id="apDiv2">
  <h1><strong>Comments</strong></h1>
  <?php 
  
//first check to see if there is anything in the recordset....
if (mysql_num_rows($getComment)==0) {echo 'No one has commented on this project.<br /> Be the first!';}

//else, begin the script to display the comments....
else {
	do { ?>
    <table width="100%" border="0" cellpadding="2">
      <tr>
        <td align="center" width="45" valign="middle"><img src="images\profileupload\<?php echo $row_getComment['image']; ?>"width="45" border="1" align="absmiddle">
        <td rowspan="2" align="left" valign="top" halign="left"><strong><?php echo $row_getComment['firstname']; ?>&nbsp;<?php echo $row_getComment['lastname']; ?></strong>
          <p><?php echo $row_getComment['comment']; ?></p></td>
      </tr>
      <tr>
        <td align="center" valign="middle">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align="center" valign="middle"><hr /></td>
      </tr>
    </table>
    
    <?php } while ($row_getComment = mysql_fetch_assoc($getComment)); 
}?>
   <p><form id="form_comment" name="form_comment" method="POST" action="<?php echo $editFormAction; ?>" onsubmit="return confirm('Are you sure you wish to post this comment?')">
      <label for="comment"></label>
      <input type="text" name="comment" id="comment" />
      <input type="submit" name="Comment" id="Comment" value="Post Comment" />
      <input type="hidden" name="MM_insert" value="form_comment" />
      <input type="hidden" name="idprojects" id="idprojects" value="<?php echo $row_getViewproject['idprojects']; ?>"/>
      <input type="hidden" name="userid" id="userid" value="<?php echo $row_getRole['userid']; ?>"/>
   </form> </p>
</div>


<?php if ($row_getLikes['userid'] == '') { ?>

<form id="form_likes" name="form_likes" method="post" action="<?php echo $editFormAction; ?>">
  <p>
    <input name="likes_projectid" type="hidden" id="likes_projectid" value="<?php echo $row_getViewproject['idprojects']; ?>" />
    <input name="likes_userid" type="hidden" id="likes_userid" value="<?php echo $row_getRole['userid']; ?>" />
  </p>
  <p>
    <input type="submit" name="Submit" id="button" value="Like"/>
  <img src="images/like.png" width="18" height="18" /></p>
  <input type="hidden" name="MM_insert" value="form_likes" />
</form>

<?php } 
		else { ?>

<form id="form_unlike" name="form_unlike" method="post" action="<?php echo $editFormAction; ?>">
  <p>
    <input name="likes_projectid" type="hidden" id="likes_projectid" value="<?php echo $row_getViewproject['idprojects']; ?>" />
    <input name="likes_userid" type="hidden" id="likes_userid" value="<?php echo $row_getRole['userid']; ?>" />
  </p>
  <p>
    <input type="submit" name="Submit" id="button" value="Unlike"/>
  <img src="images/like.png" width="18" height="18" /></p>
  <input type="hidden" name="MM_update" value="form_unlike" />
</form>

<?php } ?>

<p><img src="images/projectupload/<?php echo $row_getViewproject['photo']?>" height="256" align="top" /></p>
<p>

  <?php
	if ($row_getRelations['role'] == "0"): { echo "You have a pending application to this project."; } 
	
	elseif ($row_getRelations['role'] == "1"): { echo "You are a <b>project team member</b>."; } 
	
	elseif ($row_getRelations['role'] == "2"): { echo "You are an <b>advisor</b> for this project."; } 

	elseif ($row_getRole['role'] == 1 && $row_checkRelations['userid'] == '' && $row_getViewproject['status'] == 2): { ?>
<form id="form_apply" name="form_apply" method="POST" action="<?php echo $editFormAction; ?>" onsubmit="return confirm('Are you sure you wish to apply to become a member of this project?')">
 			<input type="submit" name="apply" id="apply" value="Apply" />
  			<input type="hidden" name="apply_userid" id="apply_userid" value="<?php echo $row_getRole['userid']; ?>"/>
            <input type="hidden" name="apply_idprojects" id="apply_idprojects" value="<?php echo $row_getViewproject['idprojects']; ?>"/>
            <input type="hidden" name="apply_role" id="apply_role" value="0"/>
            <input type="hidden" name="MM_insert" value="form_apply" />
</form> 
  <?php  } 
	
	elseif ($row_getRole['role'] == 2 && $row_getViewproject['status'] == 1): { ?>
		<form id="form_approve" name="form_approve" method="POST" action="<?php echo $editFormAction; ?>" onsubmit="return confirm('Are you sure you want to APPROVE this project?')" >
  			<input type="submit" name="approve" id="approve" value="Approve" /> this project and <b>become an advisor</b>!
            <input type="hidden" name="approve_userid" id="approve_userid" value="<?php echo $row_getRole['userid']; ?>" />
            <input type="hidden" name="approve_idprojects" id="approve_idprojects" value="<?php echo $row_getViewproject['idprojects']; ?>" />
            <input type="hidden" name="approve_role" id="approve_role" value="2" />
            <input type="hidden" name="MM_insert" value="form_approve" />
  		</form> 
        <form id="form_delete" name="form_delete" method="POST" action="<?php echo $editFormAction; ?>" onsubmit="return confirm('Are you sure you want to DELETE this project? (This action cannot be undone!)')" >
        	<input type="submit" name="delete" id="delete" value="Delete"  /> this project and <b>permanently remove it from the marketplace</b>.
            <input type="hidden" name="delete_idprojects" id="delete_idprojects" value="<?php echo $row_getViewproject['idprojects']; ?>" />
            <input type="hidden" name="MM_delete" value="form_delete"  />
        </form>
  <?php }
  
  	else: {}
  
  	endif; ?>
  

</p>
<table width="592">
  <tr>
    <td colspan="2"><hr /></td>
  <tr>
    <td width="74" align="left"><strong>Summary:</strong></td>
    <td width="455" align="left"><?php echo $row_getViewproject['summary']; ?></td>
  </tr>
</table>
<table width="590">
  <tr>
    <td colspan="2" align="left"><strong>Team Size:</strong></td>
    <td width="356">&nbsp;</td>
  </tr>
<?php if ($row_getViewproject['sizeunknown'] == ' ') {?>
  <tr>
    <td width="17" align="right">&nbsp;</td>
    <td width="201" align="left">Min Team Size:</td>
    <td><?php echo $row_getViewproject['minsize']; ?></td>
  </tr>
  <tr>
    <td align="right">&nbsp;</td>
    <td align="left">Max Team Size:</td>
    <td><?php echo $row_getViewproject['maxsize']; ?></td>
  </tr>
<?php }
elseif ($row_getViewproject['sizeunknown'] == 'X') {?>
  <tr>
    <td align="right">&nbsp;</td>
    <td align="left">Undecided</td>
    <td>&nbsp;</td>
  </tr>
<?php }?>
</table>
<br />
<table width="590">
  <tr>
    <td colspan="2" align="left"><strong>Requested Majors:</strong></td>
    <td width="356">&nbsp;</td>
  </tr>
<?php if ($row_getViewproject['BTqty'] != 0) {?>
  <tr>
    <td width="17" align="right">&nbsp;</td>
    <td width="201" align="left">Business & Technology:</td>
    <td align="left"><?php echo $row_getViewproject['BTqty']; ?></td>
  </tr>
<?php } 
if ($row_getViewproject['BMEqty'] != 0) {?>
  <tr>
    <td align="right">&nbsp;</td>
    <td align="left">Biomedical Engineers:</td>
    <td align="left"><?php echo $row_getViewproject['BMEqty']; ?></td>
  </tr>
<?php } 
if ($row_getViewproject['ChemEqty'] != 0) {?>
  <tr>
    <td align="right">&nbsp;</td>
    <td align="left">Chemical Engineers:</td>
    <td align="left"><?php echo $row_getViewproject['ChemEqty']; ?></td>
  </tr>
<?php } 
if ($row_getViewproject['CEqty'] != 0) {?>  
  <tr>
    <td align="right">&nbsp;</td>
    <td align="left">Civil Engineers:</td>
    <td align="left"><?php echo $row_getViewproject['CEqty']; ?></td>
  </tr>
<?php } 
if ($row_getViewproject['CPEqty'] != 0) {?>
  <tr>
    <td align="right">&nbsp;</td>
    <td align="left">Computer Engineers:</td>
    <td align="left"><?php echo $row_getViewproject['CPEqty']; ?></td>
  </tr>
<?php } 
if ($row_getViewproject['EEqty'] != 0) {?>
  <tr>
    <td align="right">&nbsp;</td>
    <td align="left">Electrical Engineers:</td>
    <td align="left"><?php echo $row_getViewproject['EEqty']; ?></td>
  </tr>
<?php } 
if ($row_getViewproject['EnvEqty'] != 0) {?>
  <tr>
    <td align="right">&nbsp;</td>
    <td align="left">Environmental Engineers:</td>
    <td align="left"><?php echo $row_getViewproject['EnvEqty']; ?></td>
  </tr>
<?php } 
if ($row_getViewproject['EMqty'] != 0) {?>
  <tr>
    <td align="right">&nbsp;</td>
    <td align="left">Engineering Managers:</td>
    <td align="left"><?php echo $row_getViewproject['EMqty']; ?></td>
  </tr>
<?php } 
if ($row_getViewproject['ISqty'] != 0) {?>
  <tr>
    <td align="right">&nbsp;</td>
    <td align="left">Information Systems:</td>
    <td align="left"><?php echo $row_getViewproject['ISqty']; ?></td>
  </tr>
<?php } 
if ($row_getViewproject['MEqty'] != 0) {?>
  <tr>
    <td align="right">&nbsp;</td>
    <td align="left">Mechanical Engineers:</td>
    <td align="left"><?php echo $row_getViewproject['MEqty']; ?></td>
  </tr>
<?php } 
if ($row_getViewproject['NEqty'] != 0) {?>
  <tr>
    <td align="right">&nbsp;</td>
    <td align="left">Naval Engineers:</td>
    <td align="left"><?php echo $row_getViewproject['NEqty']; ?></td>
  </tr>
<?php } 
if ($row_getViewproject['SYSqty'] != 0) {?>
  <tr>
    <td align="right">&nbsp;</td>
    <td align="left">Systems Engineers:</td>
    <td align="left"><?php echo $row_getViewproject['SYSqty']; ?></td>
  </tr>
<?php } ?>
</table>
<br />
<table width="592">
  <tr>
    <td colspan="2" align="left" valign="top"><strong>Project Description:</strong></td>
  </tr>
  <tr>
  	<td width="17">&nbsp;</td>
    <td width="575"><?php echo $row_getViewproject['description']; ?></td>
</table>
<br />
<table width="600">
<?php if ($row_getViewproject['attachment'] == ''){
}
else  { ?>
  <tr>
    <td width="217" align="left"><strong>Additional Documentation:</strong></td>
    <td width="371"><a href="projectattach/<?php echo $row_getViewproject['attachment']; ?>">Click to download</a></td>
  </tr>
  <?php } ?>
</table>
<br />
<table width="600">
  <tr>
    <td width="217" align="left"><strong>Created by:</strong></td>
    <td width="371"><a href="viewprofile.php?userid=<?php echo $row_getViewproject['userid'];?>"><?php echo $row_getViewproject['firstname'];?> <?php echo $row_getViewproject['lastname']; ?></a></td>
  </tr>
  <tr>
    <td width="217" align="left"><strong>Created on:</strong></td>
    <td><?php echo $row_getViewproject['created']; ?></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($getViewproject);

mysql_free_result($getRole);

mysql_free_result($getLikes);

mysql_free_result($getStudents);

mysql_free_result($getAdvisors);

mysql_free_result($getRelations);

mysql_free_result($getComment);
?>
