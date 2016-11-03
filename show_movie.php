<link href="query.css" rel="stylesheet">

<?php
  include "navbar.php"
?>

<div class="container">
    <h1>Movie Information</h1>

<?php
    //echo '<p>You looked up movie id: ' . $_GET["id"] . '</p>';
    if($_GET["id"]) {
        $search = $_GET["id"];
        // Connect to database CS143 from localhost (u: cs143)
        $db = new mysqli('localhost', 'cs143', '', 'CS143');

        // Handle if unable to connect
        if ($db->connect_errno > 0) {
        die('Unable to connect to database [' . $db->connect_error . ']');
        }

        //Form query from actor ID
        $query = "SELECT * FROM Movie m WHERE m.id='$search'";
        $get_genres = "SELECT * FROM MovieGenre WHERE mid ='$search'";

        echo "<h3>Movie details:</h3>";
        echo "<table>";

        // Run query
        $rs = $db->query($query);
        $rs_genres = $db->query($get_genres);

        // Get col names
        echo "<tr>"; // All col names go in one row
        echo "<td><b>Title</b></td>";
        echo "<td><b>Year</b></td>";
        echo "<td><b>Rating</b></td>";
        echo "<td><b>Company</b></td>";
        echo "<td><b>Genre(s)</b></td>";
        echo "</tr>"; // Close col name row

        // Need genre and director.

        // Get returned data
        while ($row = $rs->fetch_assoc()) {
            $id = $row['id'];
            $title = $row['title'];
            $year = $row['year'];
            $rating = $row['rating'];
            $company = $row['company'];

            echo "<tr>";
            echo "<td>". $title ."</td>";
            echo "<td>". $year ."</td>";
            echo "<td>". $rating ."</td>";
            echo "<td>". $company ."</td>";
            echo "<td>";
            while ($gen_row = $rs_genres->fetch_assoc()) {
                $genre = $gen_row['genre'];
                echo $genre. ", ";
            }
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";

        $get_avgrating = "SELECT AVG(rating) FROM Review WHERE mid='$search'";
        $rs = $db->query($get_avgrating);
        echo "<h4>Average rating: ". $rs->fetch_row()[0] ." out of 5 stars</h4><hr>";

        $get_reviews = "SELECT * FROM Review WHERE mid='$search'";

        echo "<h3>Reviews:</h3>";
        echo "<table class='review-table'>";
        $rs = $db->query($get_reviews);

        // Get returned data
        while ($row = $rs->fetch_assoc()) {
            $name = $row['name'];
            $comment = $row['comment'];
            $time = $row['time'];

            echo "<tr id='review-comment'><td>". $comment ."</td></tr>";
            echo "<tr id='review-info'><td id='name'>By ". $name ."<span id='time'>". $time ."</span></td></tr>";
        }
        echo "</table>";

        echo "<br /><a href='movie-review.php'><button class='button'>Add Review</button></a>";


        /*
        Now, we have to look up information for the actor. We will display a list of movies this actor has been in.
        */
        //Form query from actor ID
        $query = "SELECT DISTINCT aid, first, last, role FROM Movie m, MovieActor ma, Actor a WHERE ma.mid='$search' && a.id=ma.aid";

        echo "<h3>Actors in this movie:</h3>";
        echo "<table>";

        // Run query
        $rs = $db->query($query);

        // Get num of columns
        $ncols = $rs->field_count;
        $nrows = $rs->num_rows;

        // Get col names
        echo "<tr>"; // All col names go in one row

        echo "<td><b>Name</b></td>";
        echo "<td><b>Role</b></td>";
        echo "</tr>"; // Close col name row

        // Get returned data
        while ($row = $rs->fetch_assoc()) {
            $id = $row['aid'];
            $first = $row['first'];
            $last = $row['last'];
            $role = $row['role'];
            echo "<tr>";
            echo "<td><a href='show_actor.php?id=$id'>". $first . " " . $last ."</a></td>";
            echo "<td>". $role ."</td>";
            echo "</tr>";
        }

        echo "</table>";
    }

?>
</div>

<?php
    include 'query.php';
?>
