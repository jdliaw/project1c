<h1>Movie Information</h1>
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
        $query = "SELECT id, title, year, rating, company FROM Movie m WHERE m.id='$search'";
        //$query = "SELECT id, title, year, rating, company, review FROM Movie m, Review r WHERE m.id='$search' && r.mid = m.id";
        

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
        $query = "SELECT DISTINCT aid, first, last, role FROM Movie m, MovieActor ma, Actor a WHERE ma.mid='$search' && a.id=ma.aid";

        echo "<h3>Actors that are in this movie:</h3>";
        echo "<table border=1 cellspacing=1 cellpadding=2>";

        //echo $query;
        // Run query
        $rs = $db->query($query);

        // Get num of columns
        $ncols = $rs->field_count;
        $nrows = $rs->num_rows;

        // Get col names
        echo "<tr align='center'>"; // All col names go in one row

        // for ($i = 1; $i < $ncols; $i++) {
        //     if($i == 1) {
        //         echo "<td><b>Actor Name</b></td>";
        //         $i++;
        //     }
        //     else {
        //         $cinfo = $rs->fetch_field_direct($i);
        //         $cname = $cinfo->name;
        //         echo "<td><b>". $cname ."</b></td>";
        //     }
        // }
        echo "<td><b>Actor Name</b></td>";
        echo "<td><b>Role in Movie</b></td>";
        echo "</tr>"; // Close col name row

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
                    echo "<td><a href='show_actor.php?id=$row[0]'>". $row[$i] . " " . $row[$i+1] ."</a></td>";  
                    $i++; 
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
