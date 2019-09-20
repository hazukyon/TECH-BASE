<!DOCTYPE html>
<html lang = “ja”>
	<head>
	<meta charset="utf-8">
	</head>
	<body>
	<font size="6">なんでもコーナー</font><br>
	<font size="2">コメントお待ちしてます！</font><br>
	<hr>	

<?php

//$dsnの式の中にスペースを入れないこと！
// 4-1データベースへの接続
	$dsn = 'mysql:dbname=tb210320db;host=localhost';
	$user = 'tb-210320';
	$password = 'epFb9nhTnk';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
//4-2テーブル作成
	$sql = "CREATE TABLE IF NOT EXISTS msg
	 (
	id INT AUTO_INCREMENT PRIMARY KEY,
	name char(32),
	comment TEXT,
	nowtime char(32),
	pass char(32) 
	)";
	//カラム設定
		$stmt = $pdo->query($sql);
		
	
//入力フォームからポスト送信しphp で受け取る
	if(!empty($_POST["name"]) && !empty($_POST["comment"]) && 
	!empty($_POST["pass"]) && empty($_POST["editver"])){
	$name = $_POST["name"];
	$comment = $_POST["comment"]; 
	date_default_timezone_set("Asia/Tokyo");
	$nowtime = date("Y/m/d H:i:s");
	$pass = $_POST["pass"];
//4-5insertを用いてデータを入力
	$sql = $pdo -> prepare("INSERT INTO msg (name, comment,  nowtime, pass) VALUES ('$name', '$comment', '$nowtime', '$pass')");
	$sql -> bindParam(':name', $name, PDO::PARAM_STR);
	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	$sql -> bindParam(':nowtime', $nowtime, PDO::PARAM_STR);
	$sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
	$sql -> execute();
	echo "投稿しました";
	}
	
//削除フォーム
	if(!empty($_POST["delete"])){//削除番号の送信
	$delete = $_POST["delete"];
	$delpass = $_POST["delpass"];
	$sql = "SELECT pass FROM msg WHERE id = '$delete' ";
	//投稿番号のパスワードを検索
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		$pass = $row['pass'];
	}
		if($delpass ==$pass){//削除の実行
	$sql = "delete from msg where id='$delete' ";
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();
	echo "削除しました";
		}else{
			echo "パスワードが違います";
		}
	}
	
	
//編集フォーム、投稿フォームに表示するまで
if(!empty($_POST["edit"])){//編集番号の送信
//4-7updateで編集
$edit = $_POST["edit"];
$editpass = $_POST["edipass"];
$sql = "SELECT * FROM msg WHERE id='$edit' ";//変更する投稿番号を選択
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		$pass = $row['pass'] ;
		$editname = $row['name'];
		$editcomment =  $row['comment']; //変更したい名前、変更したいコメント
		$edit = $row['id'];	
				
	if($editpass == $pass){
		$newname = $row['name'];
		$newcomment = $row['comment'];
	}else{
		echo "パスワードが違います";
		$newname = "";
		$newcomment = "";
}
}
}

//送信して編集完了まで
if(!empty($_POST["editver"]) && !empty($_POST["put"])){
$editver = $_POST["editver"];
$id = $editver; //変更する投稿番号
	$name = $_POST["name"];
	$comment = $_POST["comment"]; //変更したい名前、変更したいコメントは自分で決めること
	date_default_timezone_set("Asia/Tokyo");
	$nowtime = date("Y/m/d H:i:s");
	$pass = $_POST["pass"];
	$sql = 'update msg set name=:name,comment=:comment, nowtime=:nowtime, pass=:pass where id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':name', $name, PDO::PARAM_STR);
	$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	$stmt->bindParam(':nowtime', $nowtime, PDO::PARAM_STR);
	$stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();
	echo "編集しました";
	if(empty($_POST["pass"])){
		echo "パスワードを入れてください";
		$edit = $_POST["edit"];
		$editpass = $_POST["edipass"];
		$sql = "SELECT * FROM msg WHERE id='$edit' ";//変更する投稿番号を選択
		$stmt = $pdo->query($sql);
		$results = $stmt->fetchAll();
		foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		$pass = $row['pass'] ;
		$editname = $row['name'];
		$editcomment =  $row['comment']; //変更したい名前、コメント、パス
		$edit = $row['id'];
		
		$newname = $row['name'];
		$newcomment = $row['comment'];//投稿フォームに再表示
	}
	}
}
?>
	
	
<!-名前、コメント入力欄と送信ボタン、削除番号入力欄と削除ボタンのフォーム->
	<form action = "mission_5-1.php" method = "post">
	
	【　投稿フォーム　】<br />
	名前:<br />
	<input type="text" name="name" size="30" value=<?php if(isset($newname)){ echo "$newname" ;} ?> ><br />
	コメント:<br />
	<input type="text" name="comment" size="30" value=<?php if(isset($newcomment)){ echo "$newcomment" ;} ?> ><br />
	パスワード:<br />
	<input type="password" name="pass" size="30" value=""><br />
	<input type="submit" name="put" value="送信"><br />
	<br />
	
	【　削除フォーム　】<br />
	削除番号入力:<br />
	<input number="text" name="delete" size="30" value="" /><br />
	パスワード:<br />
	<input type="password" name="delpass" size="30" value=""><br />
	<input type="submit" value="削除"><br />
	<br />
	
	【　編集フォーム　】<br />
	編集番号入力:<br />
	<input number="text" name="edit" size="30" value="" /><br />
	パスワード:<br />
	<input type="password" name="edipass" size="30" value=""><br />
	<input type="submit" value="編集"><br />
	<!-編集対象番号表示。のちにhidden->
	<input type="hidden" name="editver" value=<?php if(isset($edit)){echo "$edit";} ?> ><br />
	<hr>
	【　コメント一覧　】<br />
<?php
//4-6入力したデータを表示
$sql = 'SELECT * FROM msg';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].' ';
		echo $row['name'].' ';
		echo $row['comment'].' ';
		echo $row['nowtime'];
		echo  "<br>";
	}

	
?>
	</form>
	</body>
</html>