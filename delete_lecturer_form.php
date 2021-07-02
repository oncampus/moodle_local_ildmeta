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

class ildmeta_delete_lecturer_form extends moodleform {

    // Add elements to form.
    public function definition() {
        $mform = $this->_form; // Don't forget the underscore!

        $mform->addElement('html', '<h2>' . get_string('form:lecturer_delete', 'local_ildmeta') . '</h2>');
        $mform->addElement('html', '<p>' . get_string('form:lecturer_delete_text', 'local_ildmeta') . '</p>');
        $buttonarray = array();
        $buttonarray[] = $mform->createElement('submit', 'submitbutton', get_string('form:lecturer_true', 'local_ildmeta'));
        $buttonarray[] = $mform->createElement('cancel', 'cancel', get_string('form:lecturer_false', 'local_ildmeta'));
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
    }

    // Custom validation should be added here.
    public function validation($data, $files) {
        return array();
    }
}