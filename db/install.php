<?php

function xmldb_block_course_status_install_recovery(){
    xmldb_block_course_status_install();
}

function xmldb_block_course_status_install() {
    global $DB;
    
    $st = new StdClass;
    $st->shortname = 'notsubmitted';
    $st->coursecategoryid = 0;
    $standardstatusses[] = $st;

    $st = new StdClass;
    $st->shortname = 'needschange';
    $st->coursecategoryid = 0;
    $standardstatusses[] = $st;

    $st = new StdClass;
    $st->shortname = 'resubmitted';
    $st->coursecategoryid = 0;
    $standardstatusses[] = $st;

    $st = new StdClass;
    $st->shortname = 'approved';
    $st->coursecategoryid = 0;
    $standardstatusses[] = $st;

    $st = new StdClass;
    $st->shortname = 'published';
    $st->coursecategoryid = 0;
    $standardstatusses[] = $st;

    $st = new StdClass;
    $st->shortname = 'suspendeddate';
    $st->coursecategoryid = 0;
    $standardstatusses[] = $st;

    $st = new StdClass;
    $st->shortname = 'suspendedauthor';
    $st->coursecategoryid = 0;
    $standardstatusses[] = $st;

    $sortorder = 0;
    foreach ($standardstatusses as $st) {
        if (!$DB->record_exists('block_course_status_states', array('shortname' => $st->shortname, 'coursecategoryid' => 0))) {
            $st->displayname = get_string($st->shortname, 'block_course_status');
            $st->description = get_string($st->shortname.'_desc', 'block_course_status');
            $st->sortorder = $sortorder;
            $sortorder ++;
            $DB->insert_record('block_course_status_states', $st);
        }
    }
}
