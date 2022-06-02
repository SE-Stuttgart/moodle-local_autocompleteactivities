<?php

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