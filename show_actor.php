<h1>Actor Information</h1>
<p>by Jennifer Liaw (004454638) and Brandon Liu (004439799).</p>

<?php
    echo '<p>You looked up actor id: ' . $_GET["id"] . '</p>';
    $search = $_GET["id"];
    // Connect to database CS143 from localhost (u: cs143)
    $db = new mysqli('localhost', 'cs143', '', 'CS143');

    // Handle if unable to connect
    if ($db->connect_errno > 0) {
      die('Unable to connect to database [' . $db->connect_error . ']');
    }
    
    //Form query from actor ID
    $query = "SELECT id, first, last, sex dob FROM Actor WHERE id='$search'";

    echo "<h3>Actors with this first or last name:</h3>";
    echo "<table border=1 cellspacing=1 cellpadding=2>";

    //echo $query;
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
    
    
?>