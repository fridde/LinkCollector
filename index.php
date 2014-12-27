<?php
include "include.php";
$existing_links = ORM::for_table("links")->find_array();

$chosenTags = (isset($_REQUEST["t"]) ? $_REQUEST["t"] : FALSE);
$givenPasswords = (isset($_REQUEST["p"]) ? $_REQUEST["p"] : array());
$givenPasswords = (gettype($givenPasswords) == "string" ? array($givenPasswords) : $givenPasswords);
$givenPasswords[] = "";  //the generic password
// now $givenPasswords contains all the passwords the user has attached to the url

$isUser = FALSE;
foreach ($givenPasswords as $pw) {
    if (substr($pw, 0, 1) === "0") {
        $isUser = TRUE;
    }
}
// that means that user passwords and only those should start with "0"

$truePasswords = ORM::for_table("passwords")->find_array();
$truePasswords = col_to_index($truePasswords, "tag");

$freeTags = array();
foreach ($truePasswords as $tag => $row) {
    if ($row["password"] == "") {
        $freeTags[] = $tag;
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv = "Content-Type" content = "text/html; charset=UTF-8">
        <link type = "text/css" rel = "stylesheet" href = "inc/stylesheet.css"/>
        <?php
        foreach ($js_inc_files as $file) {
            echo '<script src="' . $file . '"> </script>' . PHP_EOL;
        }
        if ($isUser) {
            echo '<script>$(\'#sortable\').DataTable({ paging: false,
                "order": [[ 3, \'desc\' ], [ 2, \'asc\' ]] });</script>';
        }
        ?>
    </head>
    <body>
        <div id="header">
            <a href="index.php"> <h1>Link Collector</h1> </a>
        </div>



    </body>
</html>




<div id="navbar">
    <?php
    $linkArray = ORM::for_table("passwords")->find_array();
    foreach ($linkArray as $row) {
        if (substr($row["tag"], 0, 1) != "@") {
            link_for("index.php?t=" . $row["tag"], $row["tag"], "box");
        }
    }
    ?>
</div>

<div id="main">
    <?php
	echo create_htmltable_from_array($existing_links, $givenPasswords, $truePasswords, $chosenTags);
    ?>
</div>

<div id="footer"></div>