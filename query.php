<link href="query.css" rel="stylesheet">

<div class="container">
  <h2>Search</h2>

  Enter your search:<br />
  <form method="GET" action="<?php $_PHP_SELF ?>">
    <input class="text-input" name="search" value="<?php if(isset($search)) echo $_GET['search']; ?>"><br />
    <input class="button" type="submit" value="Click to search!"/>
  </form>
</div>


<?php
  if ($_GET["search"]) {
    echo "<div class='container'>";
    // Get query from textarea
    $search = $_GET["search"];
    echo "<p>Your search: <b>". $search ."</b></p>";
    echo "<h3>Actor results:</h3>";
    echo "<table>";

    // Connect to database CS143 from localhost (u: cs143)
    $db = new mysqli('localhost', 'cs143', '', 'CS143');

    // Handle if unable to connect
    if ($db->connect_errno > 0) {
      die('Unable to connect to database [' . $db->connect_error . ']');
    }

    /*
    The code below searches for Actors. Handles a space as an AND relation.
    */

    //Use the search to form a query, and handle spaces as 'AND' relation.
    $queries = explode(" ", $search);
    //If the array size is 2, that means there are two spaces so we can handle as an AND relation.
    if(count($queries) == 2) {
      $query = "SELECT * FROM Actor WHERE (first LIKE '%$queries[0]%' AND last LIKE '%$queries[1]%') OR first LIKE '%$queries[1]%' AND last LIKE '%$queries[0]%'";
    }
    //Array size is 1, that means we can run a regular query. Alternatively, if it's greater than 2, then given our actors, we cannot have 2 space in a name.
    else {
      $query = "SELECT * FROM Actor WHERE first LIKE '%$search%' OR last LIKE '%$search%'";
    }

    // Run query
    $rs = $db->query($query);

    // Get col names
    echo "<tr>"; // All col names go in one row
    echo"<td><b>Name</b></td>";
    echo"<td><b>Sex</b></td>";
    echo"<td><b>Date of Birth</b></td>";
    echo"<td><b>Date of Death</b></td>";
    echo "</tr>"; // Close col name row

    // Get returned data
    while ($row = $rs->fetch_assoc()) {
      echo "<tr>";
      $id = $row['id'];
      $first = $row['first'];
      $last = $row['last'];
      $sex = $row['sex'];
      $dob = $row['dob'];
      $dod = $row['dod'];

      echo "<td><a href='show_actor.php?id=$id'>". $first. " " . $last . "</a></td>";
      echo "<td>". $sex ."</td>";
      echo "<td>". $dob ."</td>";
      if ($dod == NULL) {
        echo "<td>N/A</td>";
      }
      else {
        echo "<td>". $dod ."</td>";
      }
      echo "</tr>";
    }

    echo "</table>";

    /*
    Now, we have to repeat the same steps for movie queries, except we don't have to bother with spaces being 'AND' relations.
    */
    //Do this query for movie titles
    echo "<h3>Movie results:</h3>";
    echo "<table>";

    $query = "SELECT id, title, year FROM Movie WHERE title LIKE '%$search%'";
    // Run query
    $rs = $db->query($query);

    // Get col names
    echo "<tr>"; // All col names go in one row
    echo "<td><b>Title</b></td>";
    echo "<td><b>Year</b></td>";
    echo "</tr>"; // Close col name row

    // Get returned data
    while ($row = $rs->fetch_assoc()) {
      $id = $row['id'];
      $title = $row['title'];
      $year = $row['year'];

      echo "<tr>";
      echo "<td><a href='show_movie.php?id=$id'>". $title ."</td>";
      echo "<td>". $year ."</td>";
      echo "</tr>";
    }

    echo "</table>";

    echo "</div>";

  }


?>
