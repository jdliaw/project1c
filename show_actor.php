<link href="query.css" rel="stylesheet">

<?php
  include "navbar.php"
?>

<div class="container">
    <h2>Actor Information</h2>

<?php
    if($_GET["id"]) {
        $search = $_GET["id"];
        // Connect to database CS143 from localhost (u: cs143)
        $db = new mysqli('localhost', 'cs143', '', 'CS143');

        // Handle if unable to connect
        if ($db->connect_errno > 0) {
            die('Unable to connect to database [' . $db->connect_error . ']');
        }

        //Form query from actor ID
        $query = "SELECT * FROM Actor WHERE id='$search'";

        echo "<h3>Actor details:</h3>";
        echo "<table>";

        // Run query
        $rs = $db->query($query);

        // Get col names
        echo "<tr>"; // All col names go in one row
        echo"<td><b>Name</b></td>";
        echo"<td><b>Sex</b></td>";
        echo"<td><b>Date of Birth</b></td>";
        echo"<td><b>Date of Death</b></td>";
        echo "</tr>"; // Close col name row

        $name = "";
        // Get returned data
        while ($row = $rs->fetch_assoc()) {
          echo "<tr>";
          $id = $row['id'];
          $first = $row['first'];
          $last = $row['last'];
          $sex = $row['sex'];
          $dob = $row['dob'];
          $dod = $row['dod'];

          $name = $first . " " . $last;

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

        /*****************************************************************************************************************/
        /** Now, we have to look up information for the actor. We will display a list of movies this actor has been in. **/
        /*****************************************************************************************************************/

        //Form query from actor ID
        $query = "SELECT DISTINCT mid, title, role FROM Movie m, MovieActor ma WHERE ma.aid='$search' && m.id=ma.mid";

        echo "<h3>Movies this actor acted in:</h3>";
        echo "<table>";

        // Run query
        $rs = $db->query($query);

        // Get num of columns
        $ncols = $rs->field_count;
        $nrows = $rs->num_rows;

        // Get col names
        echo "<tr>"; // All col names go in one row
        echo "<td><b>Movie Title</b></td>";
        echo "<td><b>" . $name . "'s Role</b></td>";
        echo "</tr>";

        // Get returned data
        while ($row = $rs->fetch_row()) {
            echo "<tr>";
            for ($i = 1; $i < $ncols; $i++) {
                if ($row[$i] == NULL) {
                echo "<td>N/A</td>";
                }
                else {
                    //If last column, then it's the movie id. We can then link to the movie.'
                    if($i == 1) {
                        echo "<td><a href='show_movie.php?id=$row[0]'>". $row[$i] ."</a></td>";
                    }
                    else
                        echo "<td>". $row[$i] ."</td>";
                }
            }
            echo "</tr>";
        }

        echo "</table>";
    }

?>

</div>


<?php
    include 'query.php';
?>