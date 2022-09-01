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
    $settingspage = new admin_settingpage('managelocalautocompleteactivities', 'Manage Activity Autocompletion Settings');
	if ($ADMIN->fulltree) {
		
		/*
		* Properties
		*/
		
		// get list of all courses and add those to the description
		$courses = $DB->get_records('course');
		$courselist = "<br><b>Selection: </b><br>";
		foreach($courses as $course) { 
			$courselist = $courselist . "Kurs: " . $course->fullname . ", ID: <b>" . $course->id . "</b><br>";
		}
		$settingspage->add(new admin_setting_configtext('local_autocompleteactivities_coursenames', "Course IDs", 
													"Add a comma-seperated list of course ids where the plugin should be active here:" . $courselist, "", PARAM_TEXT));
	}

    $ADMIN->add('localplugins', $settingspage);
}