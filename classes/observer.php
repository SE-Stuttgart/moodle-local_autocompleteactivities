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
 * @copyright   2022 Universtity of Stuttgart <dirk.vaeth@ims.uni-stuttgart.de>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_autocompleteactivities;
require_once("$CFG->libdir/externallib.php");

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
        return $DB->get_record('modules', array('id' => $cm->module))->name;
    }

    /**
     * Returns a course module's name as displayed to the user in the corresponding course section.
     *
     * This method contains a special case for `label` modules.
     *
     * @param course_module $cm The course module
     * @param string $cmtypename Course module's module type name (obtained e.g. via `observer::get_course_module_module_type_name`)
     *
     * @return string The course module instance's name or the intro if the module type is `label`
     */
    public static function get_course_module_name($cm, $cmtypename) {
        global $DB;
        $cmtypespecificrecord = $DB->get_record($cmtypename, array('id' => $cm->instance));
        return $cmtypename == 'label' ? $cmtypespecificrecord->intro : $cmtypespecificrecord->name;
    }

    /**
     * Checks if two strings share the same prefix as specified by a regular expression read from the configuration:
     * `local_autocompleteactivities_matchingprefix`.
     *
     * The first group of the matches of both strings are used for the comparison.
     * All text is lowercased and trimmed for comparison.
     *
     * @param string $name First comparand
     * @param string $comparisonname Second comparand
     *
     * @return bool True if the prefixes are equal according to the comparison rules.
     */
    public static function prefix_match($name, $comparisonname) {
        // Returns true if name and comparison name share a comon prefix up to the start of a bracket sign,
        // or are exactly the same, else false.
        // Prepare strings for regex search and comparison.
        $cleanname = strtolower($name);
        $cleancomparison = strtolower($comparisonname);

        // Find prefix matches.
        $namematches = [];
        preg_match(get_config("local_autocompleteactivities", "matchingprefix"),  $cleanname, $namematches);
        $comparisonmatches = [];
        preg_match(get_config("local_autocompleteactivities", "matchingprefix"),  $cleancomparison, $comparisonmatches);

        // At least one matching group per string expected, otherwise one of them doesn't have a prefix group.
        if (count($namematches) < 2 || count($comparisonmatches) < 2) {
            return false;
        }

        // Cleanup.
        $cleanname = trim($namematches[1]);
        $cleancomparison = trim($comparisonmatches[1]);

        // Compare prefixes.
        return $cleanname == $cleancomparison;
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
        // Get course record and create new completion info for it.
        $course = $DB->get_record("course", array("id" => $courseid));
        $completion = new \completion_info($course);
        // Set the completion state to viewed & complete.
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
        if ($DB->record_exists('course_modules_completion', array('coursemoduleid' => $coursemodule->id, 'userid' => $userid))) {
            return $DB->get_record('course_modules_completion',
                                    array('coursemoduleid' => $coursemodule->id, 'userid' => $userid)
                                )->completionstate;
        }
        // Completionstate does not exist for given course module and user.
        return null;
    }

    /**
     * Event handler for the `course_module_completion_updated` event.
     *
     * First, we check if the new completion state is `completed`. If not, we return.
     * Each time this event is fired and the first check is successful,
     * we loop over all course module instances in the same course section as the course module event source.
     * If the event source's name prefix-matches any of the whitelisted course module instance names in the same section,
     * we mark these as completed, too.
     *
     * @param (\core\event\base $event The event source
     */
    public static function course_module_completion_updated(\core\event\base $event) {
        global $DB;

        // Get completionstate from event.
        $data = $event->get_data();
        $userid = $data['relateduserid'];
        $contextinstanceid = $data['contextinstanceid'];
        $updatedcompletionstate = $data['other']['completionstate'];

        // Check if the status change was to `completed`.
        if ($updatedcompletionstate != 1) {
            // If not completed, then return.
            return;
        }

        // Find course module associated with event.
        $cm = $DB->get_record('course_modules', array('id' => $contextinstanceid));

        // Check if course associated with update is in whitelist specified in settings.
        if (in_array($cm->course, explode(",", get_config('local_autocompleteactivities', "courseids"))) == false) {
            // If not in whitelist, then return.
            return;
        }

        // Get course module name, id and type.
        $cmtypename = self::get_course_module_module_type_name($cm);
        $cmname = self::get_course_module_name($cm, $cmtypename);
        $courseid = $data['courseid'];

        if (in_array($cmtypename, explode(",", get_config('local_autocompleteactivities', "modulenames"))) == false) {
            /* Whitelist completion by module type
            * (labels not included here:
            *   clicking e.g. "kann ich schon" should not mark other modules as completed, but the other way around
            * ).
            */
            return;
        }

        // Get all course modules in the same section as course module from event.
        $sectioncoursemodules = $DB->get_records("course_modules", array('section' => $cm->section));

        $alldone = true; // Variable for measuring if all course modules in the section are completed.
        $cmlabelalldone = null;  // Label instance for the `all done` item in the course section (if available).

        // Iterate over all course modules in the same course section s.t. we don't have to search by name.
        foreach ($sectioncoursemodules as $relatedcm) {
            // Get name and type of course module under inspection.
            $relatedcmtypename = self::get_course_module_module_type_name($relatedcm);
            $relatedcmname = self::get_course_module_name($relatedcm, $relatedcmtypename);

            if ($relatedcm->id != $cm->id) { // Don't re-trigger event for event source.
                if (in_array($cmtypename, explode(",", get_config('local_autocompleteactivities', "modulenames")))) {
                    // Whitelist related course modules from same section by module type.
                    if ($relatedcmtypename == "label" && $cmlabelalldone == null) {
                        foreach (explode(",", get_config('local_autocompleteactivities', "hardmatches")) as $hardmatch) {
                            // Check if label matches a given string from the configuration.
                            if (strpos($relatedcmname, $hardmatch) !== false) {
                                // Found the `all done` label - save reference to it.
                                $cmlabelalldone = $relatedcm;
                                break;
                            }
                        }
                    } else if ($relatedcmtypename != "label" && self::prefix_match($cmname, $relatedcmname)) {
                        // Not a label - compare current course module under inspection to event source.
                        // If the prefixes match, mark the course module under inspection as `completed`.
                        self::mark_complete($courseid, $userid, $relatedcm);
                    }
                }
            }

            // Labels and pages should not count as non-completed items.
            if ($relatedcmtypename != "label" && $relatedcmtypename != 'page' && !self::get_completion_state($relatedcm, $userid)) {
                // We can't set `all done` label to "completed" since not all modules in this course section were completed.
                $alldone = false;
            }
        }

        // Mark "all done" label as "completed" because all course modules in the section are completed.
        if ($alldone && !is_null($cmlabelalldone)) {
            self::mark_complete($courseid, $userid, $cmlabelalldone);
        }
    }
}
