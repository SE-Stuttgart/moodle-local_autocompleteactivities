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
 * Plugin strings are defined here.
 *
 * @package     local_autocompleteactivities
 * @category    string
 * @copyright   2022 Universtity of Stuttgart <dirk.vaeth@ims.uni-stuttgart.de>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Autocomplete Activities';

$string['courses'] = 'Available Courses';
$string['courses_description'] = 'Select all the courses the autocompletion should be enabled for';
$string['id'] = "ID";
$string['matching_prefix'] = "Matching Prefix";
$string['matching_prefix_description'] = "Adapt the matching prefix of section content names as a regular expression, e.g. matching until the first opening bracket: /(.*)[(]/U";
$string['matching_label'] = "Matched label strings";
$string['matching_label_description'] = "Add a comma-separated list of the section label names that should be triggered by autocompletion when all other course modules in the same section are marked as completed";
$string['matching_module_whitelist'] = "Matched Module Types";
$string['matching_module_whitelist_description'] = "Add a comma-separated list of the module types that should be allowed to be autocompleted";
