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

function xmldb_local_ildmeta_upgrade($oldversion) {
    global $DB;
    $dbman = $DB->get_manager();

    if ($oldversion < 2018091800) {
        $table = new xmldb_table('ildmeta');

        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('videocode', XMLDB_TYPE_CHAR, '120', null, XMLDB_NOTNULL, null, null);
        $table->add_field('overviewimage', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('detailimage', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('coursetitle', XMLDB_TYPE_CHAR, '120', null, XMLDB_NOTNULL, null, null);
        $table->add_field('university', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, null);
        $table->add_field('noindexcourse', XMLDB_TYPE_CHAR, '120', null, XMLDB_NOTNULL, null, null);
        $table->add_field('subjectarea', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, null);
        $table->add_field('lecturer', XMLDB_TYPE_CHAR, '120', null, XMLDB_NOTNULL, null, null);
        $table->add_field('courselanguage', XMLDB_TYPE_CHAR, '120', null, XMLDB_NOTNULL, null, null);
        $table->add_field('processingtime', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('starttime', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('teasertext', XMLDB_TYPE_TEXT, '120', null, XMLDB_NOTNULL, null, null);
        $table->add_field('targetgroup', XMLDB_TYPE_TEXT, '120', null, XMLDB_NOTNULL, null, null);
        $table->add_field('learninggoals', XMLDB_TYPE_TEXT, '120', null, XMLDB_NOTNULL, null, null);
        $table->add_field('structure', XMLDB_TYPE_TEXT, '120', null, XMLDB_NOTNULL, null, null);
        $table->add_field('detailslecturer', XMLDB_TYPE_TEXT, '120', null, XMLDB_NOTNULL, null, null);
        $table->add_field('detailsmorelecturer', XMLDB_TYPE_TEXT, '120', null, XMLDB_NOTNULL, null, null);
        $table->add_field('license', XMLDB_TYPE_CHAR, '120', null, XMLDB_NOTNULL, null, null);
        $table->add_field('tags', XMLDB_TYPE_CHAR, '120', null, XMLDB_NOTNULL, null, null);
        $table->add_field('certificateofachievement', XMLDB_TYPE_TEXT, '120', null, XMLDB_NOTNULL, null, null);

        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
    }

    if ($oldversion < 2020121800) {

        // Define field picturecredits to be added to ildmeta.
        $table = new xmldb_table('ildmeta');
        $field = new xmldb_field('picturecredits', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'certificateofachievement');

        // Conditionally launch add field picturecredits.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Ildmeta savepoint reached.
        upgrade_plugin_savepoint(true, 2020121800, 'local', 'ildmeta');
    }

    if ($oldversion < 2021070400) {

        // Define field urloverviewimage to be added to ildmeta.
        $table = new xmldb_table('ildmeta');
        $field = new xmldb_field('urloverviewimage', XMLDB_TYPE_CHAR, '1333', null, null, null, null, 'picturecredits');

        // Conditionally launch add field urloverviewimage.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field licenceoverviewimage to be added to ildmeta.
        $table = new xmldb_table('ildmeta');
        $field = new xmldb_field('licenceoverviewimage', XMLDB_TYPE_INTEGER, '1', null, null, null, null, 'urloverviewimage');

        // Conditionally launch add field licenceoverviewimage.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field urllicenceoverviewimage to be added to ildmeta.
        $table = new xmldb_table('ildmeta');
        $field = new xmldb_field('urllicenceoverviewimage', XMLDB_TYPE_CHAR, '1333', null, null, null, null, 'licenceoverviewimage');

        // Conditionally launch add field urllicenceoverviewimage.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Ildmeta savepoint reached.
        upgrade_plugin_savepoint(true, 2021070400, 'local', 'ildmeta');
    }

    if ($oldversion < 2021070500) {

        // Define table ildmeta_settings to be created.
        $table = new xmldb_table('ildmeta_settings');

        // Adding fields to table ildmeta_settings.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('name', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('value', XMLDB_TYPE_TEXT, null, null, null, null, null);

        // Adding keys to table ildmeta_settings.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for ildmeta_settings.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Ildmeta savepoint reached.
        upgrade_plugin_savepoint(true, 2021070500, 'local', 'ildmeta');
    }
    

    return true;
}








