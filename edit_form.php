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

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/formslib.php");

class ildmeta_form extends moodleform {

    public function definition() {
        global $CFG, $DB;
        $mform = $this->_form; // Don't forget the underscore!

        $lecturer = $this->_customdata['lecturer'];
        $maxlecturer = $this->_customdata['max_lecturer'];
        $courseid = $this->_customdata['courseid'];

        $filemanageropts = $this->_customdata['filemanageropts'];
        $editoropts = $this->_customdata['editoropts'];

        // We will implement a general settings page later.
        $langlist = [
            'Deutsch',
            'Englisch',
            'Spanisch',
        ];

        // We will implement a general settings page later.
        $licencelist = [
            'Proprietary',
            'CC0-1.0',
        ];

        $previewurl = new moodle_url('/blocks/ildmetaselect/detailpage.php', array('id' => $courseid));

        $mform->addElement('html', '<h2>' . get_string('form:metaoverview', 'local_ildmeta') . '</h2>');
        $mform->addElement('html', html_writer::link($previewurl, get_string('form:btnpreview', 'local_ildmeta'),
            array('class' => 'btn btn-default', 'target' => '_blank')));
        $mform->addElement('html', '<hr>');

        $context = context_system::instance();

        if (has_capability('local/ildmeta:indexation', $context)) {
            $mform->addElement('select', 'noindexcourse', get_string('form:noindexcourse', 'local_ildmeta'),
                array(
                    get_string('form:noindexcourse_yes', 'local_ildmeta'),
                    get_string('form:noindexcourse_no', 'local_ildmeta'),
                    get_string('form:noindexcourse_limited', 'local_ildmeta'),
                )
            );
            $mform->setType('index', PARAM_RAW);
        }

        // Anbietende Unis.
        $universities = $DB->get_record('user_info_field', array('shortname' => 'universities'));
        $select = $mform->addElement('select', 'universitylist', get_string('form:university', 'local_ildmeta'),
            explode("\n", $universities->param1));
        $mform->setType('universitylist', PARAM_RAW);
        $select->setMultiple(true);

        $mform->addElement('html', $this->get_unilist_div_string());

        $mform->addElement('hidden', 'university', '');

        // Fachbereich/Wissensgebiet.
        $subjectareas = $DB->get_record('user_info_field', array('shortname' => 'subjectareas'));

        $mform->addElement('select', 'subjectarea', get_string('form:subjectarea', 'local_ildmeta'),
            explode("\n", $subjectareas->param1));
        $mform->setType('subjectarea', PARAM_RAW);

        // Uebersichtsbild.
        $mform->addElement('filemanager', 'overviewimage',
            get_string('form:overviewimage', 'local_ildmeta'), null, $filemanageropts);

        $mform->addElement('text', 'picturecredits', get_string('form:picturecredits', 'local_ildmeta'),
            array('size' => 50));
        $mform->setType('picturecredits', PARAM_TEXT);

        // Urloverviewimage.
        $mform->addElement('text', 'urloverviewimage', get_string('form:urloverviewimage', 'local_ildmeta'),
            array('size' => 50));
        $mform->setType('urloverviewimage', PARAM_TEXT);

        if (has_capability('local/ildmeta:indexation', $context)) {
            $mform->addElement('select', 'licenceoverviewimage', get_string('form:licenceoverviewimage', 'local_ildmeta'),
                $licencelist);
            $mform->setType('licenceoverviewimage', PARAM_RAW);
        }

        // Videocode.
        $mform->addElement('text', 'urllicenceoverviewimage',
            get_string('form:urllicenceoverviewimage', 'local_ildmeta'), array('size' => 50));
        $mform->setType('urllicenceoverviewimage', PARAM_TEXT);

        // Detailbild.
        $mform->addElement('filemanager', 'detailimage',
            get_string('form:detailimage', 'local_ildmeta'), null, $filemanageropts);

        // Videocode.
        $mform->addElement('text', 'videocode', get_string('form:videocode', 'local_ildmeta'), array('size' => 50));
        $mform->setType('videocode', PARAM_TEXT);

        // Kurstitel.
        $mform->addElement('text', 'coursetitle', get_string('form:coursetitle', 'local_ildmeta'), array('size' => 50));
        $mform->setType('coursetitle', PARAM_TEXT);

        // Dozent.
        $mform->addElement('text', 'lecturer', get_string('form:lecturer', 'local_ildmeta'), array('size' => 50));
        $mform->setType('lecturer', PARAM_TEXT);

        // Kurssprache.
        $mform->addElement('select', 'courselanguage', get_string('form:courselanguage', 'local_ildmeta'), $langlist);
        $mform->setType('courselanguage', PARAM_RAW);

        // Bearbeitungszeit in Stunden.
        $mform->addElement('text', 'processingtime', get_string('form:processingtime', 'local_ildmeta'));
        $mform->setType('processingtime', PARAM_INT);
        $mform->addRule('processingtime', get_string('form:processingtime_description', 'local_ildmeta'), 'numeric');
        $mform->addElement('static', 'text_processingtime', '',
            get_string('form:processingtime_description', 'local_ildmeta'));

        // Startzeit.
        $mform->addElement('date_selector', 'starttime', get_string('form:starttime', 'local_ildmeta'));

        $mform->addElement('html', '<h3 class="pt-5">' . get_string('form:detailinformation', 'local_ildmeta') .
            '</h3><hr>');

        // Teasertext.
        $mform->addElement('editor', 'teasertext', get_string('form:teasertext', 'local_ildmeta'));
        $mform->setType('teasertext', PARAM_RAW);

        // Zielgruppe.
        $mform->addElement('editor', 'targetgroup', get_string('form:targetgroup', 'local_ildmeta'));
        $mform->setType('targetgroup', PARAM_RAW);

        // Lernziele.
        $mform->addElement('editor', 'learninggoals', get_string('form:learninggoals', 'local_ildmeta'));
        $mform->setType('learninggoals', PARAM_RAW);

        // Gliederung.
        $mform->addElement('editor', 'structure', get_string('form:structure', 'local_ildmeta'));
        $mform->setType('structure', PARAM_RAW);

        // Leistungsnachweis.
        $mform->addElement('editor', 'certificateofachievement',
            get_string('form:certificateofachievement', 'local_ildmeta'));
        $mform->setType('certificateofachievement', PARAM_RAW);

        /*
        * We need editor + filemanager for each lecturer.
        * The data will be stored in the new table "mdl_ildmeta_additional" with "courseid", "name" and "value".
        * ??? SURE ??? The "name" will be saved as reference in the table "mdl_ildmeta".
        * Each record will be selected by "courseid" and "name"
        */

        $mform->addElement('html', '<h3 class="pt-5">' . get_string('form:lecturerdetails', 'local_ildmeta') .
            '</h3><hr>');
        $i = 1;

        // Above $i will be used here!
        if (empty($lecturer)) {

            while ($i <= $maxlecturer) {

                // Anbieter*innen / Autor*innen.
                $radioarray = array();
                $radioarray[] = $mform->createElement('radio', 'lecturer_type_' . $i, '',
                    get_string('form:lecturer_type_0', 'local_ildmeta'), 0);
                $radioarray[] = $mform->createElement('radio', 'lecturer_type_' . $i, '',
                    get_string('form:lecturer_type_1', 'local_ildmeta'), 1);
                $mform->addGroup($radioarray, 'radioar',
                    get_string('form:lecturer_type', 'local_ildmeta'), array(' '), false);
                if ($i > 1) {
                    $mform->setDefault('lecturer_type_' . $i, 1);
                }

                // Bild Anbieter*innen / Autor*innen.
                $mform->addElement('filemanager', 'detailslecturer_image_' . $i,
                    get_string('form:detailslecturer_image', 'local_ildmeta'), null, $filemanageropts);

                // Details Anbieter*innen / Autor*innen.
                $mform->addElement('editor', 'detailslecturer_editor_' . $i,
                    get_string('form:detailslecturer', 'local_ildmeta'), null, $editoropts);
                $mform->setType('detailslecturer_editor', PARAM_RAW);

                $url = new moodle_url('/local/ildmeta/delete_lecturer.php',
                    array('courseid' => $courseid, 'id' => $i));

                $mform->addElement('html', html_writer::link($url, 'Eingabefeld entfernen'));

                $mform->addElement('html', '<h>');

                $i++;
            }
        } else {
            foreach ($lecturer as $lect) {
                if (strpos($lect->name, 'type')) {
                    // Anbieter*innen / Autor*innen.
                    $radioarray = array();
                    $radioarray[] = $mform->createElement('radio', $lect->name, '',
                        get_string('form:lecturer_type_0', 'local_ildmeta'), 0);
                    $radioarray[] = $mform->createElement('radio', $lect->name, '',
                        get_string('form:lecturer_type_1', 'local_ildmeta'), 1);
                    $mform->addGroup($radioarray, 'radioar',
                        get_string('form:lecturer_type', 'local_ildmeta'), array(' '), false);
                    if ($i > 1) {
                        $mform->setDefault($lect->name, 1);
                    }
                }
                if (strpos($lect->name, 'image')) {
                    // Bild Anbieter*innen / Autor*innen.
                    $mform->addElement('filemanager', $lect->name,
                        get_string('form:detailslecturer_image', 'local_ildmeta'), null, $filemanageropts);
                }
                if (strpos($lect->name, 'editor')) {
                    // Details Anbieter*innen / Autor*innen.
                    $mform->addElement('editor', $lect->name,
                        get_string('form:detailslecturer', 'local_ildmeta'), null, $editoropts);
                    $mform->setType('detailslecturer_editor', PARAM_RAW);

                    $id = substr($lect->name, -1);
                    $url = new moodle_url('/local/ildmeta/delete_lecturer.php',
                        array('courseid' => $courseid, 'id' => $id));
                    $mform->addElement('html', html_writer::link($url,
                        get_string('form:removelecturer', 'local_ildmeta'), array('class' => 'btn btn-default')));
                    $mform->addElement('html', '<hr>');

                    $i++;
                }
            }
        }

        $mform->addElement('html', '<h5 class="pt-5">' . get_string('form:additionallecturer', 'local_ildmeta') .
            '</h5><hr>');

        $mform->addElement('text', 'additional_lecturer', get_string('form:additionalfields', 'local_ildmeta'));
        $mform->setDefault('additional_lecturer', 0);
        $mform->setType('additional_lecturer', PARAM_INT);
        $mform->addRule('additional_lecturer', get_string('form:inputnumber', 'local_ildmeta'),
            'numeric', '', 'client');
        $mform->addElement('static', 'text_additional_lecturer', '',
            get_string('form:inputnumber', 'local_ildmeta'));
        $this->add_action_buttons($cancel = false, $submitlabel = get_string('form:addnewfields', 'local_ildmeta'));

        $mform->addElement('html', '<h3 class="pt-5">' . get_string('form:additionalinformation', 'local_ildmeta') .
            '</h3><hr>');

        $licenses = $DB->get_records('license');
        $licensesarr = [];

        foreach ($licenses as $license) {
            $licensesarr[] = $license->shortname;
        }

        $mform->addElement('select', 'license', get_string('form:license', 'local_ildmeta'), $licensesarr);
        $mform->setType('license', PARAM_RAW);

        $mform->addElement('html', '<h3 class="pt-5">' . get_string('form:tags', 'local_ildmeta') . '</h3><hr>');

        // Tags.
        $mform->addElement('text', 'tags', get_string('form:tagsinput', 'local_ildmeta'), array('size' => 50));
        $mform->setType('tags', PARAM_TEXT);

        $buttonarray = array();
        $buttonarray[] = $mform->createElement('submit', 'submitbutton',
            get_string('form:savechanges', 'local_ildmeta'));
        $buttonarray[] = $mform->createElement('submit', 'submittocourse',
            get_string('form:savebacktocourse', 'local_ildmeta'));
        $buttonarray[] = $mform->createElement('cancel', 'cancel', get_string('form:cancel', 'local_ildmeta'));
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);

        // TBD -> Add new button "Save changes", "Save and back to course" and "Cancel".
    }

    public function validation($data, $files) {
        return array();
    }

    // Funktioniert hier nicht. Falsche Stelle.
    public function data_preprocessing(&$defaultvalues) {
        $lecturer = $this->_customdata['lecturer'];
        print($lecturer);
        die();
        if ($this->current->instance) {
            foreach ($lecturer as $lect) {
                $draftitemid = file_get_submitted_draft_itemid($lect->name);
                $context = context_course::instance($this->_customdata['courseid']);
                file_prepare_draft_area($draftitemid, $context->id, 'local_ildmeta', $lect->name, 0);
                $defaultvalues[$lect->name] = $draftitemid;
            }
        }
        // TODO overviewimage nicht vergessen.
    }

    public function get_unilist_div_string() {
        $string = "<div class='form-group row fitem'>";

        $string .= "<div class='col-md-3'></div>";
        $string .= "<div class='col-md-9 form-inline felement'><div id='unilist'></div></div>";

        $string .= "</div>";

        return $string;
    }
}
