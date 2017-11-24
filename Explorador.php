<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<title>Explorador de archivos</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>

<?php

	// Obtenemos la ruta a revisar, y la ruta anterior para volver...

	if($_GET["path"])
	{
		$path=$_GET["path"];
		$back=implode("/",explode("/",$_GET["path"],-2));
		if($back)
			$back.="/*";
		else
			$back="*";
	}else{
		$path="pdf/*";
	}
?>

<header>
	<h1>Explorador de archivos</h1>
</header>
<nav>
	<h2><?php echo $path?></h2>
</nav>

<section>
	<?php
	// si no estamos en la raiz, permitimos volver hacia atras
	//if($path!="*")
	//	echo "<div class='bold group'><a href='?path=".$back."'>...</a></div>";

	// devuelve el tipo mime de su extensión (desde PHP 5.3)
	$finfo1 = finfo_open(FILEINFO_MIME_TYPE);
	// devuelve la codificación mime del fichero (desde PHP 5.3)
	$finfo2 = finfo_open(FILEINFO_MIME_ENCODING);

	$folder=0;
	$file=0;
	# recorremos todos los archivos de la carpeta
	foreach (glob($path) as $filename)
	{
		$fileMime=finfo_file($finfo1, $filename);
		$fileEncoding=finfo_file($finfo2, $filename);
		if($fileMime=="directory")
		{
			$folder+=1;
			// Mostramos la carpeta y permitimos pulsar sobre la misma
			echo "<div class='directory group'>
				<a href='?path=".$filename."/*' class='name'>".end(explode("/",$filename))."</a>
				<div class='mime'>(".$fileEncoding.")</div>
			</div>";
		}else{
			$file+=1;
			// Mostramos la información del archivo
			echo "<div class='group'>
				<div class='size'>".number_format(filesize($filename)/1024,2,",",".")." Kb</div>
				<div class='name'>".end(explode("/",$filename))."</div>
				<div class='mime'>".$fileMime." (".$fileEncoding.") <a href=\"$filename\" target=\"_blank\">Descarga del archivo</a></div>
			</div>";
		}
	}

	finfo_close($finfo1);
	finfo_close($finfo2);
	?>
	<footer>
		<?php echo $folder?> carpeta/s y <?php echo $file?> archivo/s
	</footer>
</section>

</body>
</html>
