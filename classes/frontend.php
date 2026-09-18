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
 * Front-end class for the time tracked availability condition.
 *
 * @package    availability_timetracked
 * @author     BitKea Technologies LLP
 * @copyright  2026 BitKea Technologies LLP
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace availability_timetracked;

/**
 * Front-end class.
 *
 * @package    availability_timetracked
 * @author     BitKea Technologies LLP
 * @copyright  2026 BitKea Technologies LLP
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class frontend extends \core_availability\frontend {
    /**
     * Strings used by the JavaScript form.
     *
     * @return string[]
     */
    protected function get_javascript_strings() {
        return [
            'op_isgreaterequalto',
            'conditiontitle',
            'label_operator',
            'label_value',
            'label_minutes',
        ];
    }

    /**
     * Parameters for the JavaScript init function.
     *
     * @param \stdClass $course
     * @param \cm_info|null $cm
     * @param \section_info|null $section
     * @return array
     */
    protected function get_javascript_init_params($course, ?\cm_info $cm = null, ?\section_info $section = null) {
        global $CFG;

        require_once($CFG->dirroot . '/availability/condition/timetracked/locallib.php');

        $skipcmid = $cm ? (int) $cm->id : 0;
        $modules = timetracked_get_trackers($course, $skipcmid);

        return [self::convert_associative_array_for_js($modules, 'field', 'display')];
    }

    /**
     * Whether the condition can be added for this course/item.
     *
     * @param \stdClass $course
     * @param \cm_info|null $cm
     * @param \section_info|null $section
     * @return bool
     */
    protected function allow_add($course, ?\cm_info $cm = null, ?\section_info $section = null) {
        if (!get_config('local_timetracker', 'version')) {
            return false;
        }
        if (get_config('local_timetracker', 'enabled') === '0') {
            return false;
        }

        $params = $this->get_javascript_init_params($course, $cm, $section);
        return !empty($params[0]);
    }
}
