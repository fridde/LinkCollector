<?php

header('Content-Type: text/html; charset=utf-8');
/* inclusion of extra files */
$php_inc_files = array("inc/idiorm", "inc/functions");
$js_inc_files = array(
	"//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js",
    "//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js",
	"//cdn.datatables.net/responsive/1.0.1/js/dataTables.responsive.js",
	"//cdn.datatables.net/fixedheader/2.1.2/js/dataTables.fixedHeader.min.js", 
	"inc/datatables_init.js");

foreach ($php_inc_files as $file) {
    include $file . '.php';
}
$ini_array = parse_ini_file("config.ini");

/* configuration using config.php */
$ignoredTags = explode(",", $ini_array["ignoredTags"]);
array_walk($ignoredTags, "trim");

 $connectionDetails = array($ini_array["db_host"], $ini_array["db_name"], $ini_array["db_username"], $ini_array["db_password"]);
