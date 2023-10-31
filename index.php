<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title></title>
</head>
<body>

<?php
  $ediBango = "";
  $ediName = "";
  $ediComment = "";
//データベース接続
 $dsn = 'mysql:dbname=tb250371db;host=localhost';
  $user = 'tb-250371';
  $password = 'Bh9xFrhuwf';
  $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
//テーブル削除
  $sql ='DROP TABLES IF EXISTS tbtest';
  $stmt = $pdo -> query($sql);
//テーブル作成
  $sql = "CREATE TABLE IF NOT EXISTS tbtest"
  ." ("
  . "id INT AUTO_INCREMENT PRIMARY KEY,"
  . "name char(32),"
  . "comment TEXT,"
  . "date TEXT"
  .");";
  $stmt = $pdo->query($sql);
//4-3 テーブル一覧を表示
  $sql ='SHOW TABLES';
  $result = $pdo -> query($sql);
  foreach ($result as $row){
          echo $row[0];
          echo '<br>';
  }
  echo "<hr>";
//4-4 テーブルの中身を確認
  $sql ='SHOW CREATE TABLE tbtest';
  $result = $pdo -> query($sql);
  foreach ($result as $row){
          echo $row[1];
  }
  echo "<hr>";
//編集選択
  if(!empty($_POST['edit'])){
    if(!empty($_POST['ediPass'])){
      $edit = $_POST['editNo'];
      $pass = $_POST['ediPass'];
//4-6  データの取得
      $sql = 'SELECT * FROM tbtest';
      $stmt = $pdo->query($sql);
      $results = $stmt->fetchAll();
      foreach ($results as $row){
        if($results == ""){
          continue;
        } elseif($row['id'] == $edit){
            $ediBango = $row['id'];
            $ediName = $row['name'];
            $ediComment = $row['comment'];
          }
      }
    } else {
          echo "パスワードを入力してください。<br>";
      }
}

//送信ボタンが押された時
  if(!empty($_POST['send'])){
//パスワード確認
    if(!empty($_POST['comPass'])){
      $pass = $_POST['comPass'];
      $name = $_POST['name'];
      $comment = $_POST['comment'];
      $date = date("Y/m/d H:i:s");
//編集機能
//編集番号が送信された時
      if(!empty($_POST['check'])){
        $id = $_POST['check'];
        $sql = 'update tbtest set name=:name,comment=:comment,date=:date where id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
      } else {
//4-5 データ入力
          $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment, date) VALUES (:name, :comment, :date)");
          $sql -> bindParam(':name', $name, PDO::PARAM_STR);
          $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
          $sql -> bindParam(':date', $date, PDO::PARAM_STR);
          $sql -> execute();
        }
    } else {
          echo "パスワードを入力してください。<br>";
      }
}

//削除機能
  if(!empty($_POST['delete'])){
//パスワードが入力されていたら
    if(!empty($_POST['delPass'])){
//4-8 削除
      $id = $_POST['deleteNo'];
      $sql = 'delete from tbtest where id=:id';
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
    } else {
          echo "パスワードを入力してください。<br>";
      }
  }
?>

  <form method="POST" action="mission_5.php">
      <input type="hidden" name="check" value="<?php echo $ediBango ?>"><!-- 編集番号を取得 --!>
    <div>
      <input type="text" name="name" value="<?php echo $ediName; ?>">名前<br> <!-- value属性にphp領域を指定 --!>
    </div>
    <div>
      <input type="text" name="comment" value="<?php echo $ediComment; ?>">コメント<br> <!-- value属性にphp領域を指定 --!>
      <input type="password" name="comPass">パスワード<br>
      <input type="submit" name="send" value="送信">
    </div>

    <div>
      <input type="text" name="deleteNo">削除対象番号<br>
      <input type="password" name="delPass">パスワード<br>
      <input type="submit" name="delete" value="削除">
    </div>

<!-- 編集番号指定用フォームを作る -->

    <div>
      <input type="text" name="editNo">編集対象番号<br>
      <input type="password" name="ediPass">パスワード<br>
      <input type="submit" name="edit" value="編集">
    </div>
  </form>

<?php
//4-6  データの取得
  $sql = 'SELECT * FROM tbtest';
  $stmt = $pdo->query($sql);
  $results = $stmt->fetchAll();
  foreach ($results as $row){
          echo $row['id'].',';
          echo $row['name'].',';
          echo $row['comment'].',';
          echo $row['date'].'<br>';
          echo "<hr>";
  }
?>

</body>
</html>
