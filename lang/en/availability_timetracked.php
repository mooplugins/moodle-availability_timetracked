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

/**
 * Language strings for availability_timetracked.
 *
 * @package    availability_timetracked
 * @copyright  2026 Mooplugins
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Restriction by time tracked';
$string['title'] = 'Time tracked';
$string['description'] = 'Require learners to spend a minimum amount of tracked time in another activity '
    . '(using the Time Tracker plugin, local_timetracker) before this item becomes available.';
$string['conditiontitle'] = 'Time tracked in';
$string['error_selectfield'] = 'You must select a course module.';
$string['error_setvalue'] = 'You must enter the number of minutes required.';
$string['label_operator'] = 'Method of comparison';
$string['label_value'] = 'Minutes required';
$string['label_minutes'] = 'minutes';
$string['missingactivity'] = '(missing activity)';
$string['op_isgreaterequalto'] = 'is ≥';
$string['requires_isgreaterequalto'] = 'Time tracked in <strong>{$a->field}</strong> is at least '
    . '<strong>{$a->value}</strong> minutes';
$string['requires_not_isgreaterequalto'] = 'Time tracked in <strong>{$a->field}</strong> is less than '
    . '<strong>{$a->value}</strong> minutes';
$string['privacy:metadata'] = 'The Restriction by time tracked plugin does not store any personal data. '
    . 'It reads tracked time from the Time Tracker (local_timetracker) plugin.';
