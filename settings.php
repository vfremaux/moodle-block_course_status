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

// $settings->add(new admin_externalpage('coursestatus', get_string('coursestatusoptions', 'block_course_status'), $CFG->wwwroot . '/blocks/course_status/admin_status.php'));

$settings->add(new admin_setting_heading('coursestatus', get_string('coursestatusoptions', 'block_course_status'),
                   get_string('coursestatusoptionsdesc', 'block_course_status', $CFG->wwwroot.'/blocks/course_status/admin_status.php')));

$settings->add(new admin_setting_heading('coursestatusroles', get_string('coursestatusrolesheading', 'block_course_status'),
                   get_string('coursestatusroleoptionsdesc', 'block_course_status', $CFG->wwwroot.'/blocks/course_status/admin_status.php')));
