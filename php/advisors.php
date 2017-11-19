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

// *** Redirect if username exists
$MM_flag="MM_insert";
if (isset($_POST[$MM_flag])) {
  $MM_dupKeyRedirect="alreadyexist_advisor.php";
  $loginUsername = $_POST['email'];
  $LoginRS__query = sprintf("SELECT email FROM faculty_pending WHERE email=%s", GetSQLValueString($loginUsername, "text"));
  mysql_select_db($database_sdmarket, $sdmarket);
  $LoginRS=mysql_query($LoginRS__query, $sdmarket) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);

  //if there is a row in the database, the username was found - can not add the requested username
  if($loginFoundUser){
    $MM_qsChar = "?";
    //append the username to the redirect page
    if (substr_count($MM_dupKeyRedirect,"?") >=1) $MM_qsChar = "&";
    $MM_dupKeyRedirect = $MM_dupKeyRedirect . $MM_qsChar ."requsername=".$loginUsername;
    header ("Location: $MM_dupKeyRedirect");
    exit;
  }
}

$editFormAction = $_SERVER['PATH_INFO'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

//Determine whether the user has uploaded a profile pic.
if ($_FILES['image']['name'] == '') {
$imagename = 'DefaultUser.jpg';
}
else {
$imagename = $_FILES['image']['name'];
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO faculty_pending (`role`, firstname, middle, lastname, email, password, department, major, gradyear, skill1, skill2, skill3, skill4, skill5, resume, image) VALUES (%s, %s, %s, %s, %s, '".md5($_POST['password'])."', %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['role'], "int"),
                       GetSQLValueString($_POST['firstname'], "text"),
                       GetSQLValueString($_POST['middle'], "text"),
                       GetSQLValueString($_POST['lastname'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['department'], "text"),
					   GetSQLValueString($_POST['department'], "text"),
                       GetSQLValueString($_POST['gradyear'], "int"),
                       GetSQLValueString($_POST['skill1'], "text"),
                       GetSQLValueString($_POST['skill2'], "text"),
                       GetSQLValueString($_POST['skill3'], "text"),
                       GetSQLValueString($_POST['skill4'], "text"),
                       GetSQLValueString($_POST['skill5'], "text"),
                       GetSQLValueString($_FILES['resume']['name'], "text"),
                       GetSQLValueString($imagename, "text"));	
 

 //Added for resume uploads//
 $targetresume = "resumes/";  //This is the directory where resumes will be saved//
 $targetresume = $targetresume . basename($_FILES['resume']['name']);   //change the resume and name to whatever your database fields are called//

 if(move_uploaded_file($_FILES['resume']['tmp_name'], $targetresume)) 
 { 
 //And confirms it has worked//
 echo "The file ". basename( $_FILES['uploadedfile']['name']). " has been uploaded, and your information has been added to the directory"; 
 } 
 else { 
 //Gives error if not correct// 
 echo "Sorry, there was a problem uploading your resume."; 
 }		
 
 //Added for picture uploads//	
 $target = "images/profileupload/";  //This is the directory where images will be saved//
 $target = $target . basename($_FILES['image']['name']);   //change the resume and name to whatever your database fields are called//

 if(move_uploaded_file($_FILES['image']['tmp_name'], $target)) 
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

  $insertGoTo = "success_advisor.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_sdmarket, $sdmarket);
$query_getMajors = "SELECT major FROM majors ORDER BY major ASC";
$getMajors = mysql_query($query_getMajors, $sdmarket) or die(mysql_error());
$row_getMajors = mysql_fetch_assoc($getMajors);
$totalRows_getMajors = mysql_num_rows($getMajors);

mysql_select_db($database_sdmarket, $sdmarket);
$query_getGradyear = "SELECT gradyear FROM gradyear ORDER BY gradyear ASC";
$getGradyear = mysql_query($query_getGradyear, $sdmarket) or die(mysql_error());
$row_getGradyear = mysql_fetch_assoc($getGradyear);
$totalRows_getGradyear = mysql_num_rows($getGradyear);

mysql_select_db($database_sdmarket, $sdmarket);
$query_getDepts = "SELECT department FROM deptrelation ORDER BY department ASC";
$getDepts = mysql_query($query_getDepts, $sdmarket) or die(mysql_error());
$row_getDepts = mysql_fetch_assoc($getDepts);
$totalRows_getDepts = mysql_num_rows($getDepts);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Senior Design Marketplace | Create a Profile</title>
<script src="SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/register.css" rel="stylesheet" type="text/css" />
<style type="text/css">
body,td,th {
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 14px;
}
</style>
<script type="text/javascript">
function MM_validateForm() { //v4.0
  if (document.getElementById){
    var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
    for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=document.getElementById(args[i]);
      if (val) { nm=val.name; if ((val=val.value)!="") {
        if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
          if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
        } else if (test!='R') { num = parseFloat(val);
          if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
          if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
            min=test.substring(8,p); max=test.substring(p+1);
            if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
      } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
    } if (errors) alert('The following error(s) occurred:\n'+errors);
    document.MM_returnValue = (errors == '');
} }
</script>
</head>

<body>
<div>
  <h1>Faculty Registration</h1>
  <h3>Please fill out the registration form below. Your account will be reviewed and you will be notified with your default login credentials when the process is complete.</h3>
</div>
<div id="divRegister">
  <form action="<?php echo $editFormAction; ?>" method="post" enctype="multipart/form-data" name="form1" id="form1" onsubmit="return validateEmail();">
    <p>
      <label for="firstname">First Name:</label>
    <input name="firstname" type="text" id="firstname" tabindex="1" />
</p>
  <p>
    <label for="middle">Middle Initial:</label>
    <input name="middle" type="text" id="middle" tabindex="2" size="1" maxlength="1" />
  </p>
  <p>
    <label for="lastname">Last Name:</label>
    <input name="lastname" type="text" id="lastname" tabindex="3" />
  </p>
  <p>
    <label for="email">Stevens E-mail Address:</label>
    <input name="email" type="text" id="email" tabindex="4" />
  </p>
  <p><span id="sprypassword1">
    <label for="password4">Password:</label>
    <input name="password" type="password" id="password4" tabindex="5" />
  <span class="passwordRequiredMsg">A value is required.</span></span></p>
  <p><span id="spryconfirm1">
    <label for="password5">Re-type your Password:</label>
    <input name="password2" type="password" id="password5" tabindex="6" />
    <span class="confirmRequiredMsg">A value is required.</span><span class="confirmInvalidMsg">The values don't match.</span></span></p>
  <p>
    <label for="major">Department:</label>
    <select name="department" id="department" tabindex="7">
      <option value=" "></option>
      <?php
do {  
?>
      <option value="<?php echo $row_getDepts['department']?>"><?php echo $row_getDepts['department']?></option>
      <?php
} while ($row_getDepts = mysql_fetch_assoc($getDepts));
  $rows = mysql_num_rows($getDepts);
  if($rows > 0) {
      mysql_data_seek($getDepts, 0);
	  $row_getDepts = mysql_fetch_assoc($getDepts);
  }
?>
    </select>
  </p>
  <p>
    <label for="gradyear">Graduation Year:</label>
    <select name="gradyear" id="gradyear" tabindex="8">
      <option value=""></option>
      <?php
do {  
?>
      <option value="<?php echo $row_getGradyear['gradyear']?>"><?php echo $row_getGradyear['gradyear']?></option>
      <?php
} while ($row_getGradyear = mysql_fetch_assoc($getGradyear));
  $rows = mysql_num_rows($getGradyear);
  if($rows > 0) {
      mysql_data_seek($getGradyear, 0);
	  $row_getGradyear = mysql_fetch_assoc($getGradyear);
  }
?>
    </select>
  </p>
  <p>
    <label for="skill1">Skill 1:</label>
    <input name="skill1" type="text" id="skill1" tabindex="9"  />
  </p>
  <p>
    <label for="skill2">Skill 2:</label>
    <input name="skill2" type="text" id="skill2" tabindex="10" />
  </p>
  <p>
    <label for="skill3">Skill 3:</label>
    <input name="skill3" type="text" id="skill3" />
  </p>
  <p>
    <label for="skill4">Skill 4:</label>
    <input name="skill4" type="text" id="skill4" />
  </p>
  <p>
    <label for="skill5">Skill 5:</label>
    <input name="skill5" type="text" id="skill5" />
  </p>
  <p>
    <label for="file_resume">Attach your Resume:</label>
    <input name="resume" type="file" id="resume" />
  </p>
  <p>
    <label for="file_headshot2">Upload a Profile Pic:</label>
    <input name="image" type="file" id="image" />
  </p>
  <p>
    <input name="role" type="hidden" id="role" tabindex="50" value="2" />
  </p>
  <p>
    <input name="button_submit" type="submit" id="button_submit" tabindex="11" value="Submit" onclick="MM_validateForm('firstname','','R','lastname','','R','email','','R','password4','','R','password5','','R','skill1','','R');return document.MM_returnValue"/>
    <input type="reset" name="button_reset" id="button_reset" value="Reset" tabindex="12" />
  </p>
  <input name="MM_insert" type="hidden" tabindex="51" value="form1" />
  <input type="hidden" name="MM_insert" value="form1" />
  </form>
</div>
<p>&nbsp;</p>
<p>&nbsp;</p>
<script type="text/javascript">
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1");
var spryconfirm1 = new Spry.Widget.ValidationConfirm("spryconfirm1", "password4", {validateOn:["blur"]});

function validateEmail()
{
var x=document.forms["form1"]["email"].value;
var atpos=x.indexOf("@");
var dotpos=x.lastIndexOf(".");
var domain=x.indexOf("stevens.edu");
if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length || domain == -1 || domain == null)
  {
  alert("Not a valid e-mail address");
  return false;
  }
}

</script>
</body>
</html>
<?php
mysql_free_result($getMajors);

mysql_free_result($getDepts);

mysql_free_result($getGradyear);
?>
