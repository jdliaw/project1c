<h3>Add a Movie Review</h3>

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