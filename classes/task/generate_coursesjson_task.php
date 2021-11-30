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

class generate_coursesjson_task extends \core\task\scheduled_task {

    public function get_name() {
        return get_string('generate_coursesjson_task', 'local_ildmeta');
    }

    public function execute() {
        global $CFG, $DB;

        $json = array();
        $json['links'] = array(
            'self' => '',
            'first' => '',
            'last' => '',
            'prev' => '',
            'next' => ''
        );

        $json['data'] = array();

        $dataentry = array();

        $products = $DB->get_records('ildmeta');
        foreach ($products as $product) {
            if ($product->noindexcourse == 0 && $DB->record_exists('course', array('id' => $product->courseid))) {
                $dataentry = array();
                $dataentry['type'] = 'courses';
                $dataentry['id'] = 'openvhb' . $product->courseid;
                $dataentry['attributes'] = array();
                $dataentry['attributes']['url'] = $CFG->wwwroot . '/blocks/ildmetaselect/detailpage.php?id=' . $product->courseid;

                $universities = $DB->get_record('user_info_field', array('shortname' => 'universities'));
                $subjectareas = $DB->get_record('user_info_field', array('shortname' => 'subjectareas'));

                $langlist = [
                    'de',
                    'en',
					'es',
                ];

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

                $dataentry['attributes']['name'] = $product->coursetitle;
                $dataentry['attributes']['productImage'] = (string)$fileurl; // Overviewimage.
                $dataentry['attributes']['publisher'] = $product->lecturer;
                $dataentry['attributes']['university'] = $unilist;
                $dataentry['attributes']['languages'] = $langlist[$product->courselanguage];
                $dataentry['attributes']['subjectarea'] = $subjectlist;
                $dataentry['attributes']['processingtime'] = $product->processingtime . ' Stunden';
                $dataentry['attributes']['startDate'] = date('d.m.y', $product->starttime);
                $dataentry['attributes']['teasertext'] = $product->teasertext;
                $dataentry['attributes']['externprovider'] = 'openvhb';

                $json['data'][] = $dataentry;
            }
            mtrace('product added: ' . $product->courseid . ' ' . $product->coursetitle);
        }

        if ($fp = fopen($CFG->dirroot . '/courses.json', 'w')) {
            fwrite($fp, json_encode($json));
            fclose($fp);
        } else {
            mtrace('Error opening file:' . $CFG->dirroot . '/courses.json');
        }
    }
}