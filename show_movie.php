<h1>Actor Information</h1>
<p>by Jennifer Liaw (004454638) and Brandon Liu (004439799).</p>

<?php
    echo '<p>You looked up movie id: ' . $_GET["id"] . '</p>';
    if($_GET["id"]) {
        $search = $_GET["id"];
        // Connect to database CS143 from localhost (u: cs143)
        $db = new mysqli('localhost', 'cs143', '', 'CS143');

        // Handle if unable to connect
        if ($db->connect_errno > 0) {
        die('Unable to connect to database [' . $db->connect_error . ']');
        }
        
        //Form query from actor ID
        $query = "SELECT id, first, last, sex, dob FROM Actor WHERE id='$search'";

        echo "<h3>Movie:</h3>";
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



        /* 
        Now, we have to look up information for the actor. We will display a list of movies this actor has been in.
        */
        //Form query from actor ID
        $query = "SELECT DISTINCT title, role, mid FROM Movie m, MovieActor ma WHERE ma.aid='$search' && m.id=ma.mid";

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
                //If last column, then it's the movie id. We can then link to the movie.'
                if($i == $ncols-1) {
                    echo "<td><a href='show_movie.php?id=$row[$i]'>". $row[$i] ."</a></td>";   
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
