<?php

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

$product_list = '</br></br><table class="table ">
<thead>
  <tr>
    <th scope="col">#Roll Number</th>
    <th scope="col">First Name</th>
    <th scope="col">Last Name</th>    
  </tr>
</thead>
<tbody>';
$sql = pg_query($link, "select * from students order by id DESC");
if (pg_num_rows($sql)) {
    while ($row = pg_fetch_array($sql)) {
        $lname = $row['lastName'];
        $fname = $row['firstName'];
        $roll = $row['roll-no'];
        $product_list .= '<tr>
        <th scope="row">' . $roll . '</th>
        <td>' . $fname . '</td>
        <td>' . $lname . '</td>
      </tr>';
    }
    $product_list .= '</tbody>
    </table>';
} else {
    $product_list = "Your inventory is empty";
}

pg_close($link);
?>

<!DOCTYPE html>
<html>
<head>

<link href="bootstrap.min.css" rel="stylesheet">
<link href="bootstrap.min.js">
<script src="https://use.fontawesome.com/bef0d2bc4d.js"></script>
<style>
    #disp {
            width: 60vw;
            position: relative;
            top: 10vw;
            left: 18vw;
        }
</style>
</head>
<body>

    <div id="disp">
        <h2>Registered Students</h2>
        <?php echo $product_list; ?>
    </div>

</body>
</html>