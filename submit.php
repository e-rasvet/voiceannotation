<?php

require_once '../../config.php';
require_once 'lib.php';

$id = optional_param('id', 0, PARAM_INT);
$entryID = optional_param('entryID', 0, PARAM_INT);
$attachment = optional_param('attachment', 0, PARAM_INT);
$contextid = optional_param('contextid', 0, PARAM_INT);
$comment = optional_param('comment', 0, PARAM_TEXT);
$html = optional_param('html', 0, PARAM_RAW);
$mod = optional_param('mod', "journal", PARAM_TEXT);


if (!$cm = get_coursemodule_from_id($mod, $id)) {
    print_error("Course Module ID was incorrect");
}

if (!$journal = $DB->get_record("journal", array("id" => $cm->instance))) {
    print_error("Course module is incorrect");
}

/*
 * Save attachment file as filter type file
 */

$itemid = filter_file_get_unused_draft_itemid($contextid);

$data = $_POST;
$data['itemid'] = $itemid;

$new_FileID = file_save_draft_area_files($attachment, $contextid, 'filter_voiceannotation', 'attachment', $itemid);

/*
 * Back parser for exist voice annotations
 */
$htmlTags = "";
$vacomments = explode('<span class="filter_voiceannotation_player"', $html);

if (count($vacomments) > 1) {
    $c = 0;
    foreach ($vacomments as $cmm) {

        if ($c > 0) {
            $html_array = explode('data-itemid="', $cmm, 2);
            $dataItemId = explode('"', $html_array[1], 2);
            $dataItemId = $dataItemId[0];

            $html_array = explode('data-comment="', $cmm, 2);
            $dataComment = explode('"', $html_array[1], 2);
            $dataComment = $dataComment[0];

            $html_array = explode('>', $cmm, 2);
            $dataText = explode('</span>', $html_array[1], 2);
            $html_body = $dataText[1];
            $dataText = $dataText[0];

            $htmlTags .= "{{VOICEANNOTATION:TEXT={$dataText}:ATT={$dataItemId}:COMM={$dataComment}}}" . $html_body;
        } else {
            $htmlTags .= $cmm;
        }
        $c++;

    }

    $html = $htmlTags;
}


/*
 * Update html journal entry and set voice annotation marker
 */

$html_array = explode('<span id="filter_voiceannotation_marker">', $html);
$html_head = $html_array[0];
$html_array = explode('</span>', $html_array[1]);
$html_body = $html_array[0];
$html_footer = $html_array[1];

$html_new = $html_head . "{{VOICEANNOTATION:TEXT={$html_body}:ATT={$itemid}:COMM={$comment}}}" . $html_footer;


$newentry = new stdClass();
$newentry->text = $html_new;
$newentry->id = $entryID;
if (!$DB->update_record("journal_entries", $newentry)) {
    print_error("Could not update your journal");
}


echo json_encode($data);