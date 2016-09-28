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
namespace block_course_status\controller;

defined('MOODLE_INTERNAL') || die();

/**
 * Master Controler class for Status.
 * this is a first step encapsulation.
 */
class status_controller {

    function process($action, $form = null, $environment = null) {
        global $DB, $OUTPUT;

        switch ($action) {
            /*************************************** adds a new status ***************************/
            case 'add':

                if (!$form->is_cancelled()) {
                    if ($data = $form->get_data()) {
                    // if there is some error
                        //data was submitted from this form, process it
                        $sortordermax = 0 + $DB->get_field('block_course_status_states', 'MAX(sortorder)', array());
    
                        $data->sortorder = $sortordermax + 1;
                        $DB->insert_record('block_course_status_states', $data);
                    } else {
                        echo $OUTPUT->header();
                        echo $OUTPUT->heading(get_string('coursestatusheading', 'block_course_status'));
                        $data = new \stdClass();
                        $data->what = 'add';
                        $form->set_data($data);
                        $form->display();
                        echo $OUTPUT->footer();
                        die;
                    }
                }
                break;

            /********************************** Updates a course state ***********************************/
            case 'update':
                // If a status is selected.
                $id = required_param('id', PARAM_INT);

                // Check the instance.
                if (!$status = $DB->get_record('block_course_status_states', array('id' => $id))) {
                    error('Status ID was incorrect');
                }

                // data was submitted from this form, process it
                if (!$form->is_cancelled()) {
                    if ($data = $form->get_data()) {
                        $DB->update_record('block_course_status_states', $data);
                    } else {
                        echo $OUTPUT->header();
                        echo $OUTPUT->heading(get_string('coursestatusheading', 'block_course_status'));
                        //no data submitted : print the form
                        $status->what = 'update';
                        $form->set_data($status);
                        $form->display();
                        echo $OUTPUT->footer();
                        die;
                    }
                }
                break;

        /*********************************** moves up ************************/
            case 'up':
                $id = required_param('id', PARAM_INT);
                status_tree_up($id);
                status_tree_updateordering(0);
                break;

        /*********************************** moves down ************************/
            case 'down':
                $id = required_param('id', PARAM_INT);
                status_tree_down($id);
                status_tree_updateordering(0);
                break;
        
        /*************************** Deletes a status in status list *************/
            case 'delete':
                $id = required_param('id', PARAM_INT);
                $DB->delete_records('block_course_status_states', array('id' => $id));
                break;
        }
    }
}

/**
 * updates ordering of a tree branch from a specific node, reordering 
 * all subsequent siblings. 
 * @param id the node from where to reorder
 */
function status_tree_updateordering($id){
    global $CFG, $DB;

    // getting ordering value of the current node

    $res =  $DB->get_record('block_course_status_states', array('id' => $id));
    if (!$res) { // fallback : we give the ordering
        $res->sortorder = $id;
    };

    // start reorder from the immediate lower (works from sortorder = 0)
    $prev = $res->sortorder - 1;

    // getting subsequent nodes
    $query = "
        SELECT 
            id
        FROM
            {block_course_status_states}
        WHERE
            sortorder > ?
        ORDER BY
            sortorder
    ";

    // reordering subsequent nodes using an object
    if ($nextsubs = $DB->get_records_sql($query, array($prev))) {
        $ordering = $res->sortorder;
        foreach ($nextsubs as $asub) {
            $object = new \StdClass();
            $object->id = $asub->id;
            $object->sortorder = $ordering;
            $DB->update_record('block_course_status_states', $object);
            $ordering++;
        }
    }
}

/**
 * raises a node in the tree, reordering all what needed
 * @param id the id of the raised node
 * @return void
 */
function status_tree_up($id) {
    global $CFG, $DB;

    $res = $DB->get_record('block_course_status_states', array('id' => $id));
    if (!$res) return;

    if($res->sortorder >= 1){
        $newordering = $res->sortorder - 1;

        $query = "
            SELECT 
                id
            FROM 
                {block_course_status_states}
            WHERE 
                sortorder = ?
        ";

        $result = $DB->get_record_sql($query, array($newordering));
        $resid = $result->id;

        // swapping
        $object = new \StdClass();
        $object->id = $resid;
        $object->sortorder = $res->sortorder;
        $DB->update_record('block_course_status_states', $object);

        $object = new \StdClass();
        $object->id = $id;
        $object->sortorder = $newordering;
        $DB->update_record('block_course_status_states', $object);
    }
}

/**
 * lowers a node on its branch. this is done by swapping ordering.
 * @param id the node id
 */
function status_tree_down($id){
    global $CFG, $DB;

    $res =  $DB->get_record('block_course_status_states', array('id' => $id));

    $query = "
        SELECT 
            MAX(sortorder) AS sortorder
        FROM 
            {block_course_status_states}
    ";

    $resmaxordering = $DB->get_record_sql($query);
    $maxordering = $resmaxordering->sortorder;

    if ($res->sortorder < $maxordering) {
        $newordering = $res->sortorder + 1;

        $query = "
            SELECT
                id
            FROM
                {block_course_status_states}
            WHERE
                sortorder = ?
        ";
        $result = $DB->get_record_sql($query, array($newordering));
        $resid = $result->id;

        // swapping
        $object = new \StdClass();
        $object->id = $resid;
        $object->sortorder = $res->sortorder;
        $DB->update_record('block_course_status_states', $objet);

        $object = new \StdClass();
        $object->id = $id;
        $object->sortorder = $newordering;
        $DB->update_record('block_course_status_states', $object);
    }
}
