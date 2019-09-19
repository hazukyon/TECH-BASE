<!DOCTYPE html>
<html lang = “ja”>
	<head>
	<meta charset="utf-8">
	</head>
	<body>
	<!-名前、コメント入力欄と送信ボタン、削除番号入力欄と削除ボタンのフォーム->
	<form action = "mission_3-3.php" method = "post">
	名前:<br />
	<input type="text" name="name" size="30" value="" /><br />
	コメント:<br />
	<input type="text" name="comment" size="30" value="" /><br />
	<input type="submit" value="送信"><br />
	削除番号入力:<br />
	<input number="text" name="delete" size="30" value="" /><br />
	<input type="submit" value="削除">
	<br>
	
		<?php
//入力フォームからポスト送信しphp で受け取る
	if(!empty($_POST["name"]) . !empty($_POST["comment"])){
		$name = $_POST["name"];
		$comment = $_POST["comment"];
		date_default_timezone_set("Asia/Tokyo");
		$date = date("Y/m/d H:i:s") . PHP_EOL;
		$filename = "mission_3-3.txt";//テキスト保存
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
		fwrite($fp, $date);
		fclose($fp);
		$lines = file($filename, FILE_IGNORE_NEW_LINES);//中身を表示
		foreach($lines as $word){
		$ray = explode("< >" , $word);//ブラウザ上に< >なしで表示する
		print_r($ray[0] . " ". $ray[1] . " " . $ray [2] ." " . $ray[3] . "<br>");
			}
	}
		?>
		
		<?php
	//削除フォーム
	if(!empty($_POST["delete"])){//削除番号の送信
		$filename = "mission_3-3.txt";
		$delete = $_POST["delete"];
		$lines = file($filename, FILE_IGNORE_NEW_LINES);//中身を表示
			$fp = fopen($filename, "w");
		for ($i = 0; $i <count($lines); $i++){
			$arr = explode("< >", $lines[$i]);//ブラウザ上に< >なしで表示する
			if ($arr[0] !==$delete){//投稿番号が一致しないとき
					fwrite($fp, $lines[$i] . PHP_EOL);
		}
		}
		$lines = file($filename, FILE_IGNORE_NEW_LINES);//中身を表示
				foreach($lines as $word){
 				$arr = explode("< >", $word);//ブラウザ上に< >なしで表示する
		print_r($arr[0] . " ". $arr[1] . " " . $arr [2] ." " . $arr[3] . "<br>");
		}
		fclose($fp);
	}
			?>
	</form>
	</body>
</html>