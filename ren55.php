<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>じゃんけん大会</title>
  <style>
    .number {
      border: none;
      width: 25px;
      text-align: left;
    }

    .suuji {
      text-align: right;
    }
  </style>
</head>

<body>
  <h1>じゃんけん大会</h1>
  <?php
  // じゃんけん大会Web版（１＝グー、２＝チョキ、３＝パー）
  function h($str)
  {
    return htmlspecialchars($str, ENT_QUOTES, "utf-8");
  }

  function janken($user, $comp)
  {
    if ($user == 1 && $comp == 1) {
      print("あなたはグー、コンピューターはグー<br>");
      return "draw";
    }
    if ($user == 1 && $comp == 2) {
      print("あなたはグー、コンピューターはチョキ<br>");
      return "user";
    }
    if ($user == 1 && $comp == 3) {
      print("あなたはグー、コンピューターはパー<br>");
      return "comp";
    }
    if ($user == 2 && $comp == 1) {
      print("あなたはチョキ、コンピューターはグー<br>");
      return "comp";
    }
    if ($user == 2 && $comp == 2) {
      print("あなたはチョキ、コンピューターはチョキ<br>");
      return "draw";
    }
    if ($user == 2 && $comp == 3) {
      print("あなたはチョキ、コンピューターはパー<br>");
      return "user";
    }
    if ($user == 3 && $comp == 1) {
      print("あなたはパー、コンピューターはグー<br>");
      return "user";
    }
    if ($user == 3 && $comp == 2) {
      print("あなたはパー、コンピューターはチョキ<br>");
      return "comp";
    }
    if ($user == 3 && $comp == 3) {
      print("あなたはパー、コンピューターはパー<br>");
      return "draw";
    }
    return "-1";
  }

  $reset = $_GET["reset"] ?? null;
  if ($reset == "reset") {
    $user = null;
    $comp = null;
    $user_win = 0;
    $comp_win = 0;
    $draw = 0;
  } else {
    $user = $_GET["user"] ?? null;
    $comp = mt_rand(1, 3);
    $user_win = $_GET["user_win"] ?? 0;
    $comp_win = $_GET["comp_win"] ?? 0;
    $draw = $_GET["draw"] ?? 0;
  }

  if ($user != null) {
    print("じゃんけん結果<br><br>");
    $result = janken($user, $comp);
    if ($result == "user") {
      print("あなたの勝ちです。<br>");
      $user_win++;
    } elseif ($result == "comp") {
      print("コンピューターの勝ちです。<br>");
      $comp_win++;
    } elseif ($result == "draw") {
      print("あいこです。<br>");
      $draw++;
    }
  ?>
    <table>
      <tr>
        <th>あなた</th>
        <th>コンピューター</th>
      </tr>
      <tr>
        <?php if ($user == 1) : ?>
          <td><img src="./img/janken/gu1.png" alt="グー"></td>
        <?php elseif ($user == 2) : ?>
          <td><img src="./img/janken/choki1.png" alt="チョキ"></td>
        <?php elseif ($user == 3) : ?>
          <td><img src="./img/janken/pa1.png" alt="パー"></td>
        <?php endif; ?>
        <?php if ($comp == 1) : ?>
          <td><img src="./img/janken/gu2.png" alt="グー"></td>
        <?php elseif ($comp == 2) : ?>
          <td><img src="./img/janken/choki2.png" alt="チョキ"></td>
        <?php elseif ($comp == 3) : ?>
          <td><img src="./img/janken/pa2.png" alt="パー"></td>
        <?php endif; ?>
      </tr>
    </table>
  <?php
  } else {
    print("先に3回勝った方の勝利です。<br>");
  }
  print("<br>");
  ?>
  <form method="get">
    <table>
      <tr>
        <td>あなた</td>
        <td class="suuji"><input type="number" class="number" name="user_win" value="<?= h($user_win) ?>"> 勝</td>
      </tr>
      <tr>
        <td>コンピューター</td>
        <td class="suuji"><input type="number" class="number" name="comp_win" value="<?= h($comp_win) ?>"> 勝</td>
      </tr>
      <tr>
        <td>あいこ</td>
        <td class="suuji"><input type="number" class="number" name="draw" value="<?= h($draw) ?>"> 回</td>
      </tr>
    </table>
    <br>
    <?php if ($user_win >= 3): ?>
      <p>あなたの勝利です！</p>
    <?php elseif ($comp_win >= 3): ?>
      <p>コンピューターの勝利です！</p>
    <?php else: ?>
      <p>次の手は？</p>
      <label><input type="radio" name="user" value="1">グー</label>
      <label><input type="radio" name="user" value="2">チョキ</label>
      <label><input type="radio" name="user" value="3">パー</label>
      <input type="submit" value="送信">
    <?php endif; ?>
  </form>
  <br>
  <form method="get">
    <input type="hidden" name="reset" value="reset">
    <input type="submit" value="最初からやりなおす">
  </form>
</body>

</html>