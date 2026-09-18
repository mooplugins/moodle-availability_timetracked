# Time tracked availability condition for Moodle

Restrict access to an activity or section until a learner has spent enough **tracked time** in another activity — using totals from the companion [Time Tracker](https://github.com/mooplugins/moodle-local_timetracker) plugin (`local_timetracker`).

## Why this plugin?

Teachers often want soft gates such as: *“Spend at least 10 minutes in the reading before the quiz unlocks.”* Moodle’s built-in completion and time-spent-in-course options do not measure engaged time on a specific activity. Together with Time Tracker, this condition does.

## Features

- Availability restriction: **Time tracked in** 〔activity〕 **is ≥** 〔minutes〕
- Works on activities and sections
- Reads live totals from Time Tracker (compacted report + open/finished logs)
- Remaps referenced course modules after backup/restore
- Stores no personal data of its own (Privacy API null provider)

## Requirements

- Moodle 4.5 or later (CI tested on 4.5, 5.0, and 5.2)
- [`local_timetracker`](https://github.com/mooplugins/moodle-local_timetracker) **1.4.4** or later (`2026091800+`)

## Installation

1. Install and enable **Time Tracker** (`local/timetracker`) first.
2. Copy this plugin into `availability/condition/timetracked`.
3. Visit **Site administration → Notifications** and complete the installation.
4. Enable Time Tracker on at least one activity in the course.
5. Edit another activity or section → **Restrict access** → **Add restriction…** → **Time tracked**.

## Privacy

This plugin does not store personal data. It only reads tracked-time totals from `local_timetracker` when evaluating availability.

## Changelog

See [CHANGES.md](CHANGES.md).

## License

GNU GPL v3 or later. See [LICENSE](LICENSE).

## Credits

Originally developed for [ScholarLMS](https://www.scholarlms.com/). Maintained by [MooPlugins](https://www.mooplugins.com/).
