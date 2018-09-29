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
class status_roles_controller {

    function process($action, $form = null, $environment = null) {
        global $DB;

        switch($action) {
            /*************************************** save new roles mapping ***************************/
            case 'save':
                $DB->delete_records('block_course_status_roles', array());

                if (!$form->is_cancelled()) {
                    if ($data = $form->get_data()) {
    
                        foreach ($data as $key => $value) {
                            if (strstr($key, '_') !== false) {
                                list($statusid, $roleid) = explode('_', $key);
                                $rec = new StdClass();
                                $rec->statusid = $statusid;
                                $rec->roleid = $roleid;
                                $DB->insert_record('block_course_status_roles', $rec);
                            }
                        }
                    } else {
                        echo $OUTPUT->header();
                        echo $OUTPUT->heading(get_string('coursestatusrolesheading', 'block_course_status'));
                        //no data submitted : print the form
                        $data = new \stdClass();
                        $data->what = 'save';
                        $form->set_data($data);
                        $form->display();
                        echo $OUTPUT->footer();
                        die;
                    }
                }
                break;
        }
    }
}