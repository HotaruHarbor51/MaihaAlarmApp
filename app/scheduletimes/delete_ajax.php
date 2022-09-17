<?php
  require "../database.php";

  if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // GET送信された場合は強制終了させます。
    die('error');
  }

  try{
    $connection = db(); //DB接続情報を取得
    $id=$_POST['schedule_id']; // 削除対象のIDを取得
    $sql = "DELETE FROM develop.scheduletimes WHERE id = :id"; // 削除用のクエリを設定

    $statement = $connection->prepare($sql);
    $statement->bindValue(':id', $id);
    $statement->execute(); // 削除処理を実行

  } catch(PDOException $e) {
    // エラーが発生すると201を返す
    echo json_encode(array("statusCode"=>201));

  } finally {
    // DB接続を閉じる
    $connection = null;
  }
  echo json_encode(array("statusCode"=>200));
?>