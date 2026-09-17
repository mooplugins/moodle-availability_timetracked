/**
 * JavaScript for form editing timetracked conditions.
 *
 * @module moodle-availability_timetracked-form
 */
M.availability_timetracked = M.availability_timetracked || {};

/**
 * @class M.availability_timetracked.form
 * @extends M.core_availability.plugin
 */
M.availability_timetracked.form = Y.Object(M.core_availability.plugin);

/**
 * Course modules available for selection.
 *
 * @property coursemodules
 * @type Array
 */
M.availability_timetracked.form.coursemodules = null;

/**
 * Initialises this plugin.
 *
 * @method initInner
 * @param {Array} coursemodules Array of objects with .field, .display
 */
M.availability_timetracked.form.initInner = function(coursemodules) {
    this.coursemodules = coursemodules || [];
};

/**
 * Gets a node representing this plugin.
 *
 * @method getNode
 * @param {Object} json
 * @return {Y.Node}
 */
M.availability_timetracked.form.getNode = function(json) {
    var html = '<span class="availability-group"><label>' +
            M.util.get_string('conditiontitle', 'availability_timetracked') + ' ' +
            '<select name="field">' +
            '<option value="0">' + M.util.get_string('choosedots', 'moodle') + '</option>';
    var fieldInfo;
    var i;
    for (i = 0; i < this.coursemodules.length; i++) {
        fieldInfo = this.coursemodules[i];
        html += '<option value="' + fieldInfo.field + '">' + fieldInfo.display + '</option>';
    }

    html += '</select></label> <label><span class="accesshide">' +
            M.util.get_string('label_operator', 'availability_timetracked') +
            ' </span>' + M.util.get_string('op_isgreaterequalto', 'availability_timetracked') +
            '</label> <label><span class="accesshide">' +
            M.util.get_string('label_value', 'availability_timetracked') +
            '</span><input name="value" type="text" value="0" style="width: 10em" title="' +
            M.util.get_string('label_value', 'availability_timetracked') + '"/> ' +
            M.util.get_string('label_minutes', 'availability_timetracked') + '</label></span>';
    var node = Y.Node.create('<span>' + html + '</span>');

    if (json.f !== undefined &&
            node.one('select[name=field] > option[value=' + json.f + ']')) {
        node.one('select[name=field]').set('value', '' + json.f);
    }
    if (json.v !== undefined) {
        node.one('input').set('value', '' + json.v);
    }

    if (!M.availability_timetracked.form.addedEvents) {
        M.availability_timetracked.form.addedEvents = true;
        var root = Y.one('.availability-field');
        root.delegate('change', function() {
            M.core_availability.form.update();
        }, '.availability_timetracked select');
        root.delegate('change', function() {
            M.core_availability.form.update();
        }, '.availability_timetracked input[name=value]');
    }

    return node;
};

/**
 * Fills in the value from the form node.
 *
 * @method fillValue
 * @param {Object} value
 * @param {Y.Node} node
 */
M.availability_timetracked.form.fillValue = function(value, node) {
    value.f = parseInt(node.one('select[name=field]').get('value'), 10);
    value.v = parseInt(node.one('input[name=value]').get('value'), 10);
};

/**
 * Fills in errors from the form node.
 *
 * @method fillErrors
 * @param {Array} errors
 * @param {Y.Node} node
 */
M.availability_timetracked.form.fillErrors = function(errors, node) {
    var cmid = parseInt(node.one('select[name=field]').get('value'), 10);
    var timevalue = parseInt(node.one('input[name=value]').get('value'), 10);

    if (!cmid) {
        errors.push('availability_timetracked:error_selectfield');
    }
    if (isNaN(timevalue) || timevalue < 0) {
        errors.push('availability_timetracked:error_setvalue');
    }
};
