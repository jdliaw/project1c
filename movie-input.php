<p>Page to input Movie information</p>
<a href="input.php">Add Actors/Directors</a>

<h3>Movies</h3>
<p>Input movie information.</p>

<form method="GET" action="<?php $_PHP_SELF ?>">
  Title: <input type="text" name="title" />
  Year: <input type="number" name="year" />
  <br />
  MPAA rating:
  <select name="rating">
    <option value="G">G</option>
    <option value="NC-17">NC-17</option>
    <option value="PG">PG</option>
    <option value="PG-13">PG-13</option>
    <option value="R">R</option>
  </select>
  Company: <input type="text" name="company" />
  <br />
  Genre:<br />
  <input type="checkbox" name="genre[]" id="genre" value="Action" />Action<br />
  <input type="checkbox" name="genre[]" id="genre" value="Adult" />Adult<br />
  <input type="checkbox" name="genre[]" id="genre" value="Adventure" />Adventure<br />
  <input type="checkbox" name="genre[]" id="genre" value="Animation" />Animation<br />
  <input type="checkbox" name="genre[]" id="genre" value="Comedy" />Comedy<br />
  <input type="checkbox" name="genre[]" id="genre" value="Crime" />Crime<br />
  <input type="checkbox" name="genre[]" id="genre" value="Documentary" />Documentary<br />
  <input type="checkbox" name="genre[]" id="genre" value="Drama" />Drama<br />
  <input type="checkbox" name="genre[]" id="genre" value="Family" />Family<br />
  <input type="checkbox" name="genre[]" id="genre" value="Fantasy" />Fantasy<br />
  <input type="checkbox" name="genre[]" id="genre" value="Horror" />Horror<br />
  <input type="checkbox" name="genre[]" id="genre" value="Nusical" />Musical<br />
  <input type="checkbox" name="genre[]" id="genre" value="Nystery" />Mystery<br />
  <input type="checkbox" name="genre[]" id="genre" value="Romance" />Romance<br />
  <input type="checkbox" name="genre[]" id="genre" value="Sci-fi" />Sci-Fi<br />
  <input type="checkbox" name="genre[]" id="genre" value="Short" />Short<br />
  <input type="checkbox" name="genre[]" id="genre" value="Thriller" />Thriller<br />
  <input type="checkbox" name="genre[]" id="genre" value="War" />War<br />
  <input type="checkbox" name="genre[]" id="genre" value="Western" />Western<br />
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
  $check_array = array('title', 'year', 'rating', 'company');
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
    // Prepare INSERT statement
    $statement = $db->prepare("INSERT INTO Movie VALUES(?, ?, ?, ?, ?)");

    // Get next id from MaxPersonID
    $id_query = "SELECT id FROM MaxMovieID";
    $id = $db->query($id_query)->fetch_assoc()['id'] + 1;
    console_log($id);

    // Bind params to $_GET and such
    $rs = $statement->bind_param('isiss', $id, $_GET['title'], $_GET['year'], $_GET['rating'], $_GET['company']);

    $test_execute = false;

    // Execute statement
    if ($test_execute && $statement->execute()) {
      console_log("Insert Movie Success");
      // Now update MaxMovieID
      $update_id = "UPDATE MaxMovieID SET id=$id";
      console_log($update_id);
      $rs = $db->query($update_id);
      console_log($rs);
    }
    else {
      console_log("Failed to insert to Movie");
    }

    // Insert into MovieGenre if genre specified
    if (isset($_GET['genre'])) {
      // If multiple genres, need to aggregate inserts into one statement
      // jk fuck it just gonna do multiple insert statements for now
      $get_genre = $_GET['genre'];
      foreach ($get_genre as $genre) {
        console_log($genre);
        $insert_genre = $db->prepare("INSERT INTO MovieGenre VALUES(?, ?)");
        $rs = $insert_genre->bind_param('is', $id, $genre);

        if ($test_execute && $insert_genre->execute()) {
          console_log("Insert MovieGenre success");
        }
        else {
          console_log("Failed to insert to MovieGenre");
        }
      }
    }
  }

  // Close db connection
  $db->close();
?>

