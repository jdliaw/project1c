<h2>Add info!</h2>
<p>Page to input information</p>

<h3>Actors</h3>
<p>Input actor information.</p>

<form method="GET" action="<?php $_PHP_SELF ?>">
  First name: <input type="text" name="first-name" />
  Last name: <input type="text" name="last-name" />
  <br />
  Sex: <input type="text" name="sex" />
  Date of birth: <input type="date" name="dob" />
  Date of death: <input type="date" name="dod" />
  <br /><br />
  <input type="submit" value="Submit"/>
</form>

<hr>

<h3>Directors</h3>
<p>Input director information.</p>

<form method="GET" action="<?php $_PHP_SELF ?>">
  First name: <input type="text" name="first-name" />
  Last name: <input type="text" name="last-name" />
  <br />
  Date of birth: <input type="date" name="dob" />
  Date of death: <input type="date" name="dod" />
  <br /><br />
  <input type="submit" value="Submit"/>
</form>

<hr>

<h3>Movies</h3>
<p>Input movie information.</p>

<form method="GET" action="<?php $_PHP_SELF ?>">
  Title: <input type="text" name="title" />
  Year: <input type="number" name="year" />
  <br />
  MPAA rating: <input type="number" name="rating" />
  Company: <input type="text" name="company" />
  <br /><br />
  <input type="submit" value="Submit"/>
</form>