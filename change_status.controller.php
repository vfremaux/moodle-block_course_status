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
namespace block_course_status\controller;

defined('MOODLE_INTERNAL') || die();

/**
* Master Controler for Status roles
*/
class change_status_controller {

    function process($action, $form = null, $environment = null) {
        global $DB, $COURSE;

        switch($action) {
            /*************************************** save new roles mapping ***************************/
            case 'change':
                $courseid = required_param('course', PARAM_INT);
                if ($data = $form->get_data()) {
                    if ($environment['status'] == $data->approval_status_id) {
                        //status wasn't changed
                        redirect(new moodle_url('/course/view.php', array('id' => $course->id)), get_string('statusunchanged', 'block_course_status'));
                    } else {
                        // set course status
                        if (!course_status_update_status($data->approval_status_id, $data->reason, $courseid)) {
                            print_error('errorupdatestatus', 'block_course_status');
                        }
                        redirect(new \moodle_url('/course/view.php', array('id' => $courseid)), get_string('statusupdated', 'block_course_status'));
                    }
                }
                break;
        }
    }
}