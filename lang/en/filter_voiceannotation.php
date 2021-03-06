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

$string['filtername'] = 'Voice annotation';

// Settings strings
$string['settings_heading'] = 'Insert content settings';
$string['settings_desc'] = 'Change the settings for this filter. <br />
        IMPORTANT: Change these on first use only!';
$string['settings_start_tag'] = 'Start tag';
$string['settings_end_tag'] = 'End tag';
$string['settings_start_tag_desc'] = 'Tag that marks the start of the content';
$string['settings_end_tag_desc'] = 'Tag that marks the end of the content';
$string['button_label'] = "Click me";
// size of modal
$string['settings_height'] = 'iFrame height (embedded)';
$string['settings_height_desc'] = 'Enter whole number (pixels)';
$string['settings_width'] = 'iFrame width (embedded)';
$string['settings_width_desc'] = 'Enter whole number (pixels)';

// errors
$string['friendlymessage'] = 'Programming error: could not review question';
$string['questionidmismatch'] = 'Programming error: Question mismatch';
$string['postsubmiterror'] = 'Programming error: Could not review question';
$string['pop_param_error'] = 'Please specify "popup" or "embed" within your link';
$string['param_number_error'] = 'Bad number of parameters';
$string['link_text_length'] = 'Link text too long';
$string['link_text_error'] = 'Invalid characters in link';
$string['link_number_error'] = 'Please check your question id';
$string['unknown_error'] = 'Unknown error - bad format?';

//Modal form controls
$string['click_link'] = "Click the question link again to close frame";
$string['use_close'] = "Close the window to return to your course";

//Settings page
$string['stt_core'] = 'Speech to text core';
$string['speechtotextlang'] = 'Language';
$string['amazon_secretkey'] = 'Secret Key';
$string['amazon_accessid'] = 'Access ID';
$string['amazon_region'] = 'Region';

//Another
$string['startrecording'] = 'Start';
$string['recordingbox'] = 'Voice annatation';
$string['update'] = 'Submit';
$string['close'] = 'Close';
$string['quickrecording'] = 'If you select YES, a recording popup will appear right after highlighting text';