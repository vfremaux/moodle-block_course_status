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
 * @package   block_course_status
 * @copyright 2015 Valery Fremaux
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$string['course_status:addinstance'] = 'Add on instance of Course Status';
$string['course_status:iseditor'] = 'Is course editor';
$string['course_status:isreviewer'] = 'Is course content reviewer';
$string['course_status:updatecoursestatus'] = 'Update course status';
$string['course_status:viewcoursestatus'] = 'View course status';

// Privacy
$string['privacy:metadata'] = 'The Course Status block does not directly store any personal data about any user.';

$string['actualstate'] = 'Actual state: {$a}';
$string['addstatus'] = 'Add status';
$string['addstatusform'] = 'Add a course status value';
$string['adminstates'] = 'Administrate course production flow states';
$string['allstates'] = 'All publishing states';
$string['backtocourse'] = 'Back to course';
$string['pluginname'] = 'Course Approval Status';
$string['coursestatus'] = 'Course Approval Status';
$string['change'] = 'Change status';
$string['changestatusto'] = 'Change status to';
$string['coursecategory'] = 'Target course category';
$string['courseeditor'] = 'Course Editor';
$string['courseeditordesc'] = 'Course Editor can drive all editing states of a course, overrideing local role state permissions';
$string['coursereviewer'] = 'Course Reviewer';
$string['coursereviewerdesc'] = 'the course reviewer can see the cours as a teacher, and can switch some production status in the course';
$string['coursestatus'] = 'Course Approval Status';
$string['coursestatusheading'] = 'Course Production Workflow/Publication Status';
$string['coursestatusoptions'] = 'Course status';
$string['coursestatusoptionsdesc'] = 'Settings for course status : <a href="{$a}">Browse to admin screen</a>';
$string['coursestatusroleoptionsdesc'] = 'Attributing states to roles : <a href="{$a}">Browse to role map</a>';
$string['coursestatusrolesheading'] = 'Course Status to Role Mapping';
$string['description'] = 'Description';
$string['displayname'] = 'Display name';
$string['donotmove'] = 'Leave in same category';
$string['errorupdatestatus'] = 'Could not update status';
$string['history'] = 'History';
$string['historyheading'] = 'History of changes';
$string['landingcategory'] = 'Landing course category';
$string['manageroles'] = 'States control roles';
$string['managestates'] = 'Workflow states';
$string['missingstatusreason'] = 'You need tell some reason of the change';
$string['nohistory'] = 'No history data available';
$string['nostatusset'] = 'Initial state. The production workflow has not started yet.';
$string['nostatussetshort'] = 'Initial state.';
$string['pagedescription'] = 'Course Approval Status';
$string['reason'] = 'Reason';
$string['shortname'] = 'Shortname';
$string['statuschangeheading'] = 'Course Workflow Status Change';
$string['statushistory'] = 'Status history';
$string['statusunchanged'] = 'Status was unchanged<br/><br/>';
$string['statusupdated'] = 'Status has been updated<br/><br/>';
$string['updatestatusform'] = 'Change course status';
$string['roles'] = 'Roles';
$string['status'] = 'State';

// status values
$string['approved'] = 'Approved';
$string['approved_desc'] = 'Approved by the content editor';
$string['needschange'] = 'Needs change';
$string['needschange_desc'] = 'Some changes are required before publication';
$string['notsubmitted'] = 'Not submitted (working)';
$string['notsubmitted_desc'] = 'Working state';
$string['published'] = 'Published';
$string['published_desc'] = 'Published';
$string['resubmitted'] = 'Submitted again after review';
$string['resubmitted_desc'] = 'Submitted again';
$string['submitted'] = 'Submitted';
$string['submitted_desc'] = 'Submitted (first review requirement)';
$string['suspendedauthor'] = 'Suspended by author';
$string['suspendedauthor_desc'] = 'Suspended on author behalf';
$string['suspendeddate'] = 'Suspended';
$string['suspendeddate_desc'] = 'Suspended because obsolete';

$string['errornooptions'] = "Missing course status options definitions";