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

require_once('../../config.php');
require_once('lib.php');
require_once('locallib.php');
require_once('edit_form.php');

// First check, if user is logged in before accessing this page.
require_login();

// Get required params for further actions.
$courseid = optional_param('courseid', 0, PARAM_INT);
$coursecontext = context_course::instance($courseid);
$context = context_system::instance();

$url = new moodle_url('/local/ildmeta/edit.php');

// Check, if user has capability to access this page.
if (!has_capability('local/ildmeta:allowaccess', $coursecontext)) {
    redirect(new moodle_url('/'), get_string('permissiondenied', 'local_ildmeta'), null, \core\output\notification::NOTIFY_ERROR);
}

$record = $DB->get_record('ildmeta', ['courseid' => $courseid]);

$filemanageropts = array(
    'subdirs' => 0,
    'maxbytes' => '0',
    'maxfiles' => 1,
    'context' => $context
);

$editoropts = array(
    'subdirs' => 0,
    'maxbytes' => '100000',
    'maxfiles' => 10,
    'context' => $context,
    'trusttext' => true,
    'enable_filemanagement' => true
);

if (isset($record->detailslecturer)) {
    $maxlecturer = $record->detailslecturer;
} else {
    $maxlecturer = 2;
}

$recordslect = $DB->get_records('ildmeta_additional', array('courseid' => $courseid));

$customdata = array(
    'filemanageropts' => $filemanageropts,
    'editoropts' => $editoropts,
    'max_lecturer' => $maxlecturer,
    'courseid' => $courseid,
    'lecturer' => $recordslect
);

$mform = new ildmeta_form($url . '?courseid=' . $courseid, $customdata);

$itemid = 0;

if ($mform->is_cancelled()) {

    $redirectto = new moodle_url($CFG->wwwroot . '/course/view.php?id=' . $courseid);
    redirect($redirectto);

} else if ($fromform = $mform->get_data()) {

    // Prepare and save the overviewimage.
    $draftitemid = file_get_submitted_draft_itemid('overviewimage');
    file_prepare_draft_area($draftitemid, $coursecontext->id, 'local_ildmeta', 'overviewimage', $draftitemid);

    file_save_draft_area_files($fromform->overviewimage, $coursecontext->id, 'local_ildmeta', 'overviewimage', 0);

    // Prepeare and save the detailimage. Detailimage will be used if no YouTube URL is set.
    $draftitemiddetail = file_get_submitted_draft_itemid('detailimage');
    file_prepare_draft_area($draftitemiddetail, $coursecontext->id, 'local_ildmeta', 'detailimage', $draftitemiddetail);

    file_save_draft_area_files($fromform->detailimage, $coursecontext->id, 'local_ildmeta', 'detailimage', 0);

    // First of all, check for additional lecturer fields added to the form.
    if ($fromform->additional_lecturer > 0) {
        $addlect = new stdClass();
        $addlect->id = $record->id;
        $addlect->detailslecturer = $fromform->additional_lecturer + $record->detailslecturer;
        $DB->update_record('ildmeta', $addlect);

        // Add empty fields in ildmeta_additional.
        // New logic required due to delete options...

        // Get last lecturer id.

        $recordlectlast = $DB->get_record_sql("
            SELECT * FROM {ildmeta_additional}
            WHERE courseid = ?
            ORDER BY id DESC",
            array('courseid' => $courseid));

        $i = explode("_", $recordlectlast->name)[2] + 1;

        $maxi = ($i - 1) + $fromform->additional_lecturer;

        while ($i <= $maxi) {
            $str1 = "lecturer_type_" . $i;
            $str2 = "detailslecturer_image_" . $i;
            $str3 = "detailslecturer_editor_" . $i;

            $fields = array($str1, $str2, $str3);

            foreach ($fields as $f) {
                $ins = new stdClass();
                $ins->courseid = $courseid;
                $ins->name = $f;
                $ins->value = '';
                $DB->insert_record('ildmeta_additional', $ins);
            }
            $i++;
        }

        // TBD -> IN THIS CASE CHANGES WONT BE SAVED!!!
        // TBD -> Add new button "Save changes", "Save and back to course" and "Cancel".

        // If additional lecturer the user will be redirected to the edit.php for further editing.
        $url = new moodle_url('/local/ildmeta/edit.php', array('courseid' => $courseid));
    } else {
        // Otherweise he will be forwarded to the detailpage.php.
        $url = new moodle_url('/local/ildmeta/edit.php', array('courseid' => $courseid));
    }

    // Prepare data from form for database queries.
    $todb = new stdClass;

    $todb->courseid = $courseid;
    $todb->overviewimage = $draftitemid;
    $todb->coursetitle = $fromform->coursetitle;
    $todb->lecturer = $fromform->lecturer;
    (isset($fromform->noindexcourse)) ? $todb->noindexcourse = $fromform->noindexcourse : '';
    $todb->overviewimage = $draftitemid;
    $todb->detailimage = $draftitemiddetail;
    $todb->university = $fromform->university;
    $todb->subjectarea = $fromform->subjectarea;
    $todb->courselanguage = $fromform->courselanguage;
    $todb->processingtime = $fromform->processingtime;
    $todb->starttime = $fromform->starttime;
    $todb->teasertext = $fromform->teasertext['text'];
    $todb->targetgroup = $fromform->targetgroup['text'];
    $todb->learninggoals = $fromform->learninggoals['text'];
    $todb->structure = $fromform->structure['text'];
    $todb->certificateofachievement = $fromform->certificateofachievement['text'];
    $todb->license = $fromform->license;
    $todb->videocode = $fromform->videocode;
    $todb->picturecredits = $fromform->picturecredits;
    $todb->tags = $fromform->tags;
    $todb->urloverviewimage = $fromform->urloverviewimage;
    $todb->licenceoverviewimage = $fromform->licenceoverviewimage;
    $todb->urllicenceoverviewimage = $fromform->urllicenceoverviewimage;

    // If course is not in db yet.
    if (!$DB->get_record('ildmeta', array('courseid' => $courseid))) {

        // If noindexcourse in todb is not set.
        if (!isset($todb->noindexcourse)) {
            // Use the default value "no indexination".
            $todb->noindexcourse = 1;
        }

        $DB->insert_record('ildmeta', $todb);

    } else {

        // If course is in db, update.
        $primkey = $DB->get_record('ildmeta', array('courseid' => $courseid));

        $todb->id = $primkey->id;

        // If noindexcourse in todb is not set.
        if (!isset($todb->noindexcourse)) {
            // Use the old value from the db.
            $todb->noindexcourse = $primkey->noindexcourse;
        }

        $DB->update_record('ildmeta', $todb);
    }

    // Get lecturer editor + filemanager.
    $lecturer = new stdClass();

    foreach ($fromform as $key => $value) {
        if (strpos($key, '_type')) {
            $lecturer->$key = $fromform->$key;
        }
        if (strpos($key, '_image')) {
            $lecturer->$key = $fromform->$key;

            $draftlecturer = file_get_submitted_draft_itemid($key);
            file_prepare_draft_area($draftlecturer, $coursecontext->id, 'local_ildmeta', $key, 0);
            file_save_draft_area_files($draftlecturer, $coursecontext->id, 'local_ildmeta', $key, 0);
        }
        if (strpos($key, '_editor')) {
            $lecturer->$key = $fromform->$key['text'];
        }
    }

    foreach ($lecturer as $key => $value) {

        $lectodb = new stdClass();
        $lectodb->courseid = $courseid;
        $lectodb->name = $key;
        $lectodb->value = $value;

        if (!$DB->get_record('ildmeta_additional', array('name' => $lectodb->name, 'courseid' => $courseid))) {
            $DB->insert_record('ildmeta_additional', $lectodb);
        } else {

            $primkey = $DB->get_record('ildmeta_additional', array('courseid' => $courseid, 'name' => $lectodb->name));

            $lectodb->id = $primkey->id;

            $DB->update_record('ildmeta_additional', $lectodb);
        }
    }

    // After database redirect to detailpage.
    // Url defined after check for additional lecturer.
    if ($fromform->submittocourse) {
        $url = new moodle_url('/course/view.php?id=' . $courseid);
        redirect($url, 'Daten erfolgreich gespeichert', null, \core\output\notification::NOTIFY_SUCCESS);
    } else {
        redirect($url, 'Daten erfolgreich gespeichert', null, \core\output\notification::NOTIFY_SUCCESS);
    }

} else {

    // Prefill forms from db.
    $getdb = $DB->get_record('ildmeta', array('courseid' => $courseid));

    $getlect = $DB->get_records('ildmeta_additional', array('courseid' => $courseid));

    if ($getdb != null) {
        $new = new stdClass;
        $new->coursetitle = $getdb->coursetitle;
        $new->lecturer = $getdb->lecturer;
        $new->overviewimage = $getdb->overviewimage;
        $new->detailimage = $getdb->detailimage;
        $new->university = $getdb->university;
        $new->universitylist = $getdb->university;
        $new->noindexcourse = $getdb->noindexcourse;
        $new->subjectarea = $getdb->subjectarea;
        $new->courselanguage = $getdb->courselanguage;
        $new->processingtime = $getdb->processingtime;
        $new->starttime = $getdb->starttime;
        $new->teasertext['text'] = $getdb->teasertext;
        $new->targetgroup['text'] = $getdb->targetgroup;
        $new->learninggoals['text'] = $getdb->learninggoals;
        $new->structure['text'] = $getdb->structure;
        $new->additional_lecturer = '0';
        $new->certificateofachievement['text'] = $getdb->certificateofachievement;
        $new->license = $getdb->license;
        $new->videocode = $getdb->videocode;
        $new->tags = $getdb->tags;
        $new->picturecredits = $getdb->picturecredits;
        $new->urloverviewimage = $getdb->urloverviewimage;
        $new->licenceoverviewimage = $getdb->licenceoverviewimage;
        $new->urllicenceoverviewimage = $getdb->urllicenceoverviewimage;

        if (!empty($getlect)) {

            foreach ($getlect as $lec) {
                if (strpos($lec->name, '_editor')) {
                    $key = $lec->name;
                    $new->$key['text'] = $lec->value;
                } else {
                    $key = $lec->name;
                    $new->$key = $lec->value;
                }
            }

        }

        $sql = 'SELECT filearea
					    FROM {files}
					 WHERE component = :component
					      AND contextid = :contextid
						  AND filename != :filename
						  AND itemid = 0';
        $params = array('component' => 'local_ildmeta', 'contextid' => $coursecontext->id, 'filename' => '.');
        $files = $DB->get_records_sql($sql, $params);

        foreach ($files as $file) {
            $draftitemid = file_get_submitted_draft_itemid($file->filearea);

            file_prepare_draft_area($draftitemid, $coursecontext->id, 'local_ildmeta', $file->filearea, 0);
            $lectname = $file->filearea;
            $new->$lectname = $draftitemid;
        }
        $mform->set_data($new);

    } else {
        $new = new stdClass;
        $new->courseid = $courseid;
        $new->overviewimage = '';
        $new->coursetitle = $DB->get_record('course', array('id' => $courseid))->fullname;
        $new->lecturer = '';
        $new->noindexcourse = 0;
        $new->detailimage = '';
        $new->university = 0;
        $new->subjectarea = 0;
        $new->courselanguage = 0;
        $new->processingtime = 0;
        $new->starttime = 0;
        $new->teasertext = '';
        $new->targetgroup = '';
        $new->learninggoals = '';
        $new->structure = '';
        $new->detailslecturer = 2;
        $new->detailsmorelecturer = '';
        $new->detailslecturerimage = '';
        $new->additional_lecturer = 2;
        $new->certificateofachievement = '';
        $new->license = 0;
        $new->videocode = '';
        $new->tags = '';

        $DB->insert_record('ildmeta', $new);
    }

    // Get course title for heading.
    $course = $DB->get_record('course', ['id' => $courseid]);

    // Set the page layout and start the output.
    $PAGE->set_pagelayout('admin');
    $PAGE->set_context($context);
    $PAGE->set_url($url);
    $PAGE->set_title(get_string('title', 'local_ildmeta'));
    $PAGE->set_heading(get_string('heading', 'local_ildmeta') . " - " . $course->shortname);

    echo $OUTPUT->header();

    $toform = array('additional_lecturer' => 2);
    $mform->display($toform);
    $PAGE->requires->js_call_amd('local_ildmeta/ildmeta', 'init', array());

    echo $OUTPUT->footer();
}
