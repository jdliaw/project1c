<?php
  include "navbar.php";
?>

<h2>Add a Movie Review</h2>

<?php
  function console_log( $data ){
    echo '<script>';
    echo 'console.log('. json_encode( $data ) .')';
    echo '</script>';
  }
  // Populate dropdown with existing movies
  $get_movies = "SELECT DISTINCT id, title, year FROM Movie ORDER BY title ASC";

  // Connect to db
  $db = new mysqli('localhost', 'cs143', '', 'TEST');
  if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
  }
  else {
    console_log("connected to db");
  }

  // Run query
  if ($movies_rs = $db->query($get_movies)) {
    console_log("Get movies success");
  }

  $db->close();
?>

<form method="GET" action="<?php $_PHP_SELF ?>">
  Name: <input type="text" name="name" /><br />
  Movie: <select name="movie">
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
  </select><br />
  Rating: (out of 5)
  1 <input type="radio" name="rating" value="1" />
  2 <input type="radio" name="rating" value="2" />
  3 <input type="radio" name="rating" value="3" />
  4 <input type="radio" name="rating" value="4" />
  5 <input type="radio" name="rating" value="5" />
  <br />
  Comment:<br />
  <textarea name="comment" cols="60" rows="8"></textarea><br />
  <input type="submit" value="Submit"/>
</form>

<?php
  $required_present = true;
  // Check all required fields are entered
  $check_array = array('name', 'movie', 'rating');
  foreach($check_array as $key) {
    if (!isset($_GET[$key])) {
      // TODO: Some kind of error message if not all required fields entered.
      $required_present = false;
      break;
    }
  }

  // Connect to db
  $db = new mysqli('localhost', 'cs143', '', 'TEST');
  if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
  }
  else {
    console_log("connected to db");
  }

  // Execute statement if parameters present
  if ($required_present) {
    console_log('hello');

    // Prepare INSERT statement
    $statement = $db->prepare("INSERT INTO Review VALUES(?, ?, ?, ?, ?)");

    // Get timestamp
    $date = new DateTime();
    $timestamp = date('Y-m-d H:i:s',$date->getTimestamp());
    console_log($timestamp);

    // Bind params to $_GET and such
    $rs = $statement->bind_param('ssiis', $_GET['name'], $timestamp, $_GET['movie'], $_GET['rating'], $_GET['comment']);

    $test_execute = true;

    // Execute statement
    if ($test_execute && $statement->execute()) {
      console_log("Insert Review Success");
    }
    else {
      console_log("Failed to insert to Review");
    }
  }
?>