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

defined('MOODLE_INTERNAL') || die();

/**
 * @package    block_course_status
 * @category   blocks
 * @author     Valery Fremaux  <valery@valeisti.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 1999 onwards Martin Dougiamas  http://dougiamas.com
 */
require_once($CFG->dirroot.'/lib/formslib.php');
require_once($CFG->dirroot.'/blocks/course_status/locallib.php');

class changestatus_form extends moodleform {

    // form definition
    function definition() {
        global $CFG, $COURSE;
        $mform =& $this->_form;

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'what', 'change');
        $mform->setType('what', PARAM_ALPHA);

        // get a list of courses that are learning path templates
        if ($status_arr = course_status_get_options($COURSE)) {

            foreach($status_arr as $status) {
                $options[$status->id] = $status->displayname;
            }
            $mform->addElement('select', 'approval_status_id', get_string('changestatusto', 'block_course_status'), $options);
            $mform->setDefault('approval_status_id', course_status_current_status_id($COURSE));

            $mform->addElement('textarea','reason', get_string('reason', 'block_course_status'),'rows="3" cols="50"');
            $mform->addRule('reason', get_string('missingstatusreason', 'block_course_status'), 'required', null, 'client');

        } else {
            print_error('errornooptions', 'block_course_status');
        }

        // submit buttons
        $this->add_action_buttons(true, get_string('update'));

    }
}
