<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>じゃんけんゲーム</title>
  <style>
    body {
      font-family: Arial, sans-serif;
    }

    .result {
      font-weight: bold;
    }
  </style>
</head>

<body>
  <h1>じゃんけんゲーム</h1>
  <form method="get">
    <label><input type="radio" name="janken" value="1" checked>グー</label>
    <label><input type="radio" name="janken" value="2">チョキ</label>
    <label><input type="radio" name="janken" value="3">パー</label>
    <input type="submit" value="勝負！">
  </form>
  <?php
  function h($str)
  {
    return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
  }

  function determineWinner($player1, $player2)
  {
    if ($player1 == $player2) {
      return "引き分け";
    }
    if (($player1 == 1 && $player2 == 2) || ($player1 == 2 && $player2 == 3) || ($player1 == 3 && $player2 == 1)) {
      return "あなたの勝ち";
    }
    return "コンピュータの勝ち";
  }

  if (isset($_GET['janken'])) {
    $player1 = intval($_GET['janken']);
    $player2 = rand(1, 3);
    $choices = ["", "グー", "チョキ", "パー"];

    echo "<p>あなたの手: " . h($choices[$player1]) . "</p>";
    echo "<p>コンピュータの手: " . h($choices[$player2]) . "</p>";

    $result = determineWinner($player1, $player2);
    echo "<p class='result'>" . h($result) . "</p>";
  }
  ?>
</body>

</html>