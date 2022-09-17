<!-- アラーム設定 新規追加画面 -->
<?php include "../layout/header.php"; ?>

<?php
  $id = $_GET['id'];
  if(!$id) die('error');
  $statement = "";
  $errors = [];
  require "../database.php";

  if (isset($_POST['submit'])) {

    require "post_form.php";

    // 保存処理
    // バリデーションエラーがあれば、その内容が入った配列が返ってくる
    $saveResult = saveData($id);
    if(is_array($saveResult)) $errors = $saveResult;

  }

  try {

    $connection = db(); // データベース取得
    // データ取得処理
    $sql = 'SELECT * FROM "develop".scheduletimes WHERE id = :id';
    $statement = $connection->prepare($sql);
    $statement->bindParam( ':id', $id, PDO::PARAM_INT);
    $statement->execute();
    $data = $statement->fetch();
  } catch(PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
  }
  // 接続を切る
  $connection = null;
?>

<?php if (isset($_POST['submit']) && !is_array($saveResult) && $saveResult) { ?>

<div class="alert alert-primary d-flex align-items-center" role="alert">
  <svg class="bi flex-shrink-0 me-2" width="24" height="24"><use xlink:href="#check-circle-fill"/></svg>
  <div>
    <!-- 保存に成功したときはアラートメッセージを表示する -->
    <?= $_POST['title']; ?> の更新に成功しました
  </div>
</div>

<?php } ?>

<!-- バリデーションエラーがあった場合に警告表示 -->
<?php if (!empty($errors)){ ?>

<div class="alert alert-danger d-flex align-items-center" role="alert">
  <svg class="bi flex-shrink-0 me-2" width="24" height="24"><use xlink:href="#exclamation-triangle-fill"/></svg>
  <div>
    <ul>
      <?php foreach ($errors as $msg){ ?>
        <li class="text-light"><?= $msg ?></li>
      <?php } ?>
    </ul>
  </div>
</div>

<?php } ?>

<div class="content-wrapper">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/index.php">ホーム画面</a></li>
      <li class="breadcrumb-item"><a href="index.php">アラーム設定マスタ 一覧</a></li>
      <li class="breadcrumb-item active"><a href="edit.php?id=<?= $id ?>">編集</a></li>
    </ol>
  </nav>

  <h2 class="maiha-caption">アラーム設定マスタ</h2>
  <div class="card">
    <div class="card-header maiha-header">
      <h2>編集</h2>
    </div>
    <div class="card-body maiha-body">
    <form method="post">
      <table class="table table-success table-bordered table-responsive">
        <tbody>
          <tr>
            <th>タイトル</th>
            <td><input type="text" name="title" id="title" value=<?= $data['title']  ?> class="form-control"></td>
          </tr>
          <tr>
            <th>アラーム設定時間</th>
            <td><input type="datetime-local" name="noticetime" id="noticetime" value="<?= date("Y-m-d\TH:i", strtotime($data['noticetime'])) ?>"></td>
          </tr>
          <tr>
            <th>あらかじめ通知（分）</th>
            <td><input type="number" name="advancenotice" id="advancenotice" value=<?= $data['advancenotice'] ?> class="form-control"></td>
          </tr>
          <tr>
            <th>通知方法</th>
            <td>
              <select name="notification_method" id="notification_method" class="form-control">
                <?php
                  $methods = ["" => "----", "LINE" => "LINE", "Email" => "メールアドレス"];
                  foreach($methods as $key => $method){
                    if($key == $data['notification_method']) {
                      echo '<option value="' . $key . '" selected>' . $method . '</option>';
                    } else {
                      echo '<option value="' . $key . '">' . $method . '</option>';
                    }
                  }
                ?>
              </select>
            </td>
          </tr>
          <tr>
            <th>説明を追加</th>
            <td>
              <textarea name="description" id="description" rows="3" cols="50" value=<?= $data['description'] ?> class="form-control"></textarea>
            </td>
          </tr>
          <tr>
            <th>UUID<br/>（LINEのみ入力）</th>
            <td><input type="text" name="uuid" id="uuid" class="form-control" value=<?= $data['uuid'] ?>></td>
          </tr>
          <tr>
            <th>Eメールアドレス<br/>（メールアドレスのみ入力）</th>
            <td><input type="text" name="email" id="email" class="form-control" value=<?= $data['email'] ?> ></td>
          </tr>
        </tbody>
      </table>
      <p class="text-center">
        <input type="submit" name="submit" class="btn-submit btn btn-success" value="送信する">
        <a href="index.php" class="btn btn-warning" style="margin: 1rem;">一覧に戻る</a>
      </p>

    </form>
  </div>
</div>

<?php include "../layout/footer.php"; ?>
