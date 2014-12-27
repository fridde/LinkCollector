<?php

header('Content-Type: text/html; charset=utf-8');
/* inclusion of extra files */
$php_inc_files = array("inc/idiorm", "config", "inc/functions");
$js_inc_files = array(
	"//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js",
    "inc/jquery.dataTables.min.js",
	"//cdn.datatables.net/responsive/1.0.1/js/dataTables.responsive.js",
	"inc/dataTables.fixedHeader.js", 
	"inc/datatables_init.js");

foreach ($php_inc_files as $file) {
    include $file . '.php';
}




/* configuration using config.php */

ORM::configure('mysql:host=' . $db_host . ';dbname=' . $db_name);
ORM::configure('username', $db_username);
ORM::configure('password', $db_password);
