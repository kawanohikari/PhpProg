<?php
function h($str)
{
  return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}
$comment = h($_GET["comment"]);
echo $comment;
