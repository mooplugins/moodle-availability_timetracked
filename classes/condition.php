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
 * Time tracked availability condition.
 *
 * @package    availability_timetracked
 * @author     BitKea Technologies LLP
 * @copyright  2026 BitKea Technologies LLP
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace availability_timetracked;

/**
 * Restrict access based on time tracked in another activity (local_timetracker).
 *
 * @package    availability_timetracked
 * @author     BitKea Technologies LLP
 * @copyright  2026 BitKea Technologies LLP
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class condition extends \core_availability\condition {
    /** @var int Course-module id that must be tracked. */
    protected $cmid;

    /** @var int Required tracked minutes (inclusive). */
    protected $minutes;

    /**
     * Constructor.
     *
     * @param \stdClass $structure Data structure from JSON decode
     * @throws \coding_exception If invalid data structure.
     */
    public function __construct($structure) {
        if (!isset($structure->v) || !is_number($structure->v) || (int) $structure->v < 0) {
            throw new \coding_exception('Missing or invalid ->v for timetracked condition');
        }
        $this->minutes = (int) $structure->v;

        if (!property_exists($structure, 'f') || !is_number($structure->f) || (int) $structure->f <= 0) {
            throw new \coding_exception('Missing or invalid ->f for timetracked condition');
        }
        $this->cmid = (int) $structure->f;
    }

    /**
     * Save condition back to a structure object.
     *
     * @return \stdClass Structure object
     */
    public function save() {
        return (object) [
            'type' => 'timetracked',
            'f' => $this->cmid,
            'v' => $this->minutes,
        ];
    }

    /**
     * Returns a JSON object which corresponds to a condition of this type.
     *
     * @param int $cmid Course-module id of the tracked activity
     * @param int $minutes Required tracked minutes
     * @return \stdClass Object representing condition
     */
    public static function get_json($cmid, $minutes) {
        return (object) [
            'type' => 'timetracked',
            'f' => (int) $cmid,
            'v' => (int) $minutes,
        ];
    }

    /**
     * Determines whether a particular item is currently available.
     *
     * @param bool $not Set true if we are inverting the condition
     * @param \core_availability\info $info Item we're checking
     * @param bool $grabthelot Performance hint
     * @param int $userid User ID to check availability for
     * @return bool True if available
     */
    public function is_available($not, \core_availability\info $info, $grabthelot, $userid) {
        global $CFG, $USER;

        require_once($CFG->dirroot . '/availability/condition/timetracked/locallib.php');

        $iscurrentuser = ((int) $USER->id === (int) $userid);
        if (isguestuser($userid) || ($iscurrentuser && !isloggedin())) {
            $uservalue = 0.0;
        } else {
            $course = $info->get_course();
            $uservalue = timetracked_get_user_time_in_module($userid, (int) $course->id, $this->cmid);
        }

        $allow = ($uservalue >= $this->minutes);
        if ($not) {
            $allow = !$allow;
        }

        return $allow;
    }

    /**
     * Obtains a string describing this condition.
     *
     * @param bool $full Set true if this is the 'full information' view
     * @param bool $not Set true if we are inverting the condition
     * @param \core_availability\info $info Item we're checking
     * @return string Description
     */
    public function get_description($full, $not, \core_availability\info $info) {
        $modinfo = $info->get_modinfo();
        $cms = $modinfo->get_cms();

        $a = new \stdClass();
        $a->value = $this->minutes;
        if (isset($cms[$this->cmid])) {
            $a->field = $cms[$this->cmid]->get_formatted_name();
        } else {
            $a->field = get_string('missingactivity', 'availability_timetracked');
        }

        $str = $not ? 'requires_not_isgreaterequalto' : 'requires_isgreaterequalto';
        return get_string($str, 'availability_timetracked', $a);
    }

    /**
     * Obtains a representation of the options of this condition as a string for debugging.
     *
     * @return string Text representation of parameters
     */
    protected function get_debug_string() {
        return $this->cmid . ' >= ' . $this->minutes . 'm';
    }

    /**
     * Updates after restore: remap referenced course-module id.
     *
     * @param string $restoreid Restore ID
     * @param int $courseid ID of target course
     * @param \base_logger $logger Logger for any warnings
     * @param string $name Name of this item (for use in warning messages)
     * @return bool True if there was any change
     */
    public function update_after_restore($restoreid, $courseid, \base_logger $logger, $name) {
        global $DB;

        $rec = \restore_dbops::get_backup_ids_record($restoreid, 'course_module', $this->cmid);
        if (!$rec || !$rec->newitemid) {
            if ($DB->record_exists('course_modules', ['id' => $this->cmid, 'course' => $courseid])) {
                return false;
            }
            $this->cmid = 0;
            $logger->process(
                'Restored item (' . $name . ') has availability condition on module that was not restored',
                \backup::LOG_WARNING
            );
            return true;
        }

        $this->cmid = (int) $rec->newitemid;
        return true;
    }
}
