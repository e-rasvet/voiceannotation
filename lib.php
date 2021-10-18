<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 *
 *
 * @package    filter_voiceannotation
 * @copyright  2020 Kochi-Tech.ac.jp
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */


defined('MOODLE_INTERNAL') || die();

/**
 * @param $course
 * @param $cm
 * @param $context
 * @param $filearea
 * @param $args
 * @param $forcedownload
 * @param array $options
 */
function filter_voiceannotation_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {

    $fs = get_file_storage();
    if (!$file = $fs->get_file($context->id, 'filter_voiceannotation', $filearea, $args[0], '/', $args[1])) {
        send_file_not_found();
    }

    send_stored_file($file, 0, 0, $forcedownload, $options);
}


/**
 * @param $contextid
 * @param string $component
 * @param string $filearea
 * @return int
 */
function filter_file_get_unused_draft_itemid($contextid, $component = 'filter_voiceannotation', $filearea = 'attachment') {
    if (isguestuser() or !isloggedin()) {
        // guests and not-logged-in users can not be allowed to upload anything!!!!!!
        print_error('noguest');
    }

    $fs = get_file_storage();
    $draftitemid = rand(1, 999999999);
    while ($files = $fs->get_area_files($contextid, $component, $filearea, $draftitemid)) {
        $draftitemid = rand(1, 999999999);
    }

    return $draftitemid;
}