<?php
//
// Capability definitions for the block vmoodle.
//
// The capabilities are loaded into the database table when the module is
// installed or updated. Whenever the capability definitions are updated,
// the module version number should be bumped up.
//
// The system has four possible values for a capability:
// CAP_ALLOW, CAP_PREVENT, CAP_PROHIBIT, and inherit (not set).
//
//
// CAPABILITY NAMING CONVENTION
//
// It is important that capability names are unique. The naming convention
// for capabilities that are specific to modules and blocks is as follows:
//   [mod/block]/<component_name>:<capabilityname>
//
// component_name should be the same as the directory name of the mod or block.
//
// Core moodle capabilities are defined thus:
//    moodle/<capabilityclass>:<capabilityname>
//
// Examples: mod/forum:viewpost
//           block/recent_activity:view
//           moodle/site:deleteuser
//
// The variable name for the capability definitions array follows the format
//   $<componenttype>_<component_name>_capabilities
//
// For the core capabilities, the variable is $moodle_capabilities.

$capabilities = array(

    'block/course_status:addinstance' => array(

        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetype' => array(
            'manager' => CAP_ALLOW,
            'coursecreator' => CAP_ALLOW
        )
    ),

    'block/course_status:viewcoursestatus' => array(
        'captype'      => 'read',
        'contextlevel' => CONTEXT_COURSE,
        'archetype' => array(
            'manager' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
        )
    ),

    'block/course_status:updatecoursestatus' => array(
        'captype'      => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'archetype' => array(
            'manager' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW
        )
    ),

    // A capability for those who have REVIEWER role
    'block/course_status:isreviewer' => array(
        'captype'      => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'archetype' => array(
        )
    ),

    // A capability for those who have EDITOR role
    'block/course_status:iseditor' => array(
        'captype'      => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'archetype' => array(
        )
    ),

);
