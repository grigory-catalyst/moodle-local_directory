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
 * Locallib for directory local plugin
 * @package    local_directory
 * @author Grigory Baleevskiy (grigory@catalyst-au.net)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright  Catalyst
 */


function local_directory_search($formdata) {
    global $DB;
    $term = $formdata->term;
    $configfieldssearch =explode(',', get_config('local_directory', 'fields_search'));
    $searchfields = call_user_func_array(array($DB, 'sql_concat'), $configfieldssearch);
    $condition = $DB->sql_like($searchfields, ':term', false, false);
    $params = array(
        'term' => "%$term%"
    );

    $query = "SELECT usr.id, ".get_config('local_directory', 'fields_display')."
              FROM {user} as usr
              WHERE {$condition}
    ";
    return $DB->get_records_sql($query, $params, 0, 10);

}

function local_directory_render_user($user) {
    $configfieldsdisplay = explode(',', get_config('local_directory', 'fields_display'));
    echo html_writer::start_div('directory');
    foreach($configfieldsdisplay as $field) {
        echo html_writer::div($field.' : '.$user->$field);
    }
    echo html_writer::end_div();
}
