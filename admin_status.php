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
 * @author     Valery Fremaux <valery@valeisti.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 1999 onwards Martin Dougiamas  http://dougiamas.com
 */
use block_course_status\controller\status_controller;

require('../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot.'/blocks/course_status/forms/form_status.php');

$url = new moodle_url('/blocks/course_status/admin_status.php');
$PAGE->set_url($url);

// Security.

$context = context_system::instance();
$PAGE->set_context($context);
require_login();
require_capability('moodle/site:config', $context);

// Input parameters

$action = optional_param('what', '', PARAM_TEXT);

$PAGE->set_context($context);
$PAGE->set_pagelayout('admin');
$PAGE->set_heading(get_string('adminstates', 'block_course_status'));
$PAGE->navbar->add(get_string('pluginname', 'block_course_status'));
$PAGE->navbar->add(get_string('managestates', 'block_course_status'));

$renderer = $PAGE->get_renderer('block_course_status');

// Master controller

if (!empty($action)) {

    $mform = new Status_Form($url, array('mode' => $action));

    include_once($CFG->dirroot.'/blocks/course_status/admin_status.controller.php');
    $controller = new status_controller();
    $result = $controller->process($action, $mform);
}

echo $OUTPUT->header();

echo $OUTPUT->heading(get_string('coursestatusheading', 'block_course_status'));

echo $renderer->tabs('states');

$statusaddlink = new moodle_url('/blocks/course_status/admin_status.php', array('what' => 'add'));
echo '<div style="right"><a href="'.$statusaddlink.'" >'.get_string('addstatus', 'block_course_status').'</a></div>';

$data = $DB->get_records('block_course_status_states', array(), 'sortorder');
$maxorder = $DB->get_field('block_course_status_states', 'MAX(sortorder)', array());

if (!empty($data)) {
    $table = new html_table();
    $table->head = array("<b>".get_string('shortname', 'block_course_status')."</b>", 
                                "<b>".get_string('displayname', 'block_course_status')."</b>",
                                "<b>".get_string('description', 'block_course_status')."</b>",
                                "<b>".get_string('coursecategory', 'block_course_status')."</b>",
                                '');
    $table->width = "100%";
    $table->align = array('left', 'left', 'left', 'left', 'center');
    $table->size = array('10%', '20%', '40%', '20%', '10%');
    $table->data = array();

    foreach ($data as $status) {
        $view = array();
        $view[] = $status->shortname;
        $view[] = format_string($status->displayname);
        $view[] = format_string($status->description);
        if (!empty($status->coursecategoryid)) {
            $categoryname = $DB->get_field('course_categories', 'name', array('id' =>  $status->coursecategoryid));
            $categoryid = $status->coursecategoryid;
            $parent = $DB->get_field('course_categories', 'parent', array('id' => $categoryid));
            if ($parent) {
                while ($parent) {
                    $parentcat = $DB->get_record('course_categories', array('id' => $parent));
                    $categoryname = $parentcat->name.' / '.$categoryname;
                    $categoryid = $parentcat->id;
                    $parent = $parentcat->parent;
                }
            }
            $view[] = $categoryname;
        } else {
            $view[] = get_string('donotmove', 'block_course_status');
        }

        $updatestr = get_string('update');
        $updatelink = new moodle_url('/blocks/course_status/admin_status.php', array('what' => 'update', 'id' => $status->id));
        $commands = '<a href="'.$updatelink.'" title="'.$updatestr.'" ><img src="'.$OUTPUT->pix_url('t/edit').'"></a>';

        $deletestr = get_string('delete');
        $deleteurl = new moodle_url('/blocks/course_status/admin_status.php', array('what' => 'delete', 'id' => $status->id));
        $commands .= ' <a href="'.$deleteurl.'" title="'.$deletestr.'" ><img src="'.$OUTPUT->pix_url('/t/delete').'"></a>';

        if ($status->sortorder > 0) {
            $upstr = get_string('up');
            $upurl = new moodle_url('/blocks/course_status/admin_status.php', array('what' => 'up', 'id' => $status->id, 'sesskey' => sesskey()));
            $commands .= ' <a href="'.$upurl.'" title="'.$upstr.'" ><img src="'.$OUTPUT->pix_url('t/up').'"></a>';
        }
        if ($status->sortorder < $maxorder) {
            $downstr = get_string('down');
            $downurl = new moodle_url('/blocks/course_status/admin_status.php', array('what' => 'down', 'id' => $status->id, 'sesskey' => sesskey()));
            $commands .= ' <a href="'.$downurl.'" title="'.$downstr.'" ><img src="'.$OUTPUT->pix_url('t/down').'"></a>';
        }
        $view[] = $commands;
        unset($view['id']);
        $table->data[] = $view;
    }
    echo html_writer::table($table);
} else {
    echo $OUTPUT->box($OUTPUT->notification(get_string('nostatus', 'block_course_status')));
}

echo $OUTPUT->footer();