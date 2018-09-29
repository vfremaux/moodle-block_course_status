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
 * The mod_forum course searched event.
 *
 * @package    block_course_status
 * @category   blocks
 * @copyright  2015 Valery Fremaux (valery.fremaux@gmail.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_course_status\event;

defined('MOODLE_INTERNAL') || die();

/**
 * The block_course_status status changed event class.
 *
 * @property-read array $other {
 *      Extra information about the event.
 *
 *      - string searchterm: The searchterm used on forum search.
 * }
 *
 * @package    block_course_status
 * @since      Moodle 2.7
 * @copyright  2015 Valery Fremaux (valery.fremaux@gmail.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_status_updated extends \core\event\base {

    /**
     * Init method.
     *
     * @return void
     */
    protected function init() {
        $this->data['crud'] = 'w';
        $this->data['edulevel'] = self::LEVEL_TEACHING;
    }

    /**
     * Returns description of what happened.
     *
     * @return string
     */
    public function get_description() {
        $oldstatus = s($this->other['oldstatus']);
        $newstatus = s($this->other['status']);
        return "The user with id '$this->userid' has updated the course status with id '$this->courseid' from $oldstatus to $newstatus";
    }

    /**
     * Return localised event name.
     *
     * @return string
     */
    public static function get_name() {
        return get_string('eventcoursestatusupdated', 'block_course_status');
    }

    /**
     * Get URL related to the action
     *
     * @return \moodle_url
     */
    public function get_url() {
        return new \moodle_url('/course/view.php', array('id' => $this->courseid));
    }

    /**
     * Return the legacy event log data.
     *
     * @return array|null
     */
    protected function get_legacy_logdata() {
        return array($this->courseid, 'course', 'status updated', 'view.php?id='.$this->courseid, $this->other['status']);
    }

    /**
     * Custom validation.
     *
     * @throws \coding_exception
     * @return void
     */
    protected function validate_data() {

        parent::validate_data();

        if (!isset($this->other['oldstatus'])) {
            throw new \coding_exception('The \'oldstatus\' value must be set in other.');
        }

        if (!isset($this->other['status'])) {
            throw new \coding_exception('The \'status\' value must be set in other.');
        }

        if ($this->contextlevel != CONTEXT_COURSE) {
            throw new \coding_exception('Context level must be CONTEXT_COURSE.');
        }
    }

}

