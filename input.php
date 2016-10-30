<h2>Add info!</h2>
<p>Page to input information</p>

<h3>Actors</h3>
<p>Input actor information.</p>

<form method="GET" action="<?php $_PHP_SELF ?>">
  First name: <input type="text" name="first" />
  Last name: <input type="text" name="last" />
  Male <input type="radio" name="sex" value="Male" />
  Female <input type="radio" name="sex" value="Female" />
  <br />
  Date of birth: <input type="date" name="dob" />
  Date of death: <input type="date" name="dod" />
  <br /><br />
  <input type="submit" value="Submit"/>
</form>

<hr>

<h3>Directors</h3>
<p>Input director information.</p>

<form method="GET" action="<?php $_PHP_SELF ?>">
  First name: <input type="text" name="first-name" />
  Last name: <input type="text" name="last-name" />
  <br />
  Date of birth: <input type="date" name="dob" />
  Date of death: <input type="date" name="dod" />
  <br /><br />
  <input type="submit" value="Submit"/>
</form>

<hr>

<h3>Movies</h3>
<p>Input movie information.</p>

<form method="GET" action="<?php $_PHP_SELF ?>">
  Title: <input type="text" name="title" />
  Year: <input type="number" name="year" />
  <br />
  MPAA rating: <input type="number" name="rating" />
  Company: <input type="text" name="company" />
  <br />
  Genre:<br />
  <input type="checkbox" name="genre" value="Action" />Action<br />
  <input type="checkbox" name="genre" value="Adult" />Adult<br />
  <input type="checkbox" name="genre" value="Adventure" />Adventure<br />
  <input type="checkbox" name="genre" value="Animation" />Animation<br />
  <input type="checkbox" name="genre" value="Comedy" />Comedy<br />
  <input type="checkbox" name="genre" value="Crime" />Crime<br />
  <input type="checkbox" name="genre" value="Documentary" />Documentary<br />
  <input type="checkbox" name="genre" value="Drama" />Drama<br />
  <input type="checkbox" name="genre" value="Family" />Family<br />
  <input type="checkbox" name="genre" value="Fantasy" />Fantasy<br />
  <input type="checkbox" name="genre" value="Horror" />Horror<br />
  <input type="checkbox" name="genre" value="Nusical" />Musical<br />
  <input type="checkbox" name="genre" value="Nystery" />Mystery<br />
  <input type="checkbox" name="genre" value="Romance" />Romance<br />
  <input type="checkbox" name="genre" value="Sci-fi" />Sci-Fi<br />
  <input type="checkbox" name="genre" value="Short" />Short<br />
  <input type="checkbox" name="genre" value="Thriller" />Thriller<br />
  <input type="checkbox" name="genre" value="War" />War<br />
  <input type="checkbox" name="genre" value="Western" />Western<br />
  <br />
  <input type="submit" value="Submit"/>
</form>

<hr>

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
    $db = new mysqli('localhost', 'cs143', '', 'TEST');
    if($db->connect_errno > 0){
      die('Unable to connect to database [' . $db->connect_error . ']');
    }
    else {
      console_log("connected to db");
    }

    // Prepare INSERT statement
    $statement = $db->prepare("INSERT INTO Actor VALUES(?, ?, ?, ?, ?, ?)");
    // Date of death is not required. If alive, should have no input value and set as NULL.
    $dod = (isset($_GET["dod"])) ? $_GET["dod"] : null;

    // Get next id from MaxPersonID
    $id_query = "SELECT id FROM MaxPersonID";
    $id = $db->query($id_query)->fetch_assoc()['id'] + 1;
    console_log("id time");
    console_log($id);

    // Bind params to $_GET and such
    $rs = $statement->bind_param('isssss', $id, $_GET['first'], $_GET['last'], $_GET['sex'], $_GET['dob'], $dod);
    console_log("after bind");
    console_log($rs);

    $test_execute = false;

    // Execute statement
    if ($test_execute && $statement->execute()) {
      console_log("Insert Actor Success");
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