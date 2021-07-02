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
require_once('delete_lecturer_form.php');
defined('MOODLE_INTERNAL') || die();

$courseid = optional_param('courseid', array(), PARAM_INT);
$lecturerid = optional_param('id', array(), PARAM_INT);

$context = context_system::instance();

$url = new moodle_url('/local/ildmeta/delete_lecturer.php', array('courseid' => $courseid));

// Prevent access for students/guests.
if (!has_capability('local/ildmeta:delete_lecturer', context_system::instance())) {
    redirect(new moodle_url('/'));
}

require_login();

$PAGE->set_pagelayout('admin');
$PAGE->set_context($context);
$PAGE->set_url($url);
$PAGE->set_title(get_string('title', 'local_ildmeta'));
$PAGE->set_heading(get_string('heading', 'local_ildmeta'));

$tblmeta = 'ildmeta';
$tbllecturer = 'ildmeta_additional';

$url = new moodle_url('/local/ildmeta/delete_lecturer.php', array('courseid' => $courseid, 'id' => $lecturerid));
$mform = new ildmeta_delete_lecturer_form($url);


if ($mform->is_cancelled()) {

    $url = new moodle_url('/local/ildmeta/edit.php', array('courseid' => $courseid));
    redirect($url);

} else if ($fromform = $mform->get_data()) {

    if (!$fromform->submitbutton) {
        $url = new moodle_url('/local/ildmeta/edit.php', array('courseid' => $courseid));
        redirect($url);
    } else {

        // First delete from ildmeta_additional.
        $fields = $DB->get_records_sql('SELECT name FROM {ildmeta_additional} WHERE courseid = ? AND name LIKE ?',
            array('courseid' => $courseid, 'name' => '%' . $lecturerid . ''));

        $error = false;

        foreach ($fields as $f) {
            $params = array('courseid' => $courseid, 'name' => $f->name);

            if (!$DB->delete_records($tbllecturer, $params)) {
                $error = true;
            }
        }

        // Then adjust the counter in ildmeta.
        $sql = "UPDATE {ildmeta} SET detailslecturer=detailslecturer-1 WHERE courseid = ?";
        $params = array('courseid' => $courseid);

        if (!$DB->execute($sql, $params)) {
            $error = true;
        }

        $url = new moodle_url('/local/ildmeta/edit.php', array('courseid' => $courseid));
        if ($error) {
            redirect($url, 'Fehler beim Löschen der Datensätze!', null, \core\output\notification::NOTIFY_ERROR);
        } else {
            redirect($url, 'Datensätze erfolgreich gelöscht!', null, \core\output\notification::NOTIFY_SUCCESS);
        }

    }

} else {

    echo $OUTPUT->header();

    $mform->display();

    echo $OUTPUT->footer();

}
