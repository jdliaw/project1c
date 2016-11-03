<?php
  include "navbar.php";
?>

<link href="add-record.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">

<div class="input">

  <h2>Add new Actor/Director</h2>

  <form method="GET" action="<?php $_PHP_SELF ?>">
    Actor <input type="radio" name="table" value="Actor" />
    Director <input type="radio" name="table" value="Director" /><br />
    <b>First name:</b> <input type="text" name="first" class="text-input"/><br />
    <b>Last name:</b> <input type="text" name="last" class="text-input"/><br />
    Male <input type="radio" name="sex" value="Male" />
    Female <input type="radio" name="sex" value="Female" />
    <br />
    <b>Date of birth:</b> <input type="date" name="dob" class="text-input"/><br />
    <b>Date of death:</b> <input type="date" name="dod" class="text-input"/>
    <br /><br />
    <input class="button" type="submit" value="Submit"/>
  </form>

</div>

<?php
  function console_log( $data ){
    echo '<script>';
    echo 'console.log('. json_encode( $data ) .')';
    echo '</script>';
  }

  $required_present = true;
  // Check all required fields are entered
  $check_array = array('first', 'last', 'sex', 'dob');
  foreach($check_array as $key) {
    if (!isset($_GET[$key])) {
      // TODO: Some kind of error message if not all required fields entered.
      $required_present = false;
      break;
    }
  }

  // Execute statement if parameters present
  if ($required_present) {
    // Connect to db
    $db = new mysqli('localhost', 'cs143', '', 'CS143');
    if($db->connect_errno > 0){
      die('Unable to connect to database [' . $db->connect_error . ']');
    }
    else {
      console_log("connected to db");
    }

    // Date of death is not required. If alive, should have no input value and set as NULL.
    $dod = (isset($_GET["dod"])) ? $_GET["dod"] : null;
    // Get next id from MaxPersonID
    $id_query = "SELECT id FROM MaxPersonID";
    $id = $db->query($id_query)->fetch_assoc()['id'] + 1;
    console_log($id);

    // Prepare INSERT statement and bind params
    if ($_GET["table"] == 'Actor') {
      $statement = $db->prepare("INSERT INTO Actor VALUES(?, ?, ?, ?, ?, ?)");
      $rs = $statement->bind_param('isssss', $id, $_GET['first'], $_GET['last'], $_GET['sex'], $_GET['dob'], $dod);
    }
    else { // Director
      $statement = $db->prepare("INSERT INTO Director VALUES(?, ?, ?, ?, ?)");
      $rs = $statement->bind_param('isssss', $id, $_GET['first'], $_GET['last'], $_GET['dob'], $dod); // No sex for Director
    }

    // Execute statement
    if ($statement->execute()) {
      console_log("Insert " . $_GET['table']. " Success");
      // Now update MaxPersonID
      $update_id = "UPDATE MaxPersonID SET id=$id";
      console_log($update_id);
      $rs = $db->query($update_id);
      console_log($rs);
    }
    else
      console_log("Failed to insert tuple");
  }
?>