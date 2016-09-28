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

require($CFG->dirroot.'/lib/formslib.php');

class StatusRolesForm extends moodleform {

    function definition() {
        global $DB;

        $mform = $this->_form;

        $mform->addelement('hidden', 'what');
        $mform->setType('what', PARAM_ALPHA);

        $states = $DB->get_records('block_course_status_states', array());

        $roleoptions = role_get_names();

        foreach ($states as $st) {
            $mform->addElement('header', $st->displayname);
            $mform->addelement('select', 'staterole'.$st->id, get_string('roles', 'block_course_status'), $roleoptions);
        }

        $this->add_action_buttons();
    }
}