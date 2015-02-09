<meta http-equiv="refresh" content="2">
<?php
session_start();
include 'source/srctodb.php';

$sqlap="SELECT *  FROM ICC_DESC_CHAT WHERE IDCHAT =".$_SESSION[idchat];

$resap=mysql_query($sqlap);
if(mysql_num_rows($resap)>=1){
	while ($row=mysql_fetch_array($resap)){
		echo $row[MENSAJE]."--".$row[FECHA]."<br>";
	}
}

?>
