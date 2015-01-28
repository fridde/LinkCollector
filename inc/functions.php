<?php

function redirect($to) {
    @session_write_close();
    if (!headers_sent()) {
        header("Location: $to");
        flush();
        exit();
    } else {
        print "<html><head><META http-equiv='refresh' content='0;URL=$to'></head><body><a href='$to'>$to</a></body></html>";
        flush();
        exit();
    }
}

function create_download($source, $filename = "export.ris") {

    $f = fopen('php://memory', 'w+');
    fwrite($f, $source);
    fseek($f, 0);

    header('Content-Type: text/plain');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
// make php send the generated lines to the browser
    fpassthru($f);
}

function sql_insert_array($array, $sqlTable, $maxString = 5000) {

    //echo print_r($array);
    if (count($array) < 1) {
        echo "Empty array given! <br>";
        return;
    }

    $headers = array_keys(reset($array));
    //echo print_r($headers);
    $queryStart = "INSERT INTO " . $sqlTable . " (" . implode(" , ", $headers) . ") VALUES ";
    $query = "";

    foreach ($array as $rowKey => $row) {
        $newRow = array();
        foreach ($row as $colKey => $cell) {
            switch ($colKey) {
                case "id" :
                    // we let MySQL decide the new id
                    $newRow[$colKey] = "";
                    break;
                default :
                    $newRow[$colKey] = addslashes($cell);
            }
        }
        $newRow = "('" . implode("' , '", $newRow) . "'),";

        $query .= $newRow;

        if (strlen($query) > $maxString) {
            $totalQuery = $queryStart . rtrim($query, ",") . ";";

            ORM::for_table($sqlTable)->raw_execute($totalQuery);
            $query = "";
        }
    }
//add the rest
    if (strlen($query) > 2) {
        $totalQuery = $queryStart . rtrim($query, ",") . ";";

        ORM::for_table($sqlTable)->raw_execute($totalQuery);
    }
}

function array_change_col_names($array, $translationArray) {
    /* takes an array simulating a table in the format
     * array(
     *   row1 => array(nameCol1 => valueRow1Col1, nameCol2 => valueRow1Col2, ...),
     *   row2 => array(nameCol1 => valueRow2Col1, nameCol2 => valueRow2Col2, ...),
     *   ...
     * )
     * and exchanges the columnnames according to the translationArray in the format
     * array(
     *   oldColName1 => newColName1, oldColName2 => newColName2, ...
     * )
     */
    $newArray = array();
    foreach ($array as $rowIndex => $row) {
        $newRow = array();
        foreach ($row as $colName => $value) {
            if (isset($translationArray[$colName])) {
                $newRow[$translationArray[$colName]] = $value;
            } else {
                $newRow[$colName] = $value;
            }
        }
        $newArray[$rowIndex] = $newRow;
    }

    return $newArray;
}

function clean_sql($string) {

    $search = array("\'");
    $replace = array("\'\'");

    $string = str_replace($search, $replace, $string);

    return $string;
}

function convert_date($date) {

    $year = substr($date, 0, 4);
    $month = substr($date, 4, 2);
    $day = substr($date, 6, 2);

    $date = $year . "-" . $month . "-" . $day;

    return $date;
}

function create_htmltable_from_array($array, $givenPasswords, $truePasswords, $showOnlyCertainTags = FALSE) {

    $colNames = array_keys(reset($array));
    $html = '<table id="sortable" class="display stripe">';
    $html .= "<thead>
        <tr>";
    foreach ($colNames as $colname) {
        if ($colname != "id") {
            $html .= "<th>" . strtoupper($colname) . "</th>";
        }
    }

    $html .= "</tr>
        </thead>
        <tbody>";
    foreach ($array as $rowIndex => $row) {
        if (check_inclusion_according_to_tag($row, $givenPasswords, $truePasswords, $showOnlyCertainTags)) {
            $html .= "<tr>";
            foreach ($row as $colIndex => $cell) {
                $cellContent = "";
                switch ($colIndex) {
                    case "id":
                        break;
                    case "name":
                        $html .= "<td>" . $cell . "</td>";
                        break;
                    case "adress":
                        $domain = parse_url($cell, PHP_URL_HOST);
                        $domain = str_replace("www.", "", $domain);
                        $html .= "<td><a href=\"" . $cell . '" target="_blank">' . $domain . "</a>";
                        break;
                    case "tags":
                        $thisCellContent = array();
                        $localTags = explode(",", $cell);
                        array_walk($localTags, "trim");
                        $containsNames = FALSE;
                        foreach ($localTags as $tag) {
                            if ($tag != "@link") {
                                if (substr($tag, 0, 1) != "@") {
                                    $thisCellContent[] = $tag;
                                } else {
                                    $containsNames = TRUE;
                                }
                            }
                        }
                        $html .= "<td>";
                        $html .= implode(", ", $thisCellContent) . "</td>";

                        break;
                    case "created":
                        $html .= "<td>" . $cell . "</td>";
                        break;
                }
            }
            $html .= "</tr>";
        }
    }
    $html .= "</tbody></table>";

    return $html;
}

function check_inclusion_according_to_tag($row, $givenPasswords, $truePasswords, $chosenTags) {

    /* takes a row containing a link and looks if it can be included.
     * If none of the links attributed tags is matched by their corresponding password, the link is not included
     */

    $tagArray = explode(",", $row["tags"]);
    array_walk($tagArray, "trim");
    if ($chosenTags != FALSE) {
        $chosenTags = explode(",", $chosenTags);
        array_walk($chosenTags, "trim");
    }

    $any_pw_match = FALSE;
    $allTagsMatch = TRUE;

    if (count(array_filter($tagArray)) > 0) {
        foreach ($tagArray as $tag) {

            // the tag's password is matched by one of the given passwords
            $hasMatchingPw = key_exists($tag, $truePasswords) && in_array($truePasswords[$tag]["password"], $givenPasswords);


            if ($hasMatchingPw) {
                $any_pw_match = TRUE;
            }
        }
    } else {
        /* if the link has no tags, it can be considered to be free */
        $any_pw_match = TRUE;
    }

    if ($chosenTags != FALSE) {
        foreach ($chosenTags as $chosenTag) {
            if (!in_array($chosenTag, $tagArray)) {
                $allTagsMatch = FALSE;
            }
        }
    }
    $result = $any_pw_match && $allTagsMatch;
    return $result;
}

function col_to_index($array, $columnToIndex) {
    /* assumes a certain column to contain unique values and makes these values
     * the key of each row
     */
    $newArray = array();
    foreach ($array as $row) {
        $newArray[$row[$columnToIndex]] = $row;
    }
    return $newArray;
}

function link_for($url, $label = "", $class = "") {
    /* wrapper to build links and the ability to define a class */
    $returnString = '<a href="' . $url . '" ';
    if ($class != "") {
        $returnString .= 'class="' . $class . '"';
    }
    $returnString .= '>';
    if ($label == "") {
        $returnString .= $url;
    } else {
        $returnString .= $label;
    }
    $returnString .= "</a>";

    echo $returnString;
}

?>
