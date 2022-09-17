<?php

// 保存処理
// 編集時はidが引数に入る
function saveData($id = null) {

  // エラー内容
  $errors = [];

  // バリデーション
  if(!$_POST['title']) {
    $errors[] = 'タイトルは必須項目です。';
  }
  if(!$_POST['noticetime']) {
    $errors[] = 'アラーム設定時間は必須項目です。';
  }
  if(!$_POST['notification_method']) {
    $errors[] = '通知方法は必須項目です。';
  }

  if(empty($errors)){
    try {
      // 追加処理
      $new_schedule = array(
        "title"               => $_POST['title'],
        "noticetime"          => $_POST['noticetime'],
        "advancenotice"       => $_POST['advancenotice'],
        "advancetime"         => $_POST['advancenotice'] ? date("Y-m-d H:i", strtotime($_POST['noticetime']." -".$_POST['advancenotice']." minute")) : null,
        "notification_method" => $_POST['notification_method'],
        "description"         => $_POST['description'],
        "uuid"                => $_POST['uuid'],
        "email"               => $_POST['email'],
      );
      $connection = db(); // データベース取得

      $params = [];
      if($id) {
        $tmp = [];
        foreach($new_schedule as $key => $value) {
          $tmp[] = $key."=:".$key;
          $params[":".$key] = $value;
        }
        $params[":id"] = $id;
        $sql = "UPDATE develop.scheduletimes SET ".implode(',', $tmp)." WHERE id = :id";

      }else {
        $sql = sprintf(
          "INSERT INTO %s (%s) values (%s)",
          "develop.scheduletimes",
          implode(", ", array_keys($new_schedule)),
          ":" . implode(", :", array_keys($new_schedule))
        );
      }

      $statement = $connection->prepare($sql);
      if($id) {
        $statement->execute($params);
      } else {
        $statement->execute($new_schedule);
      }

    } catch(PDOException $error) {
      echo $sql . "<br>" . $error->getMessage();
    }
    // データベースの接続を切る
    $connection = null;
    return $statement;

  } else {
    // エラーメッセージがあった場合はエラーメッセージを返す。
    return $errors;
  }
}

?>