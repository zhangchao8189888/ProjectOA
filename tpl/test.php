<?php 
/** 导出excel文件
$name 导出文件名
$title  excel抬头
$array 导出内容数组
$抬头的数组的列数必须一致
*/
function export($name,$title,$array){
//内容的类型
		header("Content-Type:application/vnd.ms-excel");    
// 以附件形式保存  $name 默认保存时的文件名 
		header("Content-Disposition:attachment;filename=$name"); 
// 不缓存
		header("Pragma:no-cache");     
// 浏览器不缓存的时间 
		header("Expires:0"); 

// 循环输出 excel 抬头
		foreach($title as $value){ // excel抬头
			echo $value."\t";
		}
		echo "\r\n"; // 换行
// 循环输出 excel 内容
		foreach($array as $val){
			foreach($val as $v){
				echo $v."\t";
			}
			echo "\r\n"; // 换行
		}
	}

//  调用
$name='test.xls';
$title=Array('name','age','class');
$array=Array(
0=>1,
1=>2
);
export($name,$title,$array);
?>