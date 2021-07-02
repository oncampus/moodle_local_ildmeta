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
$string['pluginname'] = 'Kurs Metadaten';
$string['heading'] = 'Kurs Metadaten bearbeiten';
$string['title'] = 'Kurs Metadaten bearbeiten';
$string['permissiondenied'] = 'Sie haben nicht die erforderlichen Berechtigungen dieses Modul zu verwenden.';

// Meta data form.
$string['form:metaoverview'] = 'Inhalte für die Detailseite bearbeiten';
$string['form:btnpreview'] = 'Vorschau';

$string['form:noindexcourse'] = 'Indexierung';
$string['form:noindexcourse_yes'] = 'Ja';
$string['form:noindexcourse_no'] = 'Nein';
$string['form:noindexcourse_limited'] = 'Nur Kurskachel';
$string['form:university'] = 'Anbietende Hochschulen';
$string['form:subjectarea'] = 'Fachbereich Wissensgebiet';
$string['form:overviewimage'] = 'Kachelbild';
$string['form:picturecredits'] = 'Bildnachweis Kachelbild';
$string['form:urloverviewimage'] = 'URL Kachelbild';
$string['form:licenceoverviewimage'] = 'Lizenz Kachelbild';
$string['form:urllicenceoverviewimage'] = 'URL Lizenz Kachelbild';
$string['form:detailimage'] = 'Bild für Detailseite (alternativ zum Youtube Video)';
$string['form:videocode'] = 'Youtube-Link';
$string['form:coursetitle'] = 'Kurstitel';
$string['form:lecturer'] = 'Dozent/Anbieter';
$string['form:courselanguage'] = 'Kurssprache';
$string['form:processingtime'] = 'Bearbeitungszeit';
$string['form:processingtime_description'] = 'Bitte die Zeit in Stunden als Ganzzahl angeben.';
$string['form:starttime'] = 'Kursbeginn';

$string['form:detailinformation'] = 'Kursinformationen';
$string['form:teasertext'] = 'Was erwartet Sie in diesem Kurs?';
$string['form:targetgroup'] = 'Zielgruppe';
$string['form:learninggoals'] = 'Was können Sie in diesem Kurs lernen?';
$string['form:structure'] = 'Gliederung';
$string['form:certificateofachievement'] = 'Bescheinigung der Teilnahme';

$string['form:lecturerdetails'] = 'Angaben zu Autor/innen und Anbieter/innen';
$string['form:lecturer_type'] = 'Bitte wählen:';
$string['form:lecturer_type_0'] = 'Autor*in';
$string['form:lecturer_type_1'] = 'Anbieter*in';
$string['form:detailslecturer_image'] = 'Profilbild';
$string['form:detailslecturer'] = 'Profilbeschreibung';
$string['form:removelecturer'] = 'Eingabefeld entfernen';

$string['form:additionallecturer'] = 'Weitere Felder für Autor/innen und Anbieter/innen hinzufügen';
$string['form:additionalfields'] = 'Zusätzliche Felder';
$string['form:inputnumber'] = 'Bitte die Anzahl der zusätzlich benötigten Felder zum Anlegen weiterer Autor*innen und Anbieter*innen angeben.';
$string['form:addnewfields'] = 'Felder hinzufügen';

$string['form:additionalinformation'] = 'Lizenz';
$string['form:license'] = 'Lizenz';

$string['form:tags'] = 'Tags';
$string['form:tagsinput'] = 'Schlagworte';

$string['form:savechanges'] = 'Änderungen speichern';
$string['form:savebacktocourse'] = 'Speichern und zurück zum Kurs';
$string['form:cancel'] = 'Abbrechen';

// Delete additional lecturer fields.
$string['form:lecturer_delete'] = 'Eintrag löschen';
$string['form:lecturer_delete_text'] = 'Soll der ausgewählte Eintrag wirklich gelöscht werden?';
$string['form:lecturer_false'] = 'Nein';
$string['form:lecturer_true'] = 'Ja, den Eintrag löschen';
$string['form:lecturer_delete_confirm'] = 'Löschen bestätigen';