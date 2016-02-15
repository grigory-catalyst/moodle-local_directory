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
 * Search class for directory local plugin
 * @package    local_directory
 * @author Grigory Baleevskiy (grigory@catalyst-au.net)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright  Catalyst
 */

/**
 * Class local_directory_search_options
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright  Catalyst
 */
class local_directory_search_options {
    /**
     * @var array
     */
    protected $_options;

    /**
     * local_directory_search_options constructor.
     * @param array $options
     */
    public function __construct(array $options) {
        $this->_options = array(
            'fieldssearch' => array(),
            'showperpage' => 10,
        );

        $this->_options = array_merge($this->_options, $options);
    }

    /**
     * option getter
     * @param string $name
     */
    public function __get($name) {
        return $this->_options[$name];
    }
}

/**
 * Class local_directory_search
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright  Catalyst
 */
class local_directory_search{
    /**
     * performs search
     * @param stdClass $formdata
     * @param local_directory_search_options $searchoptions
     * @return array
     * @throws coding_exception
     */
    public function search($formdata, local_directory_search_options $searchoptions) {
        global $DB;
        $term = $formdata->term;
        $searchfields = call_user_func_array(array($DB, 'sql_concat'), $searchoptions->fieldssearch);
        $condition = $DB->sql_like($searchfields, ':term', false, false);
        $params = array(
            'term' => "%".addcslashes($term, '%_')."%"
        );
        $requiredcondition = "";
        foreach ($searchoptions->fieldssearch as $requiredfield) {
            $requiredcondition .= " AND $requiredfield IS NOT NULL";
        }

        $showperpage = $searchoptions->showperpage;
        $offset = $formdata->page * $showperpage;

        $query = "SELECT usr.id , *
                  FROM {user} as usr
                  WHERE {$condition} {$requiredcondition} ";

        $countquery = "SELECT COUNT(1)
                       FROM {user} as usr
                       WHERE {$condition} {$requiredcondition} ";

        return array($DB->count_records_sql($countquery, $params), $DB->get_records_sql($query, $params, $offset, $showperpage));

    }

}