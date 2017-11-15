<?php 
header('Content-type:  application/pdf');
header('Content-Length: ' . filesize($file));
header('Content-Disposition: attachment; filename="download.pdf"');
readfile($file);
?>
