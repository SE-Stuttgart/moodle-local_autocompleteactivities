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
 * @package     mod_icecreamgame
 * @category    admin
 * @copyright   2022 Universtity of Stuttgart <dirk.vaeth@ims.uni-stuttgart.de>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Settings for the 'block_chatbot' component.
 */

defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {
    $ADMIN->add('localplugins', new admin_category('local_autocompleteactivities_settings', "Autocomplete Activities"));
    $settingspage = new admin_settingpage('managelocalautocompleteactivities', 'Activity Autocompletion Settings');
	if ($ADMIN->fulltree) {
		
		/*
		* Properties
		*/
		
		// course configuration: which courses should the plugin be active for
		$courses = $DB->get_records('course');
		$courselist = array();
		// get list of all courses and add those to the offered selection
		foreach($courses as $course) {  
			$courselist[$course->id] = $course->fullname;
		}
		$settingspage->add(new admin_setting_configmulticheckbox(
            'local_autocompleteactivities/courseids',
            get_string('courses', 'local_autocompleteactivities'),
            get_string('courses_description', 'local_autocompleteactivities'),
            null,
            $courselist));
		
		// prefix match pattern
		$settingspage->add(new admin_setting_configtext('local_autocompleteactivities/matchingprefix', get_string('matching_prefix', "local_autocompleteactivities"),
													get_string('matching_prefix_description', "local_autocompleteactivities"), '/(.*)[(]/U', PARAM_RAW));
													
		// Additional matching texts
		$settingspage->add(new admin_setting_configtext('local_autocompleteactivities/labelmatches', get_string('matching_label', "local_autocompleteactivities"),
													get_string('matching_label_description', "local_autocompleteactivities"), "kann ich schon", PARAM_TEXT));
													
		// whitelist of course module types 
		$settingspage->add(new admin_setting_configtext('local_autocompleteactivities/modulenames', get_string('matching_module_whitelist', "local_autocompleteactivities"),
													get_string('matching_module_whitelist_description', "local_autocompleteactivities"), "book,resource,label,url", PARAM_TEXT));

	}

    $ADMIN->add('localplugins', $settingspage);
}