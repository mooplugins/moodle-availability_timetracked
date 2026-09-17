# Changelog

All notable changes to the Time tracked availability condition are documented here.

## 1.1.0 - 2026-09-08

### Added

- Privacy API null provider.
- `LICENSE`, `CHANGES.md`, README, `thirdpartylibs.xml`, `.gitignore`, and Moodle Plugin CI.
- `update_after_restore()` to remap referenced course-module ids.
- `allow_add()` so the restriction is offered only when Time Tracker is available and enabled trackers exist.
- Negated condition description string.

### Changed

- Requires Moodle 4.5+ (`supported` 4.5–5.2).
- Depends on `local_timetracker` ≥ `2026090805` (1.4.2) for correct totals.
- Section-safe frontend (`?\cm_info`, skip current CM safely).
- Safer JSON parsing and missing-activity handling in descriptions.

### Fixed

- Undeclared variables in the YUI form error handler.

## 1.0.2 - 2019-02-12

- Earlier ScholarLMS release.
