<?php
  include "navbar.php";
?>

<link href="add-record.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">

<div class="input">
  <h2>Add a Movie.</h2>

  <form method="GET" action="<?php $_PHP_SELF ?>">
    <b>Title:</b> <input type="text" name="title" class="text-input"/><br />
    <b>Year:</b> <input type="number" name="year" class="text-input"/>
    <br />
    <b>MPAA rating:</b>
    <select name="rating">
      <option value="G">G</option>
      <option value="NC-17">NC-17</option>
      <option value="PG">PG</option>
      <option value="PG-13">PG-13</option>
      <option value="R">R</option>
    </select><br />
    <b>Company:</b> <input type="text" name="company" class="text-input"/>
    <br />
    <b>Genre:</b><br />
    <input type="checkbox" name="genre[]" id="genre" value="Action" />Action
    <input type="checkbox" name="genre[]" id="genre" value="Adult" />Adult
    <input type="checkbox" name="genre[]" id="genre" value="Adventure" />Adventure
    <input type="checkbox" name="genre[]" id="genre" value="Animation" />Animation
    <input type="checkbox" name="genre[]" id="genre" value="Comedy" />Comedy
    <input type="checkbox" name="genre[]" id="genre" value="Crime" />Crime
    <input type="checkbox" name="genre[]" id="genre" value="Documentary" />Documentary
    <input type="checkbox" name="genre[]" id="genre" value="Drama" />Drama
    <input type="checkbox" name="genre[]" id="genre" value="Family" />Family<br />
    <input type="checkbox" name="genre[]" id="genre" value="Fantasy" />Fantasy
    <input type="checkbox" name="genre[]" id="genre" value="Horror" />Horror
    <input type="checkbox" name="genre[]" id="genre" value="Nusical" />Musical
    <input type="checkbox" name="genre[]" id="genre" value="Nystery" />Mystery
    <input type="checkbox" name="genre[]" id="genre" value="Romance" />Romance
    <input type="checkbox" name="genre[]" id="genre" value="Sci-fi" />Sci-Fi
    <input type="checkbox" name="genre[]" id="genre" value="Short" />Short
    <input type="checkbox" name="genre[]" id="genre" value="Thriller" />Thriller
    <input type="checkbox" name="genre[]" id="genre" value="War" />War
    <input type="checkbox" name="genre[]" id="genre" value="Western" />Western
    <br /><br />
    <input type="submit" value="Submit" class="button"/>
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
  $check_array = array('title', 'year', 'rating', 'company');
  foreach($check_array as $key) {
    if (!isset($_GET[$key])) {
      // TODO: Some kind of error message if not all required fields entered.
      $required_present = false;
      break;
    }
  }

  // Connect to db
  $db = new mysqli('localhost', 'cs143', '', 'CS143');
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

    // Execute statement
    if ($statement->execute()) {
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

