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
 * @author      Valery Fremaux <valery.fremaux@gmail.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @copyright  (C) 1999 onwards Martin Dougiamas  http://dougiamas.com
 */

require_once($CFG->dirroot.'/blocks/course_status/locallib.php');

class block_course_status extends block_base {

    function init() {
        $this->title = get_string('pagedescription', 'block_course_status');
    }

    function applicable_formats() {
        return array('all' => true, 'mod' => false, 'tag' => false, 'my' => false);
    }

    function has_config() {
        return true;
    }

    function specialization() {
    }

    function get_content() {
        global $CFG, $COURSE;

        // related to block/publishflow settings.
        // Using course_approval block in standard Moodle is possible but will not have "fucntional" effect
        if (isset($CFG->moodlenodetype) && $CFG->moodlenodetype == 'learningarea' && !has_capability('moodle/site:config', context_system::instance())) {
            $this->content = NULL;
            return $this->content;
        }
        
        if (!has_capability('block/course_status:viewcoursestatus', context_course::instance($COURSE->id))) {
            $this->content = NULL;
            return $this->content;
        }

        if($this->content !== NULL) {
            return $this->content;
        }

        if (empty($this->instance)) {
            return '';
        }

        $this->content = new stdClass;
        $this->content->text = '<center><span class="coursestatus">'.course_status_get_desc($COURSE).'</span></center>';

        $footer = '';
        if (has_capability('block/course_status:updatecoursestatus', context_course::instance($COURSE->id))) {
            $linkurl = new moodle_url('/blocks/course_status/changestatus.php', array('course' => $COURSE->id, 'id' => $this->instance->id));
            $footer = '<a href="'.$linkurl.'">'.get_string('change', 'block_course_status').'...</a><br/>';
        }
        $linkurl = new moodle_url('/blocks/course_status/statushistory.php', array('course' => $COURSE->id, 'id' => $this->instance->id));
        $footer .= '<a href="'.$linkurl.'">'.get_string('history', 'block_course_status').'...</a><br/>';

        $this->content->footer = "<br/>$footer";

        return $this->content;
    }

    function preferred_width() {
        return 210;
    }
}

