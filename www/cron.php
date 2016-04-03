<?php

	$host="sdnknu.mysql.ukraine.com.ua";
$user="sdnknu_efront";
$password="pxyt5t4h";
$db="sdnknu_efront";
mysql_connect($host, $user, $password) or die("MySQL сервер недоступен!".mysql_error());
mysql_select_db($db) or die("Нет соединения с БД".mysql_error());

$result = mysql_query("UPDATE `groups` SET `number_course`=number_course+1 WHERE number_course!=6 and number_course!=4 and number_course!=7");

if($result)
{
	echo "Всё норм";
}
else
{
	echo "Всё плохо";
}

?>