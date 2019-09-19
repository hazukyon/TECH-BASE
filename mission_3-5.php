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
//入力フォームからポスト送信しphp で受け取る
	if(!empty($_POST["name"]) && !empty($_POST["comment"]) && 
	!empty($_POST["pass"]) && empty($_POST["editver"])){
		$name = $_POST["name"];
		$comment = $_POST["comment"];
		$pass = $_POST["pass"];
		date_default_timezone_set("Asia/Tokyo");
		$date = date("Y/m/d H:i:s");
		$filename = "mission_3-5.txt";//テキスト保存
		$num=1;
		if(file_exists($filename)){
		$arr=file($filename);
		 if(!empty($arr)){
  		$toukou = explode("< >", $arr[count($arr) - 1]);
 		 $num = $toukou[0] + 1;
		 }
		}//投稿番号を取得
		$fp = fopen($filename, "a");
		fwrite($fp, $num . "< >");
		fwrite($fp, $name . "< >");
		fwrite($fp, $comment . "< >");
		fwrite($fp, $date . "< >");
		fwrite($fp, $pass . "< >" . PHP_EOL);
		fclose($fp);
	}
		?>
		
		<?php
	//削除フォーム
	if(!empty($_POST["delete"])){//削除番号の送信
		$filename = "mission_3-5.txt";
		$delete = $_POST["delete"];
		$lines = file($filename, FILE_IGNORE_NEW_LINES);//中身を表示
			$fp = fopen($filename, "w");
		for ($i = 0; $i <count($lines); $i++){
			$arr = explode("< >", $lines[$i]);//ブラウザ上に< >なしで表示する
			if ($arr[0] !==$delete){//投稿番号が一致しないとき
					fwrite($fp, $lines[$i] . PHP_EOL);
		}elseif($arr[4] !== $_POST["delpass"]){
			echo "パスワードが違います";
			fwrite($fp,$lines[$i] . PHP_EOL);
		}
		}
			fclose($fp);
	}
	

			?>
			
			<?php
			//編集フォーム
			if(!empty($_POST["edit"])){//編集番号の送信
			$filename = "mission_3-5.txt";
			$edit = $_POST["edit"];
			$lines = file($filename);//中身を表示
			foreach ($lines as $row){
			$arr = explode("< >" , $row, -1);
	if($edit == $arr[0] && $_POST["edipass"] == $arr[4]){
				$newname = $arr[1];
				$newcomment = $arr[2];
	}elseif($edit == $arr[0] && $_POST["edipass"] !== $arr[4]){
echo "パスワードが違います";
	$newname = "";
	$newcomment = "";
}
}
}

			
		if(!empty($_POST["editver"]) && !empty($_POST["put"])){
			$filename = "mission_3-5.txt";
				$lines = file($filename, FILE_IGNORE_NEW_LINES);//中身を表示
				$fp = fopen($filename, "w");
						for ($i = 0; $i <count($lines); $i++){
						$arr = explode("< >", $lines[$i]);//ブラウザ上に< >なしで表示する
						$editver = $_POST["editver"];//編集フォームの送信したもの
		if(!empty($_POST["pass"])){
			if ($arr[0] !== $editver){//投稿番号が一致しないとき
				fwrite($fp, $lines[$i] . PHP_EOL);
							}
			else{
		date_default_timezone_set("Asia/Tokyo");
		$date = date("Y/m/d H:i:s");
		$filename = "mission_3-5.txt";//テキスト保存
		 if(!empty($lines)){
		fwrite($fp, $arr[0] . "< >");
		fwrite($fp, $_POST["name"] . "< >");
		fwrite($fp, $_POST["comment"] . "< >");
		fwrite($fp, $date . "< >");
		fwrite($fp, $_POST["pass"] . "< >" . PHP_EOL);
	}
	}
		}else{
			$passlost = "パスワードを入れてください" ;
			fwrite($fp, $lines[$i] . PHP_EOL);
				$newname = $arr[1];
				$newcomment = $arr[2];
		}
		}
			fclose($fp);
	if(empty($_POST["pass"])){	
		echo $passlost;
		}
		}
		
						?>
						
<!-名前、コメント入力欄と送信ボタン、削除番号入力欄と削除ボタンのフォーム->
	<form action = "mission_3-5.php" method = "post">
	
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
	$filename = "mission_3-5.txt";
	if(file_exists($filename)){
	$lines = file($filename, FILE_IGNORE_NEW_LINES);//中身を表示
		foreach($lines as $word){
		$ray = explode("< >" , $word);//ブラウザ上に< >なしで表示する
		print_r($ray[0] . " ". $ray[1] . " " . $ray [2] ." " . $ray[3] ."<br>");
			}
	}
			?>
	</form>
	</body>
</html>