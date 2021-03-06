<?php #  - delete_projectors.php

//accessed through view_projectors2.php

$page_title = 'Delete Projector';
include ('../includes/header_projectors.html');

if (  (isset($_GET['id']))  && (is_numeric($_GET['id'])) )  {		//CHECK FOR CORRECT INPUT
	$id=$_GET['id'] ; 
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) {
	$id=$_POST['id'] ;
} else {
	echo '<h1 id="mainhead">
	Page Error</h1>
	<p class="error">This page has been accessed in error.</p><p><br /><br /></p>';
include ('../includes/footer.html');
exit();
}

  require_once ('../../mysql_connect_inventory.php');

if (isset($_POST['submitted'])) {					//OPEN SUBMITTED
	if ($_POST['sure'] == 'Yes' ) {					//START CONDITION 1
		$model = $_POST['model'];
		$school = $_POST['school'];
		$room = $_POST['room'];
		$query = "DELETE FROM projectors where projector_id=$id";
		$result = @mysql_query($query);
			if (mysql_affected_rows() == 1) {		//START CONDITION 2
				echo '<h1 id="mainhead">Delete a Projector</h1>
				<p>The projector has been deleted.</p>
				<p><br /><br /></p>';

	$body = "Projector with ID '$id' has been deleted.  Model '$model', school '$school', room '$room'. \n\n" ;
	mail ('user@localhost', 'Change in projectors database', $body, 'From: delete_projectors.php');



			} else  {

				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The projector could not be deleted due to a system error.</p>';
				echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>';
			}
									//CLOSE CONDITION 2

	} else {							// IF CONDITION 1 NOT MET
		echo '<h1 id="mainhead">Delete a Projector</h1>
		<p>The the projector has NOT been deleted.</p>
		<p><br /><br /></p>';
		}							//CLOSE CONDITION 1

} else { 				//CLOSE SUBMITTED  //OPEN NOT SUBMITTED (start submit form)

	$query = "SELECT CONCAT(mf, ' ',model) AS model,school,room_name,projector_id FROM manufacturers,projector_models,schools,room_names,locations,projectors WHERE projector_id=$id AND projectors.model_id=projector_models.model_id AND projector_models.mf_id=manufacturers.mf_id AND locations.location_id=projectors.location_id AND schools.school_id=locations.school_id AND room_names.room_name_id=locations.room_name_id ORDER BY room_names.room_name";

	$result = @mysql_query($query);

	if (mysql_num_rows($result) == 1) {				//OPEN CONDITION 3
		$row = mysql_fetch_array($result, MYSQL_NUM);
		echo '<h2>Delete a Projector</h2>
		<form action="delete_projector.php" method="post">
		
		<h3>Model: ' . $row[0] . '</h3>
		<h4>School: ' . $row[1] . '</h4>
		<h4>Room: ' . $row[2] . '</h4>
		<p>Are you sure you want to delete this projector?<br />

		<input type="radio" name="sure" value="Yes" />Yes

		<input type="radio" name="sure" value="No" checked="checked" />No	
		
		</p>

		<input type="submit" name="submit" value="Submit" /></p>
		<input type="hidden" name="submitted" value="TRUE" />

		<input type="hidden" name="id" value="' . $id . '" />
		<input type="hidden" name="model" value="' . $row[0] . '" />
		<input type="hidden" name="school" value="' . $row[1] . '" />
		<input type="hidden" name="room" value="' . $row[2] . '" />
		</form>';



	} else {							//CLOSE CONDITION 3 

		echo '<h1 id="mainhead">Page Error</h1>
		<p class="error">This page has been accessed in error '. $id . ' .</p>
		<p><br /><br /></p>';

		}

}										//CLOSE NOT SUBMITTED
mysql_close();
include ('../includes/footer.html');

?>	
				

			


