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

require_once($CFG->libdir.'/formslib.php');
require_once($CFG->dirroot.'/lib/coursecatlib.php');

class Status_Form extends moodleform {

    function definition() {
        global $CFG, $DB, $OUTPUT;

        // Setting variables
        $mform =& $this->_form;

        // Adding title and description
        $mform->addElement('html', $OUTPUT->heading(get_string($this->_customdata['mode'].'statusform', 'block_course_status')));
        
        // Adding fieldset
        $attributes = 'size="50" maxlength="200"';
        $attributes_description = 'cols="40" rows="8"';

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'what');
        $mform->setType('what', PARAM_ALPHA);

        $mform->addElement('text', 'shortname', get_string('shortname', 'block_course_status'), $attributes);
        $mform->setType('shortname', PARAM_TEXT);

        $mform->addElement('text', 'displayname', get_string('displayname', 'block_course_status'), $attributes);
        $mform->setType('displayname', PARAM_TEXT);

        $mform->addElement('textarea', 'description', get_string('description', 'block_course_status'), $attributes_description);
        $mform->setType('description', PARAM_CLEANHTML);

        $displaylist = array();
        $parentlist = array();

        $displaylist = coursecat::make_categories_list();

        foreach ($displaylist as $key => $val) {
            if (!has_capability('moodle/course:create', context_coursecat::instance($key))) {
                unset($displaylist[$key]);
            }
        }
        $displaylist[0] = get_string('donotmove', 'block_course_status'); 
        $mform->addElement('select', 'coursecategoryid', get_string('landingcategory', 'block_course_status'), $displaylist);

        $mform->addRule('shortname', null, 'required');
        $mform->addRule('displayname', null, 'required');

        // Adding submit and reset button
        $buttonarray = array();
        $buttonarray[] = &$mform->createElement('submit', 'go_submit', get_string('submit'));
        $buttonarray[] = &$mform->createElement('cancel', 'go_cancel', get_string('cancel'));

        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
        $mform->closeHeaderBefore('buttonar');
        
        $this->add_action_buttons();
    }

    function validation($data, $files = null){

        $errors = array();

        if (empty($data['id'])) {
            if ($DB->record_exists('block_course_status_states', array('shortname' => $data['shortname']))) {
                $errors['shortname'] = get_string('shortnamealreadyexists', 'block_course_status');
            }

            if ($DB->record_exists('block_course_status_states', array('displayname' => $data['displayname']))) {
                $errors['displayname'] = get_string('displaynamealreadyexists', 'block_course_status');
            }
        }

        return $errors;
    }
}
