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

//Determine whether the user has uploaded a profile pic.
if ($_FILES['photo']['name'] == '') {
$photoname = 'DefaultProject.jpg';
}
else {
$photoname = $_FILES['photo']['name'];
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "formCreateproject")) {
  $insertSQL = sprintf("INSERT INTO projects (title, summary, `description`, minsize, maxsize, sizeunknown, BTqty, CEqty, CPEqty, EEqty, EnvEqty, MEqty, EMqty, NEqty, SYSqty, BMEqty, ChemEqty, ISqty, attachment, photo, createdby) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['title'], "text"),
                       GetSQLValueString($_POST['summary'], "text"),
                       GetSQLValueString($_POST['description'], "text"),
                       GetSQLValueString($_POST['minsize'], "int"),
                       GetSQLValueString($_POST['maxsize'], "int"),
                       GetSQLValueString($_POST['sizeunknown'], "text"),
					   GetSQLValueString($_POST['BTqty'], "int"),
                       GetSQLValueString($_POST['CEqty'], "int"),
                       GetSQLValueString($_POST['CPEqty'], "int"),
                       GetSQLValueString($_POST['EEqty'], "int"),
                       GetSQLValueString($_POST['EnvEqty'], "int"),
                       GetSQLValueString($_POST['MEqty'], "int"),
                       GetSQLValueString($_POST['EMqty'], "int"),
                       GetSQLValueString($_POST['NEqty'], "int"),
                       GetSQLValueString($_POST['SYSqty'], "int"),
                       GetSQLValueString($_POST['BMEqty'], "int"),
                       GetSQLValueString($_POST['ChemEqty'], "int"),
                       GetSQLValueString($_POST['ISqty'], "int"),
                       GetSQLValueString($_FILES['attachment']['name'], "text"),
                       GetSQLValueString($photoname, "text"),
                       GetSQLValueString($_POST['createdby'], "text"));
					   
//Added for attachment uploads//

$targetattach = "projectattach/";  //This is the directory where attachments will be saved//
$targetattach = $targetattach . basename($_FILES['attachment']['name']);   //change the attachment and name to whatever your database fields are called//

 if(move_uploaded_file($_FILES['attachment']['tmp_name'], $targetattach)) 
 { 
 
 //And confirms it has worked//
 echo "The file ". basename( $_FILES['uploadedfile']['name']). " has been uploaded, and your information has been added to the directory"; 
 } 
 else { 
 
 //Gives error if not correct// 
 echo "Sorry, there was a problem uploading your attachment."; 
 }					
 
 //Added for picture uploads//	

$target = "images/projectupload/";  //This is the directory where images will be saved//
$target = $target . basename( $_FILES['photo']['name']);   //change the photo and name to whatever your database fields are called//

if(move_uploaded_file($_FILES['photo']['tmp_name'], $target)) 
 { 
 
 //And confirms it has worked//
 echo "The file ". basename( $_FILES['uploadedfile']['name']). " has been uploaded, and your information has been added to the directory"; 
 } 
 else { 
 
 //Gives error if not correct// 
 echo "Sorry, there was a problem uploading your image."; 
 }			   

  mysql_select_db($database_sdmarket, $sdmarket);
  $Result1 = mysql_query($insertSQL, $sdmarket) or die(mysql_error());

  $insertGoTo = "success_project.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$colname_getUserid = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_getUserid = $_SESSION['MM_Username'];
}
mysql_select_db($database_sdmarket, $sdmarket);
$query_getUserid = sprintf("SELECT userid FROM user_profile WHERE email LIKE %s", GetSQLValueString("%" . $colname_getUserid . "%", "text"));
$getUserid = mysql_query($query_getUserid, $sdmarket) or die(mysql_error());
$row_getUserid = mysql_fetch_assoc($getUserid);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Senior Design Marketplace | Create a Project</title>
<style type="text/css">
form {
	font-family: Arial, Helvetica, sans-serif;
}
h1 {
	font-family: Tahoma, Geneva, sans-serif;
	color: #900;
}
h4 {
	color: #F00;
}
body,td,th {
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 14px;
}
</style></head>

<body>
<h1>Create a Project</h1>
<div>
  <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="formCreateproject" id="formCreateproject">
  <table width="800">
      <tr>
        <td width="200" align="right">Project Title:</td>
        <td width="588"><h4>
            <input name="title" type="text" id="title" size="45" />
        </h4></td>
      </tr>
      <tr>
        <td align="right">Project Summary <br />
        (255 characters):</td>
        <td><h4>
              <textarea name="summary" cols="50" rows="7" id="summary" maxlength="255">Enter a brief summary of your project here.</textarea>
        </h4></td>
      </tr>
      <tr>
        <td align="right">Minimum Team Size:</td>
        <td><input name="minsize" type="text" id="minsize" size="2" maxlength="2" disabled="true"/></td>
      </tr>
      <tr>
        <td align="right">Maximum Team Size:</td>
        <td><input name="maxsize" type="text" id="maxsize" size="2" maxlength="2" disabled="true"/></td>
      </tr>
      <tr>
        <td align="right">Team Size Undecided?:</td>
        <td><table width="200">
          <tr>
            <td><label>
              <input name="sizeunknown" type="radio" id="sizeunknown_0" onclick="minsize.disabled=true, maxsize.disabled=true" value="X" checked="checked" />
              Yes</label></td>
          </tr>
          <tr>
            <td><label>
              <input name="sizeunknown" type="radio" id="sizeunknown_1" onclick="minsize.disabled=false, maxsize.disabled=false" value=" " />
              No</label></td>
          </tr>
        </table></td>
      </tr>
    </table>
  <p><strong>Requested Majors:</strong><br />
    Below, enter the <em><strong>minimum</strong></em> number of members you require from the various departments.</p>
    <table width="800">
      <tr>
        <td width="200" align="right">Business & Technology:</td>
        <td width="588"><input name="BTqty" type="text" id="BTqty" value="0" size="2" maxlength="2" /></td>
      </tr>
      <tr>
        <td align="right">Biomedical Engineers:</td>
        <td><input name="BMEqty" type="text" id="BMEqty" value="0" size="2" maxlength="2" /></td>
      </tr>
      <tr>
        <td align="right">Chemical Engineers:</td>
        <td><input name="ChemEqty" type="text" id="ChemEqty" value="0" size="2" maxlength="2" /></td>
      </tr>
      <tr>
        <td align="right">Computer Engineers:</td>
        <td><input name="CPEqty" type="text" id="CPEqty" value="0" size="2" maxlength="2" /></td>
      </tr>
      <tr>
        <td align="right">Civil Engineers:</td>
        <td><input name="CEqty" type="text" id="CEqty" value="0" size="2" maxlength="2" /></td>
      </tr>
      <tr>
        <td align="right">Electrical Engineers:</td>
        <td><input name="EEqty" type="text" id="EEqty" value="0" size="2" maxlength="2" /></td>
      </tr>
      <tr>
        <td align="right">Environmental Engineers:</td>
        <td><input name="EnvEqty" type="text" id="EnvEqty" value="0" size="2" maxlength="2" /></td>
      </tr>
      <tr>
        <td align="right">Engineering Managers:</td>
        <td><input name="EMqty" type="text" id="EMqty" value="0" size="2" maxlength="2" /></td>
      </tr>
      <tr>
        <td align="right">Mechanical Engineers:</td>
        <td><input name="MEqty" type="text" id="MEqty" value="0" size="2" maxlength="2" /></td>
      </tr>
      <tr>
        <td align="right">Naval Engineers:</td>
        <td><input name="NEqty" type="text" id="NEqty" value="0" size="2" maxlength="2" /></td>
      </tr>
      <tr>
        <td align="right">Systems Engineers:</td>
        <td><input name="SYSqty" type="text" id="SYSqty" value="0" size="2" maxlength="2" /></td>
      </tr>
      <tr>
        <td align="right">Information Systems Majors:</td>
        <td><input name="ISqty" type="text" id="ISqty" value="0" size="2" maxlength="2" /></td>
      </tr>
    </table>
    <p><strong>Detailed Description:</strong><br />
      Please enter a detailed description of your project below. <br />
      Keep in mind there will be opportunity at the end of the page to attach additional documentation you feel may be important to your project.<br />
      <textarea name="description" id="description" cols="100" rows="25"></textarea>
    </p>
    <table width="605">
      <tr>
        <td width="369" align="right"><label for="attachment">Attach Additional Documentation:</label></td>
        <td width="224"><input type="file" name="attachment" id="attachment" /></td>
      </tr>
      <tr>
        <td align="right">Submit a &quot;Profile Picture&quot; for your Project! :</td>
        <td><input type="file" name="photo" id="photo" /></td>
      </tr>
    </table>
    <p>
      <input name="createdby" type="hidden" id="createdby" value="<?php echo $_SESSION['MM_Username']; ?>" />
      <br />
    </p>
    <p>
      <input type="submit" name="submit" id="submit" value="Submit" />
    </p>
    <input type="hidden" name="MM_insert" value="formCreateproject" />
    <input type="hidden" name="MM_insert" value="formCreateproject" />
  </form>
</div>
</body>
</html>

