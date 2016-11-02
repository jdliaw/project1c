<link href="query.css" rel="stylesheet">

<?php
  include "navbar.php"
?>

<div class="container">
    <h1>Actor Information</h1>

<?php
    echo '<p>You looked up actor id: ' . $_GET["id"] . '</p>';
    if($_GET["id"]) {
        $search = $_GET["id"];
        // Connect to database CS143 from localhost (u: cs143)
        $db = new mysqli('localhost', 'cs143', '', 'CS143');

        // Handle if unable to connect
        if ($db->connect_errno > 0) {
            die('Unable to connect to database [' . $db->connect_error . ']');
        }

        //Form query from actor ID
        $query = "SELECT first, last, sex, dob FROM Actor WHERE id='$search'";

        echo "<h3>Here is some info about this actor:</h3>";
        echo "<table border=1 cellspacing=1 cellpadding=2>";

        // Run query
        $rs = $db->query($query);

        // Get num of columns
        $ncols = $rs->field_count;
        $nrows = $rs->num_rows;

        // Get col names
        echo "<tr align='center'>"; // All col names go in one row

        for ($i = 0; $i < $ncols; $i++) {
            if($i == 0) {
                echo "<td><b>Actor Name</b></td>";
                $i++;
            }
            else {
                $cinfo = $rs->fetch_field_direct($i);
                $cname = $cinfo->name;
                echo "<td><b>". $cname ."</b></td>";
            }
        }
        echo "</tr>"; // Close col name row

        $name = "";
        // Get returned data
        while ($row = $rs->fetch_row()) {
            echo "<tr align='center'>";
            for ($i = 0; $i < $ncols; $i++) {
                if ($row[$i] == NULL) {
                    echo "<td>N/A</td>";
                }
                else if($i == 0) {
                    $name = $row[$i] . " " . $row[$i + 1];
                    echo "<td>". $row[$i] . " " . $row[$i + 1] ."</td>";
                    $i++;
                }
                else {
                    echo "<td>". $row[$i] ."</td>";
                }
            }
            echo "</tr>";
        }

        echo "</table>";

        /*****************************************************************************************************************/
        /** Now, we have to look up information for the actor. We will display a list of movies this actor has been in. **/
        /*****************************************************************************************************************/

        //Form query from actor ID
        $query = "SELECT DISTINCT mid, title, role FROM Movie m, MovieActor ma WHERE ma.aid='$search' && m.id=ma.mid";

        echo "<h3>Movies that this actor has acted in:</h3>";
        echo "<table border=1 cellspacing=1 cellpadding=2>";

        //echo $query;
        // Run query
        $rs = $db->query($query);

        // Get num of columns
        $ncols = $rs->field_count;
        $nrows = $rs->num_rows;

        // Get col names
        echo "<tr align='center'>"; // All col names go in one row

        // //start i = 1 to skip the movie id.
        // for ($i = 1; $i < $ncols; $i++) {
        // $cinfo = $rs->fetch_field_direct($i);
        // $cname = $cinfo->name;
        // echo "<td><b>". $cname ."</b></td>";
        // }
        // echo "</tr>"; // Close col name row

        echo "<td><b>Movie Title</b></td>";
        echo "<td><b>" . $name . "'s Role</b></td>";

        // Get returned data
        while ($row = $rs->fetch_row()) {
        echo "<tr align='center'>";
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