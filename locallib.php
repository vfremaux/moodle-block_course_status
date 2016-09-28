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

/**
 * Update a course status return true or false
 *
 * put in its own function because there are 2 tables to update and 2 ensure consistant usage
 *
 * @param integer status  
 * @param text $reason   
 * @param object $course
 * @param object $context
 */
define('COURSE_STATUS_INITIAL', 0);
define('COURSE_STATUS_PUBLISHED', 5);

function course_status_update_status($status, $reason, &$courseorid) {
    global $USER, $CFG, $DB;

    if (is_object($courseorid)) {
        $course = $courseorid;
    } else {
        $course = $DB->get_record('course', array('id' => $courseorid));
    }

    // Add a new status value in status history.
    $history = new StdClass();
    $history->courseid = $course->id;
    $history->approval_status_id = $status;
    $history->reason = $reason;
    $history->timestamp = time();
    $history->userid = $USER->id;

    $DB->insert_record('block_course_status_history', $history);

    // custom course format hook
    $file = $CFG->dirroot.'/course/format/'.$course->format.'/lib.php';

    if (file_exists($file)) {
        include_once($file);
        $function = $course->format . '_update_course_status';
        if (function_exists($function)) {
            if (!$function($status, $reason, $course)) {
                print_error('could not execute custom format hook');
                return false;
            }
        } else {
            // move on
        }
    }

    return true;
}


/**
 * Returns an array of status's options valid to switch to for the given course 
 *
 * @param object $course
 */

function course_status_get_options($course) {
    global $CFG, $USER, $DB;

    $currentstatusid = course_status_current_status_id($course->id);

    (empty($currentstatus) ? $currentstatus = 0 : '');

    // get this users course context roles
    $context = context_course::instance($course->id);
    $roles = get_user_roles($context, $USER->id, true); 

    $allowstatus = array($currentstatusid); // allowed status 

    // grant status options by role
    // additional conditions based on current status
    //   developer note:  this works but is a bit clunky and may revisit if have time.  although the main idea
    //                    is that this should be easy to see what's being applied if you ever have to change 
    //                    the rules - not some impenetrable one liner.
    foreach ($roles as $role) {
        $rolemap = $DB->get_records('block_course_status_roles', array('roleid' => $role->roleid));
        if ($rolemap) {
            foreach ($rolemap as $map) {
                array_push($allowstatus, $map->statusid);
            }
        }

        /*
        switch($role->shortname) {

            case ROLE_ADMIN:
            case ROLE_SUPERADMIN:
                // no restrictions on admins
                array_push($allowstatus, COURSE_STATUS_NOTSUBMITTED);
                array_push($allowstatus, COURSE_STATUS_SUBMITTED);
                array_push($allowstatus, COURSE_STATUS_NEEDSCHANGE);
                array_push($allowstatus, COURSE_STATUS_RESUBMITTED);
                array_push($allowstatus, COURSE_STATUS_APPROVED);
                array_push($allowstatus, COURSE_STATUS_PUBLISHED);
                array_push($allowstatus, COURSE_STATUS_SUSPENDEDDATE);
                array_push($allowstatus, COURSE_STATUS_SUSPENDEDAUTHOR);
                break;

            case ROLE_HEADEDITOR:
                // head editors have more limited options but can apply at any time
                array_push($allowstatus, COURSE_STATUS_NEEDSCHANGE);
                array_push($allowstatus, COURSE_STATUS_APPROVED);
                array_push($allowstatus, COURSE_STATUS_PUBLISHED);
                break;

            case ROLE_LPEDITOR:
                // lp editors can only submit for approval at the appropriate juncture
                if ( $currentstatus ==  COURSE_STATUS_NOTSUBMITTED ) {
                     array_push($allowstatus, COURSE_STATUS_SUBMITTED);
                }
                if ( $currentstatus ==  COURSE_STATUS_NEEDSCHANGE ) {
                     array_push($allowstatus, COURSE_STATUS_RESUBMITTED);
                }
                break;

            default:
                // if anyone else should somehow get here (they shouldn't) they won't be given any options
                break; 
        }
        */
    }
    
    if (!empty($allowstatus)) {
        $statusstr = implode(',', $allowstatus);
        $whereclause = (has_capability('moodle/site:config', context_system::instance())) ? '' : " WHERE id IN ('{$statusstr}') ";

        $sql = "
            SELECT
                id,
                shortname,
                displayname,
                description
            FROM
                {block_course_status_states}
            $whereclause
            ORDER BY
                id
        ";

        if ($status_array = $DB->get_records_sql($sql)) {
            return $status_array;
        }
    }
    return;
}

/**
 * Returns text description of the given course status.
 *
 * @param mixed $courseorid course object or course ID
 */
function course_status_get_desc($courseorid) {
    global $DB;

    $currentstatusid = course_status_current_status_id($courseorid);

    if (!$currentstatusid) {
        return get_string('nostatusset', 'block_course_status'); 
    } else {
        // lookup status of course
        return $DB->get_field('block_course_status_states', 'displayname', array('id' => $currentstatusid));
    }
}

/**
 * get the status info
 *
 * @param mixed $courseorid course object or course ID
 */
function course_status_get_status($courseorid) {
    global $DB;

    $currentstatusid = course_status_current_status_id($courseorid);

    if (!$currentstatusid) {
        $status = new StdClass;
        $status->shortname = 'NULL';
        $status->displayname = get_string('nostatussetshort', 'block_course_status');
        $status->description = get_string('nostatusset', 'block_course_status');
        return $status;
    } else {
        // lookup status of course
        return $DB->get_record('block_course_status_states', array('id' => $currentstatusid));
    }
}

/**
 * Get the latest known status ID of a course
 *
 * @param mixed $courseorid course object or course ID
 * @return the last status id if there is one. null in other cases.
 */
function course_status_current_status_id($courseorid) {
    global $DB, $COURSE;

    if (is_null($courseorid)) {
        $courseorid = $COURSE;
    }

    if (!is_object($courseorid)) {
        $courseid = $courseorid;
    } else {
        $courseid = $courseorid->id;
    }

    $params = array('courseid' => $courseid);

    if ($latests = $DB->get_records('block_course_status_history', $params, 'timestamp DESC', 'id,timestamp', 0, 1)) {
        if ($latests) {
            $latest = array_pop($latests);
            $status = $DB->get_record('block_course_status_history', array('id' => $latest->id));
            return $status->approval_status_id;
        }
    }
    return null;
}