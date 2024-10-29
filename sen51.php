<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>サンプル</title>
</head>

<body>
  <h1>サンプルプログラム</h1>
  <?php
  echo "<p>九九の表</p>";
  echo "<table border=1>";
  foreach (range(1, 9) as $x) {
    echo "<tr>";
    foreach (range(1, 9) as $y) {
      echo "<td>" . $x * $y . "</td>";
    }
    echo "</tr>";
  }
  echo "</table>"
  ?>
</body>

</html>