<?php # assign_netbooks.php

$page_title = 'Assign Mobile Devices';
include ('../../includes/header_district_computers.html');


if (isset($_POST['submitted']) ) {				// START SUBMIT, MUST CLOSE
  $errors = array();

if (empty($_POST['model'])) {					// START ERROR COLLECTION
  $errors[] = 'You must enter a model.';
} else {
  $modelid = $_POST['model'];	
}

//If assigned to a person, set the teacher ID.

if (isset($_POST['person']) ) {
		$personid = $_POST['person'];
		echo "personid is $personid\n";
		} 


if ( !empty($_POST['cname'] ) ) {
	$cname = $_POST['cname'] ;
	} else {
	echo '<p>You did not enter a computer name so it will be set to UNKNOWN. Please correct ASAP.</p>';
}

if (empty($_POST['stag'])) {	
  $errors[] = 'You must enter a service tag or serial number.';
} else {
  $stag = $_POST['stag'];	
}

if (empty($errors)) {						// START IF EMPTY ERRORS, MUST CLOSE



require_once ('../../../mysql_connect_inventory.php');


//CHECK FOR DUPLICATE SERVICE TAG

$query = "SELECT computer_id FROM computers WHERE ( service_tag='$stag') ";
$result = mysql_query($query);
if (mysql_num_rows($result)==0) {					// START NO DUPS, MUST CLOSE
	
echo "<p>This computer is not a duplicate</p>";


// Determine school ID 

$query= "SELECT last_name,location_id FROM teachers,locations WHERE teachers.teacher_id='$personid' AND teachers.school_id=locations.school_id AND locations.room_name_id=1";
$result = mysql_query($query) ;
if ($result) {
	$row = mysql_fetch_array($result, MYSQL_ASSOC) ;
	$location_id = $row['location_id'] ;
	$last_name = $row['last_name'] ;
	} else {
 	echo '<h1 id="mainhead">System Error</h1>
  	<p class="error">Your location or name info could not be determined due to a system error.</p>';
  	echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';
  	echo '</body></html>';
  	exit();
}



  $query = "INSERT INTO computers (computer_name,service_tag,model_id,teacher_id,location_id) VALUES
  ('$cname','$stag','$modelid','$personid','$location_id')";

$result = mysql_query($query); 
if ($result) {
  
  echo '<h1 id="mainhead">Thank you!</h1>
  <p>Your computer model and associated data went through--no duplicates.</p><p>
  <br /></p>';
  
  $body = "A new mobile device of type ID '$modelid' and service tag '$stag' has been assigned to Mr/Ms $last_name .\n\n" ;
	mail ('user@localhost', 'Change in inventory database', $body, 'From: assign_netbooks.php');
  }


 else {
  echo '<h1 id="mainhead">System Error</h1>
  <p class="error">Your model data could not be entered due to a system error. No data was sent.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';

  echo '</body></html>';

  exit();
}

} else {   						//CLOSE NOT A DUPLICATE
	echo "<p>Sorry, that service tag or computer name is already in the database.</p>";
	echo "<p>To transfer device, please delete existing entry and re-add with this form.</p>";
	mysql_close();  
	exit();
	
}

} else      { 						//  Close If empty errors
  	echo '<h1 id="mainhead">Error!</h1>
  	<p class="error">The following error(s) occurred:<br />';
  	foreach ($errors as $msg) {
  		echo " -$msg<br />\n";
  		}
  	echo '</p><p>Please try again.</p><p><br /></p>' ;
	mysql_close();  
	exit();
}

							//close the submit
}


?>

<h2>Assign New Mobile Devices to People</h2>
<form action="assign_netbooks.php" method="post">
<fieldset><legend>Use This Form Only to Assign New Devices</legend>
<!--BEGIN SELECT COMPUTER MODEL  -->

<h3>Select Device Model</h3>
<?php


require_once ('../../../mysql_connect_inventory.php');

$query = "SELECT model,model_id,computer_type FROM computer_models,computer_types WHERE computer_models.ct_id=computer_types.ct_id AND (computer_types.ct_id=3 OR computer_types.ct_id=5 OR computer_types.ct_id=6 OR computer_types.ct_id=7) ORDER BY computer_models.model DESC" ;

$result = @mysql_query($query);


if ($result) {
 
  echo '<select name="model">';
  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  	echo '<option value="' . $row['model_id'] . '">' . ' ' . $row['model'] . ' ' . $row['computer_type'] . '</option>\\n';

  	}  echo '</select>'; 
	

mysql_free_result ($result);
} else {
  echo '<p class="error">The models could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';


mysql_close();  
exit();
 }

?>


<!-- END SELECT COMPUTER MODEL -->


<!--BEGIN SELECT PERSON  -->

<?php

require_once ('../../../mysql_connect_inventory.php');

//Identify Person

echo '<h3>Select a Person</h3>';

$query = "SELECT teacher_id, CONCAT(first_name, ' ', last_name) AS person FROM teachers ORDER BY last_name" ;

$result = @mysql_query($query);

if ($result) {
 
  echo '<select name="person">';
  while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  	echo '<option value="' . $row['teacher_id'] . '">' . $row['person'] . '</option>\\n';

  	}  echo '</select>'; 
	

mysql_free_result ($result);
} else {
  echo '<p class="error">People\'s names could not be retrieved. 
We apologize for any inconvenience.</p>';

  echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';


mysql_close();  
exit();
 }
?>

<p>Computer Name: <input type="text" name="cname" size="20" maxlength="24" value="<?php if 
(isset($_POST['cname'])) echo $_POST['cname'];
?>" /></p>


<p>Service Tag: <input type="text" name="stag" size="16" maxlength="16" value="<?php if 
(isset($_POST['stag'])) echo $_POST['stag'];
?>" /></p>


</fieldset>

<div align="center">  
<input type="submit" name="submit" value="Submit"/></div>
<input type="hidden" name="submitted" value="TRUE"/>
</form>


<?php
include ('../../includes/footer.html');
?>
