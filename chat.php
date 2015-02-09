<?php
if ($_REQUEST[res]==0){
	echo $_REQUEST[user];
	
	include 'source/srctodb.php';
	echo $fecha_actual;
	echo $hora_actual;
	
	
	
	
	$sqlap="SELECT MAX(IDCHAT) IDC FROM ICC_CHAT";
	//echo $sqlap;
	$resap=mysql_query($sqlap);
	if(mysql_num_rows($resap)>=1){
		while ($row=mysql_fetch_array($resap)){
			$idchat=$row[IDC]+1;
		}
	}	
	$sqlapc="INSERT INTO ICC_CHAT  (IDCHAT,USUARIO,FECHA_INICIO,ESTADO) values (".$idchat.",'".$_REQUEST[user]."','".$fecha_actual."','En Espera')";
	echo $sqlapc;
	$resap=mysql_query($sqlapc);
	mysql_query("COMMIT",$conex);
	$sqlapd="INSERT INTO ICC_DESC_CHAT  (IDCHAT,MENSAJE,SOLICITANTE,FECHA) values (".$idchat.",'BIENVENIDO AL CHAT DE ICC','SYSTEM','".$fecha_actual."')";
	echo $sqlapd;
	$resap=mysql_query($sqlapd);
	mysql_query("COMMIT",$conex);
	
	
	
	
	$url= "chat.php?user=".$_REQUEST[user]."&res=1&idchat=".$idchat;
?>	
	<script>
			window.location.href = "<?php echo $url;?>";
			</script>
<?php 
}else{
	session_start();
	$_SESSION[user]=$_REQUEST[user];
	$_SESSION[idchat]=$_REQUEST[idchat];
	
	
	echo "<table><tr><td colspan=2><form action='' method='POST' enctype='multipart/form-data'>
	<label for='imagen'>Imagen:</label>
	<input type='file' name='imagen' id='imagen' />
	<input type='submit' name='subir' value='Subir'/>
</form></td></tr>";
	echo "<tr><form method='POST'><td><input type='text' name='mensaje' size=80%></td><td><input type='submit' name='btn_envio' value='Entrar'></td></form></tr>";
	echo "<tr><td colspan=2>
	<iframe src='detchat.php' width='390' height='300' marginheight='0' marginwidth='0' noresize scrolling='Si' frameborder='0'> 
</iframe>
	</td></tr></table>";
	if (isset($_POST['btn_envio'])){
	include 'source/srctodb.php';
		$sqlapd="INSERT INTO ICC_DESC_CHAT  (IDCHAT,MENSAJE,SOLICITANTE,FECHA) values (".$_SESSION[idchat].",'".$_POST['mensaje']."','CLIENTE','".$fecha_actual."')";
	$resap=mysql_query($sqlapd);
	mysql_query("COMMIT",$conex);
	}
	if (isset($_POST['subir'])){
	if ($_FILES["imagen"]["error"] > 0){
	echo "ha ocurrido un error";
} else {
	session_start();
	//ahora vamos a verificar si el tipo de archivo es un tipo de imagen permitido.
	//y que el tamano del archivo no exceda los 100kb
	$permitidos = array("image/jpg", "image/jpeg", "image/gif", "image/png");
	$limite_kb = 1000;

	if (in_array($_FILES['imagen']['type'], $permitidos) && $_FILES['imagen']['size'] <= $limite_kb * 1024){
		//esta es la ruta donde copiaremos la imagen
		//recuerden que deben crear un directorio con este mismo nombre
		//en el mismo lugar donde se encuentra el archivo subir.php
		$ruta = "attachements/".$_SESSION[idchat].$_FILES['imagen']['name'];
		//comprovamos si este archivo existe para no volverlo a copiar.
		//pero si quieren pueden obviar esto si no es necesario.
		//o pueden darle otro nombre para que no sobreescriba el actual.
		if (!file_exists($ruta)){
			//aqui movemos el archivo desde la ruta temporal a nuestra ruta
			//usamos la variable $resultado para almacenar el resultado del proceso de mover el archivo
			//almacenara true o false
			$resultado = @move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta);
			
			if ($resultado){
				echo "el archivo ha sido movido exitosamente<br>";
				include 'source/srctodb.php';
				$ruta1 = "../cliente/attachements/".$_SESSION[idchat].$_FILES['imagen']['name'];
				$mensaje="<a href=".$ruta1." target=_blank>Imagen Adjunta</a>";
				echo "$mensaje<br>";
				$sqlapd="INSERT INTO ICC_DESC_CHAT  (IDCHAT,MENSAJE,SOLICITANTE,FECHA) values (".$_SESSION[idchat].",'".$mensaje."','CLIENTE','".$fecha_actual."')";
				echo $sqlapd;
				$resap=mysql_query($sqlapd);
				echo "yes";
				mysql_query("COMMIT",$conex);
			} else {
				echo "ocurrio un error al mover el archivo.";
			}
		} else {
			echo $_FILES['imagen']['name'] . ", este archivo existe";
		}
	} else {
		echo "archivo no permitido, es tipo de archivo prohibido o excede el tamano de $limite_kb Kilobytes";
	}
}
	}
} 

?>
