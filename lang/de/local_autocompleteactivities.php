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

$string['courses'] = 'Verfügbare Kurse';
$string['courses_description'] = 'Wählen sie alle Kurse aus, für die der automatische Aktivitätsabschluss aktiv sein soll';
$string['id'] = "ID";
$string['matching_prefix'] = "Übereinstimmendes Präfix";
$string['matching_prefix_description'] = "Anpassung des übereinstimmenden Präfixes der Abschnittsinhalte als regulärer Ausdruck, z.B. Übereinstimmung bis zur ersten offenen Klammer: /(.*)[(]/U";
$string['matching_label'] = "Direkte Übereinstimmungen";
$string['matching_label_description'] = "Eine Komma-separierte Liste von Wörtern oder (Teil-)Sätzen für Labels, die als abgeschlossen markiert werden sollen, sobald alle anderen Aktivitäten im selben Abschnitt abgeschlossen sind";
$string['matching_module_whitelist'] =  "Übereinstimmende Aktivitätsarten";
$string['matching_module_whitelist_description'] = "Eine Komma-separierte Liste von Aktivitätsarten die automatisch als abgeschlossen markiert werden dürfen";
