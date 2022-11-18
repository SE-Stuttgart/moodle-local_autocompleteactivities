<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin administration pages are defined here.
 *
 * @package     local_autocompleteactivities
 * @category    events
 * @copyright   2022 Universtity of Stuttgart <dirk.vaeth@ims.uni-stuttgart.de>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_autocompleteactivities;
require_once("$CFG->libdir/externallib.php");
defined('MOODLE_INTERNAL') || die();


/**
 * Class description
 *
 * @author  University of Stuttgart <dirk.vaeth@ims.uni-stuttgart.de>
 * @license GPL3
*/
class observer {

    /**
     * Returns a course module's module type name.
     * 
     * Lookup in database: course module -> module -> name.
     * 
     * @param course_module $cm The course module
     * 
     * @return string The course module's module type name
     */
    public static function get_course_module_module_type_name($cm) {
        global $DB;
        return $DB->get_record('modules', array('id'=>$cm->module))->name;
    }
   
    /**
     * Returns a course module's name as displayed to the user in the corresponding course section.
     * 
     * This method contains a special case for `label` modules.
     * 
     * @param course_module $cm The course module
     * @param string $cm_type_name The course module's associated module type name (obtained e.g. via `observer::get_course_module_module_type_name`)
     * 
     * @return string The course module instance's name or the intro if the module type is `label`
     */
    public static function get_course_module_name($cm, $cm_type_name) {
        global $DB;
        $cm_type_specific_record = $DB->get_record($cm_type_name, array('id'=>$cm->instance));
        return $cm_type_name == 'label'? $cm_type_specific_record->intro : $cm_type_specific_record->name;  
    }

    /**
     * Checks if two strings share the same prefix as specified by a regular expression read from the configuration `local_autocompleteactivities_matchingprefix`.
     * 
     * The first group of the matches of both strings are used for the comparison.
     * All text is lowercased and trimmed for comparison. 
     * 
     * @param string $name First comparand
     * @param string $comparison_name Second comparand
     * 
     * @return bool True if the prefixes are equal according to the comparison rules.
     */
    public static function prefix_match($name, $comparison_name) {
        // returns true if name and comparison name share a comon prefix up to the start of a bracket sign, or are exactly the same, else false
        # Prepare strings for regex search and comparison
        $clean_name = strtolower($name);
        $clean_cmp = strtolower($comparison_name);

        # Find prefix matches
        $name_matches = [];
        preg_match(get_config("local_autocompleteactivities", "matchingprefix"),  $clean_name, $name_matches);
        $cmp_matches = [];
        preg_match(get_config("local_autocompleteactivities", "matchingprefix"),  $clean_cmp, $cmp_matches);

        # At least one matching group per string expected, otherwise one of them doesn't have a prefix group
        if(count($name_matches) < 2 || count($cmp_matches) < 2) {
            return false;
        }

        # Cleanup
        $clean_name = trim($name_matches[1]);
        $clean_cmp = trim($cmp_matches[1]);
      
        # Compare prefixes
        return $clean_name == $clean_cmp;
    }

    /**
     * Mark the specified course module completed (viewed) for the given user.
     * 
     * @param int $courseid The id of the course containing the course module to be marked as complete
     * @param int $userid The id of the user for whom the course module in the specified course should be marked as complete
     * @param course_module $coursemodule The coursemodule which should be marked as complete for the given user and course
     */
    public static function mark_complete($courseid, $userid, $coursemodule) {
        global $DB;
        // get course record and create new completion info for it
        $course = $DB->get_record("course", array("id"=>$courseid));
        $completion = new \completion_info($course);
        // set the completion state to viewed & complete 
        $completion->set_module_viewed($coursemodule, $userid);
        $completion->update_state($coursemodule, COMPLETION_COMPLETE, $userid);
    }

    /**
     * Gets the completion (viewed) state for a specified course module per user.
     * 
     * @param course_module $coursemodule The course module to be checked for the given user
     * @param int $userid The user's id
     * 
     * @return int The completionstate, if it exists - else `null`.
     */
    public static function get_completion_state($coursemodule, $userid) {
        global $DB; 
        if($DB->record_exists('course_modules_completion', array('coursemoduleid'=>$coursemodule->id, 'userid'=>$userid))) {
            return $DB->get_record('course_modules_completion', array('coursemoduleid'=>$coursemodule->id, 'userid'=>$userid))->completionstate;
        }
        // completionstate does not exist for given course module and user
        return null;
    }

    /**
     * Event handler for the `course_module_completion_updated` event.
     * 
     * First, we check if the new completion state is `completed`. If not, we return.
     * Each time this event is fired and the first check is successful, we loop over all course module instances in the same course section as the course module event source.
     * If the event source's name prefix-matches any of the whitelisted course module instance names in the same section, we mark these as completed, too.
     * 
     * @param (\core\event\base $event The event source
     */
    public static function course_module_completion_updated(\core\event\base $event) {
        global $DB;

        // get completionstate from event
        $data = $event->get_data();
        $userid = $data['relateduserid'];
        $contextinstanceid = $data['contextinstanceid'];
        $updated_completion_state = $data['other']['completionstate'];

        // check if the status change was to `completed`
        if($updated_completion_state != 1) { 
            // if not, return
            return;
        }

        // find course module associated with event
        $cm = $DB->get_record('course_modules', array('id'=>$contextinstanceid));
        
        // check if course associated with update is in whitelist specified in settings
        if(in_array($cm->course, explode(",", get_config('local_autocompleteactivities', "courseids"))) == false) {
            // if not, return
            return;
        }
        
        // get course module name, id and type
        $cm_type_name = observer::get_course_module_module_type_name($cm);
        $cm_name = observer::get_course_module_name($cm, $cm_type_name);
        $courseid = $data['courseid'];

        if(in_array($cm_type_name, explode(",", get_config('local_autocompleteactivities', "modulenames"))) == false) {
            // whitelist completion by module type (labels not included here: clicking e.g. "kann ich schon" should not mark other modules as completed, but the other way around)
            return;
        }

        // get all course modules in the same section as course module from event
        $section_cms = $DB->get_records("course_modules", array('section'=>$cm->section));
        
        $all_done = true; // variable for measuring if all course modules in the section are completed
        $cm_label_all_done = null;  // label instance for the `all done` item in the course section (if available)
       
        foreach($section_cms as $related_cm) { // iterate over all course modules in the same course section s.t. we don't have to search by name
            // get name and type of course module under inspection
            $related_cm_typename = observer::get_course_module_module_type_name($related_cm);
            $related_cm_name = observer::get_course_module_name($related_cm, $related_cm_typename);
            
            if($related_cm->id != $cm->id) { // don't re-trigger event for event source1
                if(in_array($cm_type_name, explode(",", get_config('local_autocompleteactivities', "modulenames")))) { // whitelist related course modules from same section by module type
                    if($related_cm_typename == "label" && $cm_label_all_done == null) {
                        foreach(explode(",", get_config('local_autocompleteactivities', "hardmatches")) as $hardmatch) {
                            // check if label matches a given string from the configuration
                            if(strpos($related_cm_name, $hardmatch) !== false) {
                                // found the `all done` label - save reference to it
                                $cm_label_all_done = $related_cm;
                                break;
                            }
                        }
                    }
                    elseif($related_cm_typename != "label" && observer::prefix_match($cm_name, $related_cm_name)) {
                        // Not a label - compare current course module under inspection to event source
                        // If the prefixes match, mark the course module under inspection as `completed`
                        observer::mark_complete($courseid, $userid, $related_cm);
                    }
                }
            }

            if($related_cm_typename != "label" && $related_cm_typename != 'page' && !observer::get_completion_state($related_cm, $userid)) { // labels and pages should not count as non-completed items
                $all_done = false; // we can't set `all done` label to "completed" since not all modules in this course section were completed
            }
        }

        // mark "all done" label as "completed" because all course modules in the section are completed
        if($all_done && !is_null($cm_label_all_done)) {
            observer::mark_complete($courseid, $userid, $cm_label_all_done);
        }
    }
}
