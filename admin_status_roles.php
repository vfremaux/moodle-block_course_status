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
 */
use block_course_status\controller\status_roles_controller;

require('../../config.php');
require_once($CFG->dirroot.'/blocks/course_status/forms/status_roles_form.php');

$url = new moodle_url('/blocks/course_status/admin_status_roles.php');

// Security.

$context = context_system::instance();
require_login();
require_capability('moodle/site:config', $context);

$status = $DB->get_records('block_course_status_states', array());
$roles = $DB->get_records('role', array(), 'sortorder');

$map = array();
if ($data = $DB->get_records('block_course_status_roles', array())) {
    foreach ($data as $datum) {
        $map[$datum->statusid.'_'.$datum->roleid] = 1;
    }
}

$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_title(get_string('coursestatusrolesheading', 'block_course_status'));
$PAGE->navbar->add(get_string('pluginname', 'block_course_status'));
$PAGE->navbar->add(get_string('manageroles', 'block_course_status'));

$renderer = $PAGE->get_renderer('block_course_status');

$mform = new StatusRolesForm();
$action = optional_param('what', false, PARAM_ALPHA);
if ($action) {
    include_once($CFG->dirroot.'/blocks/course_status/admin_status_roles.controller.php');
    $controller = new status_roles_controller();
    $result = $controller->process($action);
}

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('coursestatusrolesheading', 'block_course_status'));

echo $renderer->tabs('roles');


echo $OUTPUT->footer();
