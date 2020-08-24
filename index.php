<?php

$flag = true;

if (array_key_exists("register", $_POST)) {
  if (empty($_POST['first-name'])) {
    echo "Enter your first name";
    $flag = false;
  } else {
      $fname = htmlspecialchars($_POST['first-name']);
  }
  if (empty($_POST['last-name'])) {
      echo ("Enter your last name");
      $flag = false;
  } else {
      $lname = htmlspecialchars($_POST['last-name']);
  }

  if (empty($_POST['roll-no'])) {
      echo "Enter your roll-no";
      $flag = false;
  } else {
      $roll = htmlspecialchars($_POST['roll-no']);
  }
}

if ($flag) {
  $link = (function(){
    $parts = (parse_url(getenv('DATABASE_URL')));
    extract($parts);
    $path = ltrim($path, "/");
    return pg_connect("host={$host} dbname={$path} user={$user} password={$pass}");
  })();
  /* check connection */
  if (pg_last_error()) {
      printf("Connection failed: %s\n", pg_last_error());
      exit();
  }
  // print_r($_POST);
  $checkQuery = "select 'id' from account where \"roll-no\" = '" . $roll . "';";

  $checkResult = pg_query($link, $checkQuery);

  if (pg_num_rows($checkResult)) {
      echo "Roll number already registered.";
  } else {
      $res = pg_query($link, "select max(id) from students");
      $row = pg_fetch_array($res);
      $id = $row['max'];
      $id = $id + 1;
      $query = "insert into students(id, \"firstName\", \"lastName\",\"roll-no\") values(".$id.",'" . $fname . "','" . $lname . "','" . $roll . ")";

      if (pg_query($link, $query))
          echo "<h1>You are successfully registered in the database.</h1>";
  }
}


?>



<!DOCTYPE html>
<html>
<head>

<link href="bootstrap.min.css" rel="stylesheet">
<link href="bootstrap.min.js">
<script src="https://use.fontawesome.com/bef0d2bc4d.js"></script>
<style type="text/css">
form {
            display: block;
        }
@import url('https://fonts.googleapis.com/css2?family=PT+Sans&display=swap');

body {
  margin:0;
  font-family: 'PT Sans', sans-serif;
  background-color: cornflowerblue;
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
}
.vert {
  display: block;
}
.form-inline {
  /* display: block; */
  background-color: rgba(0, 0, 0, 0.35);
  font-family: 'PT Sans', sans-serif;
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  justify-content: center;
  width: 270px;
  height: 470px;
}
.dark-mode {
  background-color: rgb(0,0,0);
  color: white;
}
.d-mode {
  width: 270px;
  height: 70px;
  background-color: rgb(100, 50, 50);
}
</style>
</head>

<body>
<div class="vert">
  <button onClick=darkMode() class="d-mode"><i class="fa fa-moon-o fa-4x" aria-hidden="true"></i>
  </button>
  <form onsubmit="event.preventDefault(); rollCheck();" class="needs-validation form-inline" method="post" novalidate>
    <div class="form-row">
      <div class="col-md-6 mb-3">
        <label for="validationCustom01">First name</label>
        <input type="text" class="form-control" id="validationCustom01" placeholder="First name" name="first-name" required>
        <div class="valid-feedback">
          Looks good!
        </div>
      </div>
    </div>
    <div class="form-row">
      <div class="col-md-6 mb-3">
        <label for="validationCustom02">Last name</label>
        <input type="text" class="form-control" id="validationCustom02" placeholder="Last name" name="last-name" required>
        <div class="valid-feedback">
          Looks good!
        </div>
      </div>
    </div>
    <div class="form-row">
      <div class="col-md-12 mb-3">
        <label for="validationCustomUsername">Roll Number</label>
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text" id="inputGroupPrepend">@</span>
          </div>
          <input type="text" class="form-control" id="roll-no" placeholder="eg. 116AB1234" name="roll-no" aria-describedby="inputGroupPrepend" required>
          <div class="invalid-feedback">
            Please enter your roll number.
          </div>
        </div>
      </div>
    </div>
    <button class="btn btn-primary" type="submit">Submit form</button>
  </form>
</div>

<script>
// Example starter JavaScript for disabling form submissions if there are invalid fields
(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();

function rollCheck() {
    var roll = document.getElementById('roll-no').value;
    // console.log('Invalid roll number. Try again.');
    var re = RegExp('^[0-9]{3}[A-Z]{2}[0-9]{4}$');
    if(!re.test(roll)){
        alert('Invalid roll number.Try again.');
    }
    return re.test(roll);
}

function darkMode() {
    var element = document.body;
    element.classList.toggle("dark-mode");
}
</script>

</body>
</html>