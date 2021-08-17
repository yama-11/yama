<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
    
    <style>body {
           font-family: monospace
           
        }</style>

</head>

    <body bgcolor="#778899" text="f5f5f5" >
            <br>
    
    <u><h1><center> 掲示板 </center></h1></u>
    <h4><center>好きな◯○を教えてください！</center></h4>
    <?php

    $dsn='データベース名';
    $user = 'ユーザ名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    //テーブル作成
    //$sql = "CREATE TABLE IF NOT EXISTS boarddb"
    //." ("
    //. "id INT AUTO_INCREMENT PRIMARY KEY,"
    //. "name char(30),"
    //. "comment TEXT,"
    //. "date DATETIME,"
    //. "pass char(30)"
    //.");";
    //$stmt = $pdo-> query($sql);
    
    
    //ini_set('display_errors', 0);

  
  //編集番号なし, 名前コメントあり 
if(!empty($_POST["name"]) && !empty($_POST["txt"]) && !empty($_POST["pass"])){ 
    if(empty($_POST["editnumber"])){
        
        $sql = $pdo -> prepare("INSERT INTO boarddb (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        
        $sql -> bindParam(':date',$date , PDO::PARAM_STR);
        $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);

        $name = $_POST["name"];
        $comment = $_POST["txt"]; 
        $date = date("Y/m/d h:i:s");
        $pass = $_POST["pass"];
        $sql -> execute();

    }else{ //編集番号有
      $sql = 'SELECT * FROM boarddb';
      $stmt = $pdo->query($sql);
      $results = $stmt->fetchAll();
      foreach ($results as $row) {
        if($_POST["editnumber"]==$row['id']){
            $id = $_POST["editnumber"];
                $name = $_POST["name"];
                $comment = $_POST["txt"]; 
                $date = date("Y/m/d h:i:s");
                $pass = $_POST["pass"];
                $sql = 'UPDATE boarddb SET name=:name,comment=:comment,date=:date,pass=:pass WHERE id=:id';

                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt -> bindParam(':date', $date, PDO::PARAM_STR);
                $stmt -> bindParam(':pass', $pass, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
        }
    }
}
}

//削除
if(!empty($_POST["desubmit"]) && !empty($_POST["depass"])){
    $sql = 'SELECT * FROM boarddb';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row) {
        if($_POST["delete"]==$row['id'] && $_POST["depass"]==$row['pass']){
            $id = $_POST["delete"];
            $sql = 'delete from boarddb where id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }
    }
}
//編集
if(!empty($_POST["edit"]) && !empty($_POST["editpass"])){
    $sql = 'SELECT * FROM boarddb';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row) {
        if($_POST["edit"]==$row['id'] && $_POST["editpass"]==$row['pass']){
            //フォームのお名前とコメント（id,nameとtxt）に編集対象の内容を渡す
            $editnum=$row['id'];
            $editt=$row['name'];
            $ei=$row['comment'];
        }
    }
}
?>

<center>
    <form action="" method="post">
        <input type="hidden" name="editnumber" value="<?php if(!empty($editnum)){ echo $editnum;} ?>"> 
        <input type="text" name="name" value="<?php if(!empty($editt)){ echo $editt;}?>" placeholder="名前" ><br>
        <input type="text" name="txt" value="<?php if(!empty($ei)){ echo $ei;}?>" placeholder="コメント" ><br>
        <input type="text" name="pass" placeholder="パスワード"><br>
        <input type="submit" name="submit" value="送信" ><br>
    </form>

    <form action="" method="post">
        <input type="text" name="delete" placeholder="削除したい番号"><br>
        <input type="text" name="depass" placeholder="パスワード"><br>
        <input type="submit" name="desubmit" value="削除" ><br>
    </form>

    <form action="" method="post">
        <input type="text" name="edit" placeholder="編集したい番号"><br>
        <input type="text" name="editpass" placeholder="パスワード"><br>
        <input type="submit" name="editsubmit" value="編集" ><br>
    </form>

</center>
<?php
//表示   
$sql = 'SELECT * FROM boarddb';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row){
    echo $row['id'].' , ';
    echo $row['name'].' , ';
    echo $row['comment'].' , ';
    echo $row['date'].'<br>';
echo '<hr class="line">';
}
?>


<br>
<br>

</body>
</html>