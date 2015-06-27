<?php

$DBUSER="wpcomclt_adminz";
$DBPASSWD="92sziEKNgDkHw";
$DATABASE="wpcomclt_adminz";

$filename = "backup-" . date("d-m-Y") . ".sql.gz";
$mime = "application/x-gzip";

header( "Content-Type: " . $mime );
header( 'Content-Disposition: attachment; filename="' . $filename . '"' );

$cmd = "mysqldump -u $DBUSER --password=$DBPASSWD $DATABASE | gzip --best";   

passthru( $cmd );

exit(0); 

?>