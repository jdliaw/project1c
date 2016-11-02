<link href="query.css" rel="stylesheet">

<?php
  include "navbar.php"
?>

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
    // Get query from textarea
    $search = $_GET["search"];
    echo "<p>Your search: ". $search ."</p>";
    echo "<h3>Actors with this first or last name:</h3>";
    echo "<table border=1 cellspacing=1 cellpadding=2>";

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
      $query = "SELECT id, first, last, dob FROM Actor WHERE (first LIKE '%$queries[0]%' AND last LIKE '%$queries[1]%') OR first LIKE '%$queries[1]%' AND last LIKE '%$queries[0]%'";
    }
    //Array size is 1, that means we can run a regular query. Alternatively, if it's greater than 2, then given our actors, we cannot have 2 space in a name.
    else {
      $query = "SELECT id, first, last, dob FROM Actor WHERE first LIKE '%$search%' OR last LIKE '%$search%'";
    }

    // Run query
    $rs = $db->query($query);

    // Get num of columns
    $ncols = $rs->field_count;
    $nrows = $rs->num_rows;

    // Get col names
    echo "<tr align='center'>"; // All col names go in one row

    // for ($i = 0; $i < $ncols; $i++) {
    //   $cinfo = $rs->fetch_field_direct($i);
    //   $cname = $cinfo->name;
    //   echo "<td><b>". $cname ."</b></td>";
    // }
    echo"<td><b>Actor Name</b></td>";
    echo"<td><b>Date of Birth</b></td>";


    echo "</tr>"; // Close col name row

    // Get returned data
    while ($row = $rs->fetch_row()) {
      echo "<tr align='center'>";
      for ($i = 1; $i < $ncols; $i++) {
        if ($row[$i] == NULL) {
          echo "<td>N/A</td>";
        }
        else if ($i == 1) {
            echo "<td><a href='show_actor.php?id=$row[0]'>". $row[$i] . " " . $row[$i+1] ."</a></td>";
            $i++;
        }
        else {
          echo "<td>". $row[$i] ."</td>";
        }
      }
      echo "</tr>";
    }

    echo "</table>";

    /*
    Now, we have to repeat the same steps for movie queries, except we don't have to bother with spaces being 'AND' relations.
    */
    //Do this query for movie titles
    echo "<h3>Actors with this first or last name:</h3>";
    echo "<table border=1 cellspacing=1 cellpadding=2>";

    $query = "SELECT id, title, year FROM Movie WHERE title LIKE '%$search%'";
    // Run query
    $rs = $db->query($query);

    // Get num of columns
    $ncols = $rs->field_count;
    $nrows = $rs->num_rows;

    // Get col names
    echo "<tr align='center'>"; // All col names go in one row

    for ($i = 1; $i < $ncols; $i++) {
      $cinfo = $rs->fetch_field_direct($i);
      $cname = $cinfo->name;
      echo "<td><b>". $cname ."</b></td>";
    }
    echo "</tr>"; // Close col name row

    // Get returned data
    while ($row = $rs->fetch_row()) {
      echo "<tr align='center'>";
      for ($i = 1; $i < $ncols; $i++) {
        if ($row[$i] == NULL) {
          echo "<td>N/A</td>";
        }
        else if($i == 1) {
          echo"<td><a href='show_movie.php?id=$row[0]'>" . $row[$i] . " " . $row[$i+1] . "</a></td>";
        }
        else {
          echo "<td>". $row[$i] ."</td>";
        }
      }
      echo "</tr>";
    }

    echo "</table>";

  }


?>
