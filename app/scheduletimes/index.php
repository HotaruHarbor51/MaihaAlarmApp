<!-- アラーム設定 一覧画面 -->
<?php include "../layout/header.php"; ?>

<?php
  $datas = "";

  try {
    require "../database.php";
    require "../layout/common.php";

    $connection = db(); // データベース取得
    // データ取得処理
    $sql = 'SELECT * FROM "develop".scheduletimes ORDER BY id desc';
    $statement = $connection->prepare($sql);
    $statement->execute();
    $datas = $statement->fetchAll();
  } catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
  }
  // 接続を切る
  $connection = null;
?>

<script>
$(function(){
  $('.schedule-del').on('click', function(){
    // クリックしたボタンを取得
    const clickEle = $(this);
    const schedule_id = clickEle.val();
    if(!window.confirm("設定No." + schedule_id + " を削除してもよろしいでしょうか。")){
      return false;
    }
    // phpファイルへのアクセス
    $.ajax(
      {
        url: 'delete_ajax.php',
        type: 'post',
        data: {schedule_id: clickEle.val(), '_method': 'delete'},
      }
    )
    .done(function(dataResult){
      const result = JSON.parse(dataResult);
      if(result.statusCode==200){
        clickEle.parents('tr').remove();
        // 削除に成功した場合
        alert('削除に成功しました。');
      } else {
        alert('削除に失敗しました。')
      }
    })
  })
})
</script>

<?php
$ReturnLink = <<< EOM
<a href="/index.php" class="btn btn-warning">一覧画面に戻る</a>
<br/>
EOM;

$addLink = <<< EOM
<a href="add.php" class="btn btn-primary mb-3">新規追加する</a>
<br/>
EOM;

?>

<div class="content-wrapper">

  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/index.php">ホーム画面</a></li>
      <li class="breadcrumb-item active"><a href="index.php">アラーム設定マスタ 一覧</a></li>
    </ol>
  </nav>

  <h2 class="maiha-caption">アラーム設定マスタ</h2>

  <div class="card">
    <div class="card-header maiha-header">
      <h2>設定リスト</h2>
    </div>
    <div class="card-body maiha-body">

      <?= $addLink ?>
      <?php if($datas && $statement->rowCount() > 0){ ?>
        <table class="table table-success table-striped table-bordered table-responsive">
          <thead>
            <th>No</th>
            <th>タイトル</th>
            <th>通知方法</th>
            <th>アラーム時間</th>
            <th>あらかじめ通知時間</th>
            <th>操作</th>
          </thead>
          <tbody>
            <?php foreach($datas as $data){ ?>
              <tr>
                <td><?= escape($data['id']) ?></td>
                <td><?= escape($data['title']) ?></td>
                <td><?= escape($data['notification_method']) ?></td>
                <td><?= escape(date('Y-m-d H:i', strtotime($data['noticetime']))) ?></td>
                <td><?= $data['advancetime'] ? escape(date('Y-m-d H:i', strtotime($data['advancetime']))) : "" ?></td>
                <td>
                  <a href="<?= "edit.php?id=".$data['id'] ?>" class="btn btn-info">編集</a>
                  <button class="schedule-del btn btn-danger" value="<?= escape($data['id']) ?>">削除</button>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      <?php } ?>
  </div>
</div>

<?php include "../layout/footer.php"; ?>
