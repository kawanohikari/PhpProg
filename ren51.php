<!-- 九九の表（色付き） -->
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>九九の表</title>
</head>

<body>
  <h1>九九の表</h1>
  <table>
    <?php foreach (range(1, 9) as $y) : ?>
      <tr>
        <?php foreach (range(1, 9) as $x) : ?>
          <?php if ($x == $y) : ?>
            <td style="border-right: 20px solid white;background-color: yellow;">
            <?php else: ?>
            <td style="border-right: 20px solid white; background-color: #f<?= $y ?>f">
            <?php endif; ?>
            <?= $x ?>×<?= $y ?> = <?= $x * $y ?>
            </td>
          <?php endforeach; ?>
      <tr>
      <?php endforeach; ?>
  </table>
</body>

</html>