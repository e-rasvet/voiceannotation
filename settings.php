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

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {



    /*
     * Transcribe core settings
     */
    $stt_core = array( "amazon" => "Amazon Transcribe", "google" => "Google Speech to text");

    $settings->add(new admin_setting_configselect('filter_voiceannotation/stt_core',
        new lang_string('stt_core', 'filter_voiceannotation'),
        '', 'amazon', $stt_core));

    $speechtotextlang = array( "en-US" => "US English (en-US)", "en-AU" => "Australian English (en-AU)", "en-GB" => "British English (en-GB)",
        "fr-CA" => "Canadian French (fr-CA)", "fr-FR" => "French (fr-FR)",
        "es-US" => "US Spanish (es-US)");

    $settings->add(new admin_setting_configselect('filter_voiceannotation/speechtotextlang',
        new lang_string('speechtotextlang', 'filter_voiceannotation'),
        '', 'en-US', $speechtotextlang));

    $settings->add(new admin_setting_configselect('filter_voiceannotation/quickrecording',
        new lang_string('quickrecording', 'filter_voiceannotation'),
        '', 0, array(0 => new lang_string('no'), 1 => new lang_string('yes'))));


    $amazon_region = array( "us-east-1" => "US East (N. Virginia)", "us-east-2" => "US East (Ohio)", "us-west-2" => "US West (Oregon)",
        "ap-southeast-2" => "Asia Pacific (Sydney)", "ca-central-1" => "Canada (Central)",
        "eu-west-1" => "EU (Ireland)");

    $settings->add(new admin_setting_configselect('filter_voiceannotation/amazon_region',
        new lang_string('amazon_region', 'filter_voiceannotation'),
        '', 'ap-southeast-2', $amazon_region));

    $settings->add(new admin_setting_configtext('filter_voiceannotation/amazon_accessid',
        get_string('amazon_accessid', 'qtype_sassessment'), '', '', PARAM_TEXT));

    $settings->add(new admin_setting_configtext('filter_voiceannotation/amazon_secretkey',
        get_string('amazon_secretkey', 'qtype_sassessment'), '', '', PARAM_TEXT));


}