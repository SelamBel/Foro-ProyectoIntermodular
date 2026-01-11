<?php function startHTML($titulo = 'Documento PHP') {
    echo "<!DOCTYPE html>\n<html lang='es'>\n<head>\n<meta charset='UTF-8'>\n<title>$$titulo</title>\n</head>\n<body>";
}

function endHTML() {
    echo "</body>\n</html>";
}
?>

<?php startHTML('Título del ejercicio'); ?>

<h1>Ejercicio PHP</h1>
<p>Contenido aquí...</p>


<?php endHTML(); ?>

<?php 


