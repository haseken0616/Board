<?php
//データベース接続
$dsn = 'データベース名';
$users = 'ユーザー名';
$password ='パスワード';
$pdo = new PDO($dsn, $users, $password);
//テーブル作成(ID,名前,コメント,日付)
$sql = "CREATE TABLE IF NOT EXISTS Board_mission4"
."("
."id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,"
."name char(32),"
."comment TEXT,"
."date DATETIME,"
."password char(32)"
.");";
$stmt = $pdo -> query($sql);

?>


<?php
if(!empty($_POST["delete"])){
  $sql = 'SELECT * FROM Board_mission4 order by id';
  $results = $pdo -> query($sql);

  foreach( $results ->fetchAll() as $row){
    if($_POST["delete"]==$row['id']){
      if($_POST["password2"]==$row['password']){
        $id = $_POST["delete"];
        $sql = "delete from Board_mission4 where id = $id";
        $result = $pdo -> query($sql);
       }
       else{
        echo "パスワードが違います。削除できません";
       }
    }
  }
}

if(!empty($_POST["edit"])){//編集用投稿
  if(!empty($_POST["name"]) && !empty($_POST["comment"])){
    $sql = 'SELECT * FROM Board_mission4 order by id';
    $results = $pdo -> query($sql);
    foreach( $results ->fetchAll() as $row){
      if($_POST["edit"]==$row["id"]){
        if($_POST["password3"]==$row['password']){
          $id = $_POST["edit"];
          $new_name = $_POST["name"];
          $new_comment = $_POST["comment"];
          $sql = "update Board_mission4 set name ='$new_name' , comment='$new_comment'where id = $id";
          $result = $pdo -> query($sql);
        }
        else{
          echo "パスワードが違います。編集できません";
        }
      }
    }
   }
   else if(!empty($_POST["name"])){
     echo "コメントを入力してください";
   }
   else if(!empty($_POST["comment"])){
     echo "名前を入力してください";
   }
   else{
     echo "名前とコメントを入力してください";
   }
}
else{//通常の新規投稿
  if(!empty($_POST["name"])&&!empty($_POST["comment"])){
    //select count(id) from Board_mission4 
    $sql = $pdo -> prepare("INSERT INTO Board_mission4(name,comment,date,password) VALUES(:name,:comment,cast(now()as datetime),:password)");
    $sql -> bindParam(':name',$_POST["name"],PDO::PARAM_STR);
    $sql -> bindParam(':comment',$_POST["comment"],PDO::PARAM_STR);
    $sql -> bindParam(':password',$_POST["password1"],PDO::PARAM_STR);
    $sql -> execute();
  }
  else if(!empty($_POST["name"])){
    echo "コメントを入力してください";
  }
  else if(!empty($_POST["comment"])){
    echo "名前を入力してください";
  }
}
?>


<html>
<meta http-equiv = "content-type" charset="UTF-8">
<form method="post" action="mission_4.php">
<title>掲示板</title>
<p>新規投稿：名前、コメント、パスワードを入力<br />
<p>編集：編集したい番号と対応するパスワードを編集フォームに、書き換えたい内容を名前とコメントに入力<br />
<p>削除：削除したい番号、パスワードを入力<br /></p>


<p><input type="text" value = "<?php echo $name2;?>" name="name" placeholder="名前">
<p><input type="text" value = "<?php echo $commen2t;?>" name="comment" placeholder="コメント">
<p><input type="text" value = "<?php echo $pass1;?>"name="password1" placeholder="パスワード">
<input type="submit" value="送信する"></p><br>

<p><input type="text" value = "<?php echo $delete;?>" name="delete" placeholder="削除対象番号">
<p><input type="text" value = "<?php echo $pass2;?>"name="password2" placeholder="パスワード">
<input type="submit" value="削除"></p><br>

<p><input type="text" value = "<?php echo $edit;?>" name="edit" placeholder="編集対象番号">
<p><input type="text" value = "<?php echo $pass3;?>"name="password3" placeholder="パスワード">
<input type="submit" value="編集"></p>


</form>
</html>


<?php
//データの表示
$sql = 'SELECT * FROM Board_mission4 order by id';
$results = $pdo -> query($sql);

echo "以下に投稿が表示されます<br>";
  foreach( $results ->fetchAll() as $row){
    echo $row['id'].':';
    echo $row['name'].':';
    echo $row['comment'].':';
    echo $row['date'].'<br>';
  }


?>
