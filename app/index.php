<?php include "layout/header.php"; ?>

<?php

  $indexPageLinks = <<< EOM
  <a href="scheduletimes/index.php">アラーム設定マスタ</a>
  <br/>
  EOM;

?>

<div class="content-wrapper">
  <div class="frontpage-wrapper maiha-body">
    <h2 class="maiha-caption">色織まいはのお茶会用アラームアプリ</h2>
    <p>
      みんなと一緒のお茶会　楽しみね♪<br/>
      忘れないようにしないと。<br/>
      このアプリでアラーム設定することで、わたしに通知してくれるのね。<br/>
      <span style="color: #F5B2B2;">アラーム設定マスタ</span>からアラーム時刻を設定できますわ。<br/>
      下記リンクよりお入りくださいませ。
    </p><br/>
    <p>
      <?= $indexPageLinks; ?>
    </p>
  </div>
</div>

<?php include "layout/footer.php"; ?>
