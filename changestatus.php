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
 * @package  block_course_status
 * @category blocks
 * allows user to change the status of the given course
 *
 */
use block_course_status\controller\change_status_controller;
 
require('../../config.php');
require_once($CFG->dirroot.'/course/lib.php');
require_once($CFG->dirroot.'/blocks/course_status/forms/changestatus_form.php');

$id = required_param('course', PARAM_INT);
$blockid = required_param('id', PARAM_INT);

$context = context_course::instance($id);

$PAGE->set_context($context);

$url = new moodle_url('/blocks/course_status/changestatus.php', array('course' => $id, 'id' => $blockid));

if (!$course = $DB->get_record('course', array('id' => $id))) {
    print_error('coursemisconf');
}

// Security.
require_login($course);
require_capability('block/course_status:updatecoursestatus', $context);

$PAGE->set_url($url);
$PAGE->navbar->add(get_string('coursestatus', 'block_course_status'));
$PAGE->set_heading($COURSE->fullname);
$PAGE->set_title($COURSE->fullname);

$mform = new changestatus_form(new moodle_url('/blocks/course_status/changestatus.php', array('id' => $blockid, 'course' => $id)));

// Processing section.

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/course/view.php', array('id' => $id)), get_string('statusunchanged', 'block_course_status'));
}

$status = course_status_get_status($course);

$action = optional_param('what', false, PARAM_ALPHA);
if (!empty($action)) {
    include_once($CFG->dirroot.'/blocks/course_status/change_status.controller.php');
    $controller = new change_status_controller();
    $result = $controller->process($action, $mform, array('status' => $status));
}

echo $OUTPUT->header();

echo $OUTPUT->heading(get_string('actualstate', 'block_course_status', format_string($status->displayname)));
echo $OUTPUT->box(format_string($status->description));
echo $OUTPUT->heading(get_string('statuschangeheading', 'block_course_status'));

$mform->display();

echo $OUTPUT->footer();

