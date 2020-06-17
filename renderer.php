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


class filter_voiceannotation_renderer extends plugin_renderer_base {
    /**
     * This function returns content
     * @param string $linktext the text for the returned link
     * @return string the html required to display the content
     */
    public function get_content($content, $courseid) {
        //$this->page->requires->js_call_amd('filter_voiceannotation/player',
        //        'init', array($content));

        $embed = $this->parse_filter_tag($content);

        $fs = get_file_storage();
        if ($file = $fs->get_file($courseid, 'filter_voiceannotation', 'attachment', $embed->itemid, '/', 'audio.mp3')) {

            $fileUrl = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(),
                $file->get_itemid(), $file->get_filepath(), $file->get_filename(), true);

            $embedhtml = html_writer::start_tag("span", array('class' => 'filter_voiceannotation_player', 'data-itemid' => $embed->itemid, 'data-url' => $fileUrl, 'data-comment' => $embed->comment));
            $embedhtml .= $embed->text;
            //$embedhtml .= html_writer::tag('i', '', array('class' => 'fa fa-play', 'aria-hidden' => 'true'));
            $embedhtml .= html_writer::end_tag("span");
        } else {
            $embedhtml = html_writer::start_tag("span");
            $embedhtml .= $embed->text . "[The voice annotation was not found]";
            $embedhtml .= html_writer::end_tag("span");
        }

        return $embedhtml;
    }

    /**
     * @param $content
     * @return stdClass
     */
    private function parse_filter_tag($content){
        $data = explode("TEXT=", $content);
        $data2 = explode(":ATT=", $data[1]);
        $data3 = explode(":COMM=", $data2[1]);

        $res = new stdClass();
        $res->text = $data2[0];
        $res->itemid = $data3[0];
        $res->comment = $data3[1];

        return $res;
    }

}