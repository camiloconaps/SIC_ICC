<?php
$conex=mysql_connect('190.24.138.149','codensa','c0d3ns4') or die('no hay conexion');
mysql_select_db('chat_atenea',$conex);

//determinar la hora de conexion
$fecha_actual = date('Y-m-d H:i:s');
$hora_actual = date('H:i:s');
?>
		
