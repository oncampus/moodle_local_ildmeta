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
 * Local ild_ildmeta
 *
 * @package     local_ild_ildmeta
 * @copyright   2017 oncampus GmbH, <support@oncampus.de>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

namespace local_ildmeta\task;

use context_system;
use context_course;
use moodle_url;

class generate_moochub_json_task_new_v2 extends \core\task\scheduled_task
{

    public function get_name() {
        return get_string('generate_moochub_json_task_new_v2', 'local_ildmeta');
    }

    public function execute() {
        global $CFG, $DB;

        $json = array();
        $json['links'] = array(
            'self' => 'https://open.vhb.org/moochub_new.json',
            'first' => 'https://open.vhb.org/moochub_new.json',
            'last' => 'https://open.vhb.org/moochub_new.json',
        );

        $json['data'] = array();

        $dataentry = array();

        $products = $DB->get_records('ildmeta');
        foreach ($products as $product) {
            if ($product->noindexcourse == 0 && $DB->record_exists('course', array('id' => $product->courseid))) {
                $dataentry = array();
                $dataentry['type'] = 'courses';
                $dataentry['id'] = 'openvhb' . $product->courseid;

                $universities = $DB->get_record('user_info_field', array('shortname' => 'universities'));
                $subjectareas = $DB->get_record('user_info_field', array('shortname' => 'subjectareas'));

                $langlist = array('de', 'en');

                $fs = get_file_storage();
                $fileurl = '';
                $context = context_course::instance($product->courseid);
                $files = $fs->get_area_files($context->id, 'local_ildmeta', 'overviewimage', 0);
                $fileurl = '';
                foreach ($files as $file) {
                    if ($file->get_filename() !== '.') {
                        $fileurl = moodle_url::make_pluginfile_url(
                            $file->get_contextid(),
                            $file->get_component(),
                            $file->get_filearea(),
                            $file->get_itemid(),
                            $file->get_filepath(),
                            $file->get_filename()
                        );
                    }
                }

                $unis = explode("\n", $universities->param1);
                $subjects = explode("\n", $subjectareas->param1);

                $uninames = array();
                $subjectnames = array();

                $uniids = explode(',', $product->university);
                $subjectids = explode(',', $product->subjectarea);

                foreach ($uniids as $uniid) {
                    $uninames[] = $unis[$uniid];
                }

                foreach ($subjectids as $subjectid) {
                    $subjectnames[] = $subjects[$subjectid];
                }

                $unilist = implode(', ', $uninames);
                $subjectlist = implode(', ', $subjectnames);

                $dataentry['attributes'] = array();
                $dataentry['attributes']['name'] = $product->coursetitle;
                $dataentry['attributes']['courseCode'] = 'openvhb' . $product->courseid;
                $dataentry['attributes']['courseMode'] = "MOOC";
                $dataentry['attributes']['languages'] = array($langlist[$product->courselanguage]);

                date_default_timezone_set("UTC");
                $dataentry['attributes']['startDate'] = date('c', $product->starttime);

                $dataentry['attributes']['availableUntil'] = null;

                if (trim($product->videocode) == '') {
                    $dataentry['attributes']['video'] = null;
                } else {
                    $dataentry['attributes']['video'] = array();
                    $dataentry['attributes']['video']['url'] = trim($product->videocode);
                    $dataentry['attributes']['video']['licenses'] = array();
                    $dataentry['attributes']['video']['licenses'][0]['id'] = "Proprietary";
                    $dataentry['attributes']['video']['licenses'][0]['url'] = null;
                }

                $lecturer = explode(', ', $product->lecturer);
                $dataentry['attributes']['instructors'] = array();
                for ($i = 0; $i < count($lecturer); $i++) {
                    $dataentry['attributes']['instructors'][$i] = new \stdClass;
                    $dataentry['attributes']['instructors'][$i]->name = $lecturer[$i];
                }

                $dataentry['attributes']['moocProvider'] = array();
                $dataentry['attributes']['moocProvider']['name'] = "OPEN vhb";
                $dataentry['attributes']['moocProvider']['url'] = "https://open.vhb.org";
                $dataentry['attributes']['moocProvider']['logo'] = "https://open.vhb.org/vhb_logo-OPEN_vhb.jpg";

                $dataentry['attributes']['url'] = $CFG->wwwroot . '/blocks/ildmetaselect/detailpage.php?id=' . $product->courseid;

                $dataentry['attributes']['courseLicenses'] = array();
                $dataentry['attributes']['courseLicenses'][0]['id'] = "Proprietary";
                $dataentry['attributes']['courseLicenses'][0]['url'] = null;

                $dataentry['attributes']['access'] = array("free");

                $json['data'][] = $dataentry;
            }
            mtrace('product added: ' . $product->courseid . ' ' . $product->coursetitle);
        }

        if ($fp = fopen($CFG->dirroot . '/moochub_new.json', 'w')) {
            fwrite($fp, json_encode($json));
            fclose($fp);
        } else {
            mtrace('Error opening file:' . $CFG->dirroot . '/moochub_new.json');
        }
    }
}
