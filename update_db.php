<?php

include "include.php";

$tag_pw_array = ORM::for_table("passwords")->find_array();
$tag_pw_array = col_to_index($tag_pw_array, "tag");
$new_tag_pw_array = array();
$tagIgnoreArray = array("@link", "@public"); //insert tags here
// create the big array of links
$bigArray = array();
$xml = file_get_contents("files/Evernote.enex");
$xml = html_entity_decode($xml);
$xml = preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $xml);
$xml = simplexml_load_string($xml);

foreach ($xml->children() as $child) {
    $bigArrayRow = array();

    $tags = $child->xpath("tag");
    $tagArray = array();
    foreach ($tags as $tag) {
        $tag = strval($tag);
        if (!array_key_exists($tag, $tag_pw_array) && !array_key_exists($tag, $new_tag_pw_array) && !in_array($tag, $tagIgnoreArray)) {
            $new_tag_pw_array[$tag] = array("tag" => $tag, "password" => "");
        }
        if (!in_array($tag, $tagIgnoreArray)) {
            $tagArray[] = $tag;
        }
    }
    $bigArrayRow["title"] = strval($child->title);
    $temp = $child->xpath("note-attributes/source-url");
    $bigArrayRow["url"] = strval(reset($temp));

    $bigArrayRow["tags"] = implode(",", $tagArray);
    $bigArrayRow["created"] = strval($child->created);

    $bigArray[] = $bigArrayRow;
}


$bla = ORM::for_table("links")->delete_many();
$translationArray = array("title" => "name", "url" => "adress");
$bigArray = array_change_col_names($bigArray, $translationArray);
foreach ($bigArray as $rowIndex => $row) {
    $bigArray[$rowIndex]["created"] = convert_date($bigArray[$rowIndex]["created"]);
}
echo print_r($bigArray);
sql_insert_array($bigArray, "links");
sql_insert_array($new_tag_pw_array, "passwords");
?>

