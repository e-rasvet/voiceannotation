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

/**filter_voiceannotation_container
 *
 *
 * @package    filter_voiceannotation
 * @copyright  2020 Kochi-Tech.ac.jp
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

defined('MOODLE_INTERNAL') || die();
/**
 * This filter looks for content tags in Moodle text and
 * replaces them with specified user-defined content.
 * @see filter_manager::apply_filter_chain()
 */
class filter_voiceannotation extends moodle_text_filter {

    private $starttag = "{{VOICEANNOTATION:";
    private $endtag = "}}";
    private $callCounter = 0;

    /**
     * This function looks for tags in Moodle text and
     * replaces them with questions from the question bank.
     * Tags have the format {{CONTENT:xxx}} where:
     *          - xxx is the user specified content
     * @param string $text to be processed by the text
     * @param array $options filter options
     * @return string text after processing
     */
    function filter($text, array $options = array()) {
        global $PAGE, $_SERVER, $CFG;

        $this->callCounter++;

        // Basic test to avoid work
        if (!is_string($text)) {
            // non string content can not be filtered anyway
            return $text;
        }

        $coursectx = $this->context->get_course_context(false);

        $courseid = $coursectx->instanceid;
        $contextid = $courseid;

        if (!isset($courseid)) {
            return $text;
        }

        $contextCourse = context_course::instance($courseid);

        /*
         * moodle/course:update   -   edit teacher role
         * moodle/course:viewhiddenactivities   --  non-editteacher role
         */


        if(!strstr($_SERVER['REQUEST_URI'], "/mod/journal/") || !has_capability('moodle/course:viewhiddenactivities', $contextCourse)) {
            /*
             * Just voiceannotation payer
             */
            $PAGE->requires->js_call_amd('filter_voiceannotation/player');

            // Do a quick check to see if we have a tag
            if (strpos($text, $this->starttag) === false) {
                return $text;
            }

            $renderer = $PAGE->get_renderer('filter_voiceannotation');

            // There may be a tag in here somewhere so continue
            // Get the contents and positions in the text and call the
            // renderer to deal with them
            $text = filter_voiceannotation_insert_content($text, $this->starttag, $this->endtag, $courseid, $renderer);
            
            return $text;
        }

        /*
         *
         * Add Highlight JS code to each page
         * and call it only ones
         */
        if ($this->callCounter == 1) {

            $config = get_config('filter_voiceannotation');

            $PAGE->requires->js_call_amd('filter_voiceannotation/recording_box', 'init', array($config->quickrecording));
            $PAGE->requires->js_call_amd('filter_voiceannotation/player');
            $PAGE->requires->js_call_amd('filter_voiceannotation/recorder');

            //$contextid = context_course::instance($COURSE->id)->id;
            $itemid = $this->filter_file_get_unused_draft_itemid($contextid, 'user', 'draft');

            $result = "";

            $btnattributes = array(
                'name' => 'filter_voiceannotation_recording_audio',
                'id' => 'filter_voiceannotation_rec',
                'class' => 'filter_voiceannotation_srecordingBTN button-xl',
                'size' => 80,
                'speechtotextlang' => 'en-US',
                'amazon_region' => $config->amazon_region,
                'amazon_accessid' => $config->amazon_accessid,
                'amazon_secretkey' => $config->amazon_secretkey,
                'type' => 'button',
                'options' => json_encode(array(
                    'repo_id' => $this->get_repo_id(),
                    'ctx_id' => $contextid,
                    'itemid' => $itemid,
                    'id' => $this->context->instanceid,
                    'title' => 'audio.mp3',
                )),
                'onclick' => 'recBtn(event);',
                'audioname' => 'filter_voiceannotation_audio_player',
                'answerDiv' => 'filter_voiceannotation_answerDiv',
            );

            $btn  = html_writer::start_tag('button', $btnattributes);
            $btn .= html_writer::tag('i', "", array('class' => 'fa fa-microphone'));
            $btn .= " " . get_string("startrecording", 'filter_voiceannotation');
            $btn .= html_writer::end_tag('button');


            if ($config->stt_core == "amazon") {
                $result .= html_writer::script(null, "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js");
                $result .= html_writer::script(null, new moodle_url('/filter/voiceannotation/js/amazon/lame.js'));
                $result .= html_writer::script(null, new moodle_url('/filter/voiceannotation/js/amazon/main.js'));
            } else if ($config->stt_core == "google"){
                $result .= html_writer::script(null, "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js");
                $result .= html_writer::script(null, new moodle_url('/filter/voiceannotation/js/google/recorder.js'));
                $result .= html_writer::script(null, new moodle_url('/filter/voiceannotation/js/google/main.js'));
                $result .= html_writer::script(null, new moodle_url('/filter/voiceannotation/js/google/Mp3LameEncoder.min.js'));
            }

            $result .= html_writer::tag('div', $btn, array('id' => 'filter_voiceannotation_btn'));
            $result .= html_writer::start_tag('div', array('id'=>'filter_voiceannotation_box_btn', 'class' => 'filter_voiceannotation_bottomright_text filter_voiceannotation_bottomright', 'style' => 'display:none'));
            $result .= html_writer::start_tag('a', array('href'=>'#', 'class' => 'filter_voiceannotation_box_link', 'id' => 'filter_voiceannotation_container'));
            $result .= html_writer::tag('i', '', array('class' => 'fa fa-microphone'));
            $result .= html_writer::end_tag('a');
            $result .= html_writer::end_tag('div');
            $result .= html_writer::tag('div', "", array("style"=>"clear:both;", "id" => "filter_voiceannotation_sep"));


            $text = $result.$text;
        }

        // Do a quick check to see if we have a tag
        if (strpos($text, $this->starttag) === false) {
            return $text;
        }

        $renderer = $PAGE->get_renderer('filter_voiceannotation');

        // There may be a tag in here somewhere so continue
        // Get the contents and positions in the text and call the
        // renderer to deal with them
        $text = filter_voiceannotation_insert_content($text, $this->starttag, $this->endtag, $courseid, $renderer);
        return $text;
    }


    /**
     * @param string $type
     * @return |null
     */
    public function get_repo_id($type = 'upload') {
        global $CFG;
        require_once($CFG->dirroot . '/lib/form/filemanager.php');
        foreach (repository::get_instances() as $rep) {
            $meta = $rep->get_meta();
            if ($meta->type == $type)
                return $meta->id;
        }
        return null;
    }

    /**
     * @param $contextid
     * @param string $component
     * @param string $filearea
     * @return int
     */
    public function filter_file_get_unused_draft_itemid($contextid, $component = 'filter_voiceannotation', $filearea = 'attachment') {
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

}
/**
 *
 * function to replace question filter text with actual question
 *
 * @param string $str text to be processed
 * @param string $starttag start tag pattern to be searched for
 * @param string $endtag end tag for text to replace
 * @param int $courseid id of course text is in
 * @param renderer $renderer - filter renderer
 * @return a replacement text string
 */
function filter_voiceannotation_insert_content($str, $starttag, $endtag, $courseid, $renderer) {

    $newstring = $str;

    // While we have the start tag in the text
    while (strpos($newstring, $starttag) !== false) {
        $initpos = strpos($newstring, $starttag);
        if ($initpos !== false) {
            $pos = $initpos + strlen($starttag);  // get up to string
            $endpos = strpos($newstring, $endtag);
            $content = substr($newstring, $pos, $endpos - $pos); // extract content
            // Clean the string
            $content = filter_var($content, FILTER_SANITIZE_STRING);
            $content = $renderer->get_content($content, $courseid);
            // Update the text to replace the filtered string
            $newstring = substr_replace($newstring, $content, $initpos,
                    $endpos - $initpos + strlen($endtag));
            $initpos = $endpos + strlen($endtag);
        }
    }
    return $newstring;
}