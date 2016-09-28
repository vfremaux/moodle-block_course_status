<?php

class block_course_status_renderer extends plugin_renderer_base {

    function tabs($currenttab) {
        global $OUTPUT;

        $stateadminlink = new moodle_url('/blocks/course_status/admin_status.php');
        $row[] = new tabobject('states', $stateadminlink, get_string('managestates', 'block_course_status'));
        $roleadminlink = new moodle_url('/blocks/course_status/admin_status_roles.php');
        $row[] = new tabobject('roles', $roleadminlink, get_string('manageroles', 'block_course_status'));

        return $OUTPUT->tabtree($row, $currenttab);
    }

}