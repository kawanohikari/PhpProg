<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>サンプル</title>
</head>

<body>
  <?php
  $comment = $_GET["comment"];
  ?>
  <h1>サンプルプログラム</h1>
  <form action="chap5_5_1.php" method="get">
    <input type="text" name="comment" value="<?= $comment ?>">
    <input type="submit" value="送信">
  </form>
</body>

</html>