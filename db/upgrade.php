<?php

// This file keeps track of upgrades to this block
//
// Sometimes, changes between versions involve
// alterations to database structures and other
// major things that may break installations.
//
// The upgrade function in this file will attempt
// to perform all the necessary actions to upgrade
// your older installtion to the current version.
//
// If there's something it cannot do itself, it
// will tell you what you need to do.
//
// The commands in here will all be database-neutral,
// using the functions defined in lib/ddllib.php

function xmldb_block_course_status_upgrade($oldversion=0) {
    $result = true;

    $dbman = $DB->get_manager();

    if ($result && $oldversion < 2011050200) {

    /// Define field coursecategoryid to be added to course_approval_status
        $table = new xmldb_table('course_approval_status');
        $field = new xmldb_field('coursecategoryid', XMLDB_TYPE_INTEGER, '11', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', 'description');

    /// Launch add field coursecategoryid
        if (!$dbman->field_exists($ttable, $field)) {
            $dbman->add_field($table, $field);
        }

    /// Define table course_approval_role_status to be created
        $table = new xmldb_table('course_approval_role_status');

    /// Adding fields to table course_approval_role_status
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('statusid', XMLDB_TYPE_INTEGER, '11', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0');
        $table->add_field('roleid', XMLDB_TYPE_INTEGER, '11', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0');

    /// Adding keys to table course_approval_role_status
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

    /// Launch create table for course_approval_role_status
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        upgrade_block_savepoint(true, 2011050200, 'course_status');
    }
    
    if ($result && $oldversion < 2011050201){

    // Define field coursecategoryid to be added to course_approval_status
        $table = new xmldb_table('course_approval_status');
        $field = new xmldb_field('sortorder', XMLDB_TYPE_INTEGER, '4', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', 'coursecategoryid');

    // Launch add field coursecategoryid
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // pre order existing status definitions
        if($status = $DB->get_records('course_approval_status', array(), 'id')) {
            $i = 0;
            foreach ($status as $st) {
                $st->sortorder = $i;
                $i++;
                $DB->update_record('course_approval_status', $st);
            }
        }
        upgrade_block_savepoint(true, 2011050201, 'course_status');
    }
    
    return $result;
}
