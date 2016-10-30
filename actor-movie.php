<h3>Actor Roles in Movies</h3>

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
  Actor:
    <select name="actor">
      <?php
        // Populate dropdown with existing actors
        if ($actors_rs) {
          // Echo each result as a select option
          while ($row = $actors_rs->fetch_assoc()) {
            echo "<option value='".$row["id"]."'>".$row['first']." ".$row['last']." (".$row["dob"].")</option>";
          }
        }
        $actors_rs->free();
      ?>
    </select>
  <br />
  Movie:
    <select name="movie">
      <?php
        // Populate dropdown with existing movies
        if ($movies_rs) {
          // Echo each result as a select option
          while ($row = $movies_rs->fetch_assoc()) {
            echo "<option value='".$row["id"]."'>".$row["title"]." (".$row["year"].")</option>";
          }
        }
        $movies_rs->free();
      ?>
    </select>
  <br />
  Role: <input type="text" name="role" />
  <input type="submit" value="Submit"/>
</form>