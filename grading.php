<?php
 	session_start();
	session_regenerate_id(true);
	if(isset($_SESSION['login'])==false){
		print'ログインされていません。<br />';
		print'<a href="student_login.html">ログイン画面へ</a>';
		exit();
	}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>ファイルのアップロード</title>
</head>
<body>

<?php
	$dsn='mysql:dbname=file_upload;host=localhost';
	$user='root';
	$password='tomo8080';
	//$user='koba3327';
	//$password='TBU1314-2';
	$dbh=new PDO($dsn,$user,$password);
	$dbh->query('SET NAMES utf8');

	//自身が採点したかどうか
	$sql='SELECT student_code FROM grading_data1 WHERE student_code=? AND subjects_code=?';
	$stmt=$dbh->prepare($sql);
	$data[0]=$_SESSION['student_code'];
	$data[1]=$_SESSION['subjects_code'];
	$stmt->execute($data);
	$rec=$stmt->fetch(PDO::FETCH_ASSOC);
	if($rec){
		print 'すでに採点しています。';
		print'<input  type="button"onclick="history.back()"value="戻る">';
		exit();
	}

	//学籍番号を取得
	$sql='SELECT student_code FROM student_data WHERE student_code!=?';
	$stmt=$dbh->prepare($sql);
	array_splice($data, 1, 1);
	$stmt->execute($data);
	$rec1=$stmt->fetchALL(PDO::FETCH_ASSOC);
	//採点された学籍番号を取得
	$sql='SELECT answer_stu_code FROM grading_data1 WHERE answer_stu_code!=? AND subjects_code=?';
	$stmt=$dbh->prepare($sql);
	$data[1]=$_SESSION['subjects_code'];
	$stmt->execute($data);
	$rec2=$stmt->fetchALL(PDO::FETCH_ASSOC);

	//学籍番号ごとの回答状況
	//配列を作成し初期化
	for($i=0;$i<Count($rec1);$i++){
		$ans_stu[$rec1[$i]['student_code']]=0;
	}
	//格納
	for($i=0;$i<Count($rec2);$i++){
		$ans_stu[$rec2[$i]['answer_stu_code']]++;
	}

	$count_zero=0;
	$count_one=0;
	$count_two=0;
	for($i=0;$i<Count($rec1);$i++){
		if($ans_stu[$rec1[$i]['student_code']]==0){
			$ans_zero[$count_zero]=$rec1[$i]['student_code'];
			$count_zero++;
		}
		if($ans_stu[$rec1[$i]['student_code']]==1){
			$ans_one[$count_one]=$rec1[$i]['student_code'];
			$count_one++;
		}
		if($ans_stu[$rec1[$i]['student_code']]==2){
			$ans_two[$count_two]=$rec1[$i]['student_code'];
			$count_two++;
		}
	}
	$count=0;
	if($count_zero>0){
		$size=$count_zero;
		for($i=0;$i<$size;$i++){
			$students[$i]=$i;
		}
		for($i=0;$i<$count_zero;$i++){
			$random=rand(0,$size-1);
			$select[$count]=$ans_zero[$students[$random]];
			$students[$random]=$students[$size-1];
			$count++;
			$size--;
			if($count==3||$size==0){
				break;
			}
		}
		if($count<3){
			if($count_one>0){
				$size=$count_one;
				for($i=0;$i<$size;$i++){
					$students[$i]=$i;
				}
				for($i=0;$i<$count_one;$i++){
					$random=rand(0,$size-1);
					$select[$count]=$ans_one[$students[$random]];
					$students[$random]=$students[$size-1];
					$count++;
					$size--;
					if($count==3||$size==0){
						break;
					}
				}
			}
		}
	}else{
		if($count_one>0){
			$size=$count_one;
			for($i=0;$i<$size;$i++){
				$students[$i]=$i;
			}
			for($i=0;$i<$count_one;$i++){
				$random=rand(0,$size-1);
				$select[$count]=$ans_one[$students[$random]];
				$students[$random]=$students[$size-1];
				$count++;
				$size--;
				if($count==3||$size==0){
					break;
				}
			}
			if($count<3){
				if($count_two>0){
					$size=$count_two;
					for($i=0;$i<$size;$i++){
						$students[$i]=$i;
					}
					for($i=0;$i<$count_two;$i++){
						$random=rand(0,$size-1);
						$select[$count]=$ans_two[$students[$random]];
						$students[$random]=$students[$size-1];
						$count++;
						$size--;
						if($count==3||$size==0){
							break;
						}
					}
				}
			}
		}else{
			if($count_two>0){
				$size=$count_two;
				for($i=0;$i<$size;$i++){
					$students[$i]=$i;
				}
				for($i=0;$i<$count_two;$i++){
					$random=rand(0,$size-1);
					$select[$count]=$ans_two[$students[$random]];
					$students[$random]=$students[$size-1];
					$count++;
					$size--;
					//回答していない人が最後の一人になるときに採点されてない課題がその人の課題になることを防ぐ処理
					if(4<$count_two&&$count_two<=6&&$count==2){
						//採点していない人を探す
						$sql='SELECT DISTINCT student_code FROM grading_data1 WHERE student_code!=? AND subjects_code=?';
						$stmt=$dbh->prepare($sql);
						$stmt->execute($data);
						$rec3=$stmt->fetchALL(PDO::FETCH_ASSOC);//採点した人
						$rec3_size=count($rec3)-1;
						$no_stu=0;
						for($j=0;$j<count($rec1);$j++){
							for($k=0;$k<count($rec3);$k++){
								if($rec1[$j]['student_code']==$rec3[$k]['student_code']){
									break;
								}
								if($k==$rec3_size){
									$no_stu=$rec1[$j]['student_code'];//採点していない人
								}
							}
							if($no-stu!=0){
								break;
							}
						}
						for($j=0;$j<$count_two;$j++){
							if($no-stu==$ans_two[$j]&&$no-stu!=$select[0]&&$no-stu!=$select[1]){
								$select[$count]=$ans_two[$j];
								$count++;
								$size--;
								break;
							}
						}
					}
					if($count==3||$size==0){
						break;
					}
				}
			}
		}
	}

	// ディレクトリパス
	$dir = "C:/xampp/htdocs/file_upload/files1" ;
	//$dir = "./files1" 
	$files1 = scandir($dir);

	$dir = "C:/xampp/htdocs/file_upload/files2" ;
	//$dir = "./files2" 
	$files2 = scandir($dir);

	$files2[Count($files2)-1] = mb_convert_encoding($files2[Count($files2)-1], "UTF-8", "SJIS");
	$last_filenum=mb_substr($files2[Count($files2)-1],0,2);

	$number=0;
	if(ctype_digit($last_filenum)){
		$number=intval($last_filenum);		
	}
	$count=1;
	for($i=2;$i<Count($files1);$i++){
		$files1[$i] = mb_convert_encoding($files1[$i], "UTF-8", "SJIS");
		$stucode=mb_substr($files1[$i],0,6);
		for($j=0;$j<3;$j++){
			if($select[$j]==$stucode){
print $select[$j];
				$number++;
				if($number<10){
					$str_num='0'.strval($number);
				}else{
					$str_num=strval($number);
				}
				$newname=$str_num.mb_substr($files1[$i],6,strlen($files1[$i])-1);
				copy(mb_convert_encoding("files1/".$files1[$i], "SJIS", "AUTO"),mb_convert_encoding("files2/".$newname, "SJIS", "AUTO"));
				//rename(mb_convert_encoding("files/".$files[$i], "SJIS", "AUTO"),mb_convert_encoding("files/".$newname, "SJIS", "AUTO"));
				//rename("files/".$files[$i],"files/".$newname);
				print'<button onclick="window.open(\'files2/'.$newname.'\',\'_blank\', \'location=no\')">生徒の解答'.$count.'</button>　　　';	
				//rename(mb_convert_encoding("files/".$newname, "SJIS", "AUTO"),mb_convert_encoding("files/".$files[$i], "SJIS", "AUTO"));
				//rename("files/".$newname,"files/".$files[$i]);
				$count++;
				break;
			}
		}
	}

	//データベース切断
	$dbh=null;

	for($i=0;$i<2;$i++){
		for($j=$i+1;$j<3;$j++){
			if($select[$i]>$select[$j]){
				$temp=$select[$i];
				$select[$i]=$select[$j];
				$select[$j]=$temp;
			}
		}
	}
	print'<br/>';
	print'<form method="post" action="grading_check.php">';
	for($i=1;$i<=3;$i++){
		print'採点'.$i.'<input type="number" name="grading'.$i.'" style="width:44px;" min="0" max="100" required>　　　';
		print '<input type="hidden" name="student'.$i.'" value="'.$select[$i-1].'">';
		print '<input type="hidden" name="file_num" value="'.$number.'">';
	}
	print'<br/><br/>';
	print'<input  type="button"onclick="history.back()"value="戻る">';
	print'　<input type="submit" value="送信">';
?>
</body>
</html>