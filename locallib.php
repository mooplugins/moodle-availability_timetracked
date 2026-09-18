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
 * Local library functions for availability_timetracked.
 *
 * @package    availability_timetracked
 * @author     BitKea Technologies LLP
 * @copyright  2026 BitKea Technologies LLP
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../../../local/timetracker/locallib.php');

/**
 * Enabled Time Tracker activities in a course (for the condition UI).
 *
 * @param \stdClass $course
 * @param int $skipcurrent Course module id to skip (activity being edited).
 * @return array Map of coursemodule id => formatted name
 */
function timetracked_get_trackers($course, $skipcurrent) {
    global $DB;

    $trackers = $DB->get_records('local_timetracker', [
        'course' => $course->id,
        'enabled' => 1,
    ]);
    if (!$trackers) {
        return [];
    }

    $modinfo = get_fast_modinfo($course);
    $cms = $modinfo->get_cms();
    $context = \context_course::instance($course->id);
    $modules = [];

    foreach ($trackers as $tracker) {
        $cmid = (int) $tracker->coursemodule;
        if ($cmid === (int) $skipcurrent) {
            continue;
        }
        if (!isset($cms[$cmid]) || $cms[$cmid]->deletioninprogress) {
            continue;
        }
        $modules[$cmid] = format_string($cms[$cmid]->name, true, ['context' => $context]);
    }

    return $modules;
}

/**
 * Tracked time in minutes for a user on a course module.
 *
 * Uses local_timetracker totals (compacted report + uncompacted logs).
 *
 * @param int $userid
 * @param int $courseid
 * @param int $coursemodule
 * @return float
 */
function timetracked_get_user_time_in_module($userid, $courseid, $coursemodule) {
    $seconds = local_timetracker_get_timespent((int) $userid, null, (int) $courseid, (int) $coursemodule);
    return round($seconds / 60, 2);
}
