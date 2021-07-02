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
// Todo -> Übersetzen.

defined('MOODLE_INTERNAL') || die();

// Cronjob for generating JSON für MOOC Hub.
$string['generate_moochub_task'] = 'MOOC Hub Task';

// General information.
$string['pluginname'] = 'Course metadata';
$string['heading'] = 'Edit course metadata';
$string['title'] = 'Edit course metadata';
$string['permissiondenied'] = 'Permission denied.';

// Meta data form.
$string['form:metaoverview'] = 'Edit content for detailpage';
$string['form:btnpreview'] = 'Preview';

$string['form:noindexcourse'] = 'Indexing';
$string['form:noindexcourse_yes'] = 'Yes';
$string['form:noindexcourse_no'] = 'No';
$string['form:noindexcourse_limited'] = 'Only course tile';
$string['form:university'] = 'Offering university';
$string['form:subjectarea'] = 'Subject area';
$string['form:overviewimage'] = 'Overview image';
$string['form:picturecredits'] = 'Credits overview image';
$string['form:urloverviewimage'] = 'URL overview image';
$string['form:licenceoverviewimage'] = 'Licence overview image';
$string['form:urllicenceoverviewimage'] = 'URL licence overview image';
$string['form:detailimage'] = 'Alternative image for YouTube video link';
$string['form:videocode'] = 'Youtube-Link';
$string['form:coursetitle'] = 'Course title';
$string['form:lecturer'] = 'Course offered by';
$string['form:courselanguage'] = 'Course language';
$string['form:processingtime'] = 'Average workload';
$string['form:processingtime_description'] = 'Please enter the time in full hours only.';
$string['form:starttime'] = 'Available from';

$string['form:detailinformation'] = 'Course information';
$string['form:teasertext'] = 'What awaits you in this course?';
$string['form:targetgroup'] = 'Target group';
$string['form:learninggoals'] = 'What can you learn in this course?';
$string['form:structure'] = 'Outline';
$string['form:certificateofachievement'] = 'Confirmation of participation';

$string['form:lecturerdetails'] = 'Authors information';
$string['form:lecturer_type'] = 'Please select:';
$string['form:lecturer_type_0'] = 'Author';
$string['form:lecturer_type_1'] = 'Provider';
$string['form:detailslecturer_image'] = 'Profile picture';
$string['form:detailslecturer'] = 'Profile description';
$string['form:removelecturer'] = 'Remove input fields';

$string['form:additionallecturer'] = 'Add additional fields for further authors and providers';
$string['form:additionalfields'] = 'Additional fields';
$string['form:inputnumber'] = 'Please enter the amount of additional fields you need.';
$string['form:addnewfields'] = 'Add fields';

$string['form:additionalinformation'] = 'Licence';
$string['form:license'] = 'Licence';

$string['form:tags'] = 'Tags';
$string['form:tagsinput'] = 'Keywords';

$string['form:savechanges'] = 'Save changes';
$string['form:savebacktocourse'] = 'Save and back to course';
$string['form:cancel'] = 'Cancel';

// Delete additional lecturer fields.
$string['form:lecturer_delete'] = 'Delete entry';
$string['form:lecturer_delete_text'] = 'Do you really want to delete this entry?';
$string['form:lecturer_false'] = 'No';
$string['form:lecturer_true'] = 'Yes, delete this entry';
$string['form:lecturer_delete_confirm'] = 'Confirm deletion';