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
 * @package    block_course_status
 * @category   blocks
 * @author      Valery Fremaux <valery.fremaux@gmail.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 1999 onwards Martin Dougiamas  http://dougiamas.com
 *
 * reports the status history of the given course 
 */
require('../..//config.php');
require_once($CFG->dirroot.'/course/lib.php');
require_once($CFG->libdir.'/pagelib.php');
require_once($CFG->libdir.'/blocklib.php'); // This includes lib/pagelib.php and course/lib.php

$courseid = required_param('course', PARAM_INT);
$blockid = required_param('id', PARAM_INT);

$url = new moodle_url('/blocks/course_status/statushistory.php', array('course' => $courseid, 'id' => $blockid));

if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('coursemisconf');
}

require_login($course);
require_capability('block/course_status:viewcoursestatus', context_course::instance($course->id));

$PAGE->set_url($url);
$PAGE->set_heading(get_string('statushistory', 'block_course_status'));
$PAGE->navbar->add(get_string('statushistory', 'block_course_status'));

echo $OUTPUT->header();

$sql = "
    SELECT
        h.id,
        s.displayname,
        s.description,
        h.timestamp,
        h.reason,
        u.username
    FROM
        {block_course_status_history} h,
        {block_course_status_states} s,
        {user} u
    WHERE
        h.courseid = ? AND 
        h.approval_status_id = s.id AND 
        u.id = h.userid 
    ORDER by 
        timestamp desc
";

$table = new stdClass();

if ($records = $DB->get_records_sql($sql, array($course->id))) {
    $strstatus = get_string('status', 'block_course_status');
    $strtime = get_string('time');
    $struser = get_string('user');
    $strreason = get_string('reason', 'block_course_status');

    $table = new html_table();
    $table->head = array("<b>$strstatus</b>", "<b>$strtime</b>", "<b>$struser</b>", "<b>$strreason</b>");
    $table->align = array('left', 'center', 'center', 'left');
    $table->wrap = array('nowrap', 'nowrap', 'nowrap');
    $table->width = '100%';
    $table->size = array('30%', '20%', '20%', '30%');

    foreach ($records as $record) {

        $row = array();

        $row[] = $record->displayname;
        $row[] = userdate($record->timestamp);
        $row[] = $record->username;
        $row[] = $record->reason;

        $table->data[] = $row;
    }
}

echo $OUTPUT->heading(get_string('historyheading', 'block_course_status'));
if (!empty($table->data)) {
    echo html_writer::table($table);
} else {
    echo $OUTPUT->notification(get_string('nohistory', 'block_course_status'));
}

echo '<br/><center>';
$opts['id'] = $courseid;
echo $OUTPUT->single_button(new moodle_url('/course/view.php', $opts), get_string('backtocourse', 'block_course_status'));
echo '</center>';

echo $OUTPUT->footer();
