<h1>Query</h1>
<p>by Jennifer Liaw (004454638) and Brandon Liu (004439799).</p>

<p>Enter your search:</p>

<form method="GET" action="<?php $_PHP_SELF ?>">
  <textarea name="search" value="<?php if(isset($search)) echo $_GET['search']; ?>" cols="60" rows="8"></textarea><br />
  <input type="submit" value="Submit"/>
</form>

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

    $query = "SELECT first, last, dob FROM Actor WHERE first LIKE '%$search%' OR last LIKE '%$search%'";
    // Run query
    $rs = $db->query($query);

    // Get num of columns
    $ncols = $rs->field_count;
    $nrows = $rs->num_rows;

    // Get col names
    echo "<tr align='center'>"; // All col names go in one row

    for ($i = 0; $i < $ncols; $i++) {
      $cinfo = $rs->fetch_field_direct($i);
      $cname = $cinfo->name;
      echo "<td><b>". $cname ."</b></td>";
    }
    echo "</tr>"; // Close col name row

    // Get returned data
    while ($row = $rs->fetch_row()) {
      echo "<tr align='center'>";
      for ($i = 0; $i < $ncols; $i++) {
        if ($row[$i] == NULL) {
          echo "<td>N/A</td>";
        }
        else {
          echo "<td>". $row[$i] ."</td>";
        }
      }
      echo "</tr>";
    }

    echo "</table>";

    //Do this query for movie titles
    echo "<h3>Actors with this first or last name:</h3>";
    echo "<table border=1 cellspacing=1 cellpadding=2>";

    $query = "SELECT title, year FROM Movie WHERE title LIKE '%$search%'";
    // Run query
    $rs = $db->query($query);

    // Get num of columns
    $ncols = $rs->field_count;
    $nrows = $rs->num_rows;

    // Get col names
    echo "<tr align='center'>"; // All col names go in one row

    for ($i = 0; $i < $ncols; $i++) {
      $cinfo = $rs->fetch_field_direct($i);
      $cname = $cinfo->name;
      echo "<td><b>". $cname ."</b></td>";
    }
    echo "</tr>"; // Close col name row

    // Get returned data
    while ($row = $rs->fetch_row()) {
      echo "<tr align='center'>";
      for ($i = 0; $i < $ncols; $i++) {
        if ($row[$i] == NULL) {
          echo "<td>N/A</td>";
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
