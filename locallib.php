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
        'term' => "%".addcslashes($term, '%_')."%"
    );
    $requiredcondition = "";
    foreach(explode(',', get_config('local_directory', 'fields_search')) as $requiredfield) {
        $requiredcondition .= " AND $requiredfield IS NOT NULL";
    }

    $showperpage = get_config('local_directory', 'show_per_page');
    $offset = $formdata->page * $showperpage;

    $query = "SELECT usr.id , *
              FROM {user} as usr
              WHERE {$condition} {$requiredcondition}

    ";

    return $DB->get_records_sql($query, $params, $offset, $showperpage);
}