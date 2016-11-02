<?php
  include "navbar.php";
?>

<link href="add-record.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">

<div class="input">
  <h2>Add Actor Roles in Movies</h2>

<?php
  function console_log( $data ){
    echo '<script>';
    echo 'console.log('. json_encode( $data ) .')';
    echo '</script>';
  }
  // Populate dropdown with existing actors and movies
  $get_actors = "SELECT DISTINCT id, first, last, dob FROM Actor ORDER BY first ASC";
  $get_movies = "SELECT DISTINCT id, title, year FROM Movie ORDER BY title ASC";

  // Connect to db
  $db = new mysqli('localhost', 'cs143', '', 'TEST');
  if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
  }
  else {
    console_log("connected to db");
  }

  // Run queries
  if ($actors_rs = $db->query($get_actors)) {
    console_log("Get actors success");
  }
  if ($movies_rs = $db->query($get_movies)) {
    console_log("Get movies success");
  }

  $db->close();
?>

  <form method='GET' action="<?php $_PHP_SELF ?>">
    <b>Actor:</b><br />
      <select name="actor">
        <option value=" "></option>
        <?php
          // Populate dropdown with existing actors
          if ($actors_rs) {
            // Echo each result as a select option
            while ($row = $actors_rs->fetch_assoc()) {
              echo "<option value='{$row["id"]}'>{$row['first']} {$row['last']} ({$row["dob"]})</option>";
            }
          }
          $actors_rs->free();
        ?>
      </select>
    <br />
    <b>Movie:</b><br />
      <select name="movie">
        <option value=" "></option>
        <?php
          // Populate dropdown with existing movies
          if ($movies_rs) {
            // Echo each result as a select option
            while ($row = $movies_rs->fetch_assoc()) {
              echo "<option value='{$row["id"]}'>{$row["title"]} ({$row["year"]})</option>";
            }
          }
          $movies_rs->free();
        ?>
      </select>
    <br />
    <b>Role:</b><br />
    <input type="text" name="role" class="text-input"/><br /><br />
    <input type="submit" value="Submit" class="button"/>
  </form>
</div>

<?php
  $required_present = true;
  // Check all required fields are entered
  $check_array = array('actor', 'movie', 'role');
  foreach($check_array as $key) {
    if (!isset($_GET[$key])) {
      // TODO: Some kind of error message if not all required fields entered.
      $required_present = false;
      break;
    }
    else {
      console_log($_GET[$key]);
    }
  }

  if ($required_present) {
    // Connect to db
    $db = new mysqli('localhost', 'cs143', '', 'TEST');
    if($db->connect_errno > 0){
      die('Unable to connect to database [' . $db->connect_error . ']');
    }
    else {
      console_log("connected to db");
    }

    // Prepare statement
    $statement = $db->prepare("INSERT INTO MovieActor VALUES(?, ?, ?)");
    $rs = $statement->bind_param("iis", $_GET['movie'], $_GET['actor'], $_GET['role']);

    $test_execute = false;

    // Execute statement
    if ($test_execute && $statement->execute()) {
      console_log("Insert MovieActor Success");
    }
    else {
      console_log("Failed to insert to MovieActor");
    }
    // Close connection
    $db->close();
  }
?>


