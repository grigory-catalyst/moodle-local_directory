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
            'groupings' => array(),
            'q' => '',
            'page' => 0,
            'request' => array(),
            'navigation_levels' => 2,
        );

        $this->_options = array_merge($this->_options, $options);
    }

    /**
     * option array getter
     * @return array
     */
    public function getoptions() {
        return $this->_options;
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
     * @param local_directory_search_options $searchoptions
     * @return array
     * @throws coding_exception
     */
    public function search(local_directory_search_options $searchoptions) {
        global $DB;
        list($params, $condition) = $this->searchcondition($searchoptions);
        $showperpage = $searchoptions->showperpage;
        $offset = $searchoptions->page * $showperpage;
        $orderexpression = $this->getorderexpression($searchoptions);
        $query = "SELECT usr.id , *
                  FROM {user} as usr
                  WHERE {$condition}
                  ORDER BY {$orderexpression}";
        $countquery = "SELECT COUNT(1)
                       FROM {user} as usr
                       WHERE {$condition} ";
        return array($DB->count_records_sql($countquery, $params), $DB->get_records_sql($query, $params, $offset, $showperpage));
    }

    /**
     * Takes parameters from request based on groupings and number of levels
     * @param local_directory_search_options $searchoptions
     * @return array
     */
    public function getnavigationfilter(local_directory_search_options $searchoptions) {
        return array_slice(
            array_intersect_key($searchoptions->request, array_flip($searchoptions->groupings)),
            0, $searchoptions->navigation_levels
        );
    }

    /**
     * generates the condition for search
     * @param local_directory_search_options $searchoptions
     * @return string
     */
    public function searchcondition(local_directory_search_options $searchoptions) {
        global $DB;
        $term = $searchoptions->q;
        $searchfields = call_user_func_array(array($DB, 'sql_concat'), $searchoptions->fieldssearch);
        $condition = $DB->sql_like($searchfields, ':q', false, false);
        $navigationfilterparams = $this->getnavigationfilter($searchoptions);
        foreach ($navigationfilterparams as $key => $value) {
            $condition .= sprintf(' AND %s = :%s', $key, $key);
        }
        $params = array_merge(
            array('q' => "%".addcslashes($term, '%_')."%"),
            $navigationfilterparams
        );
        $requiredcondition = "";
        foreach ($searchoptions->fieldssearch as $requiredfield) {
            $requiredcondition .= " AND $requiredfield IS NOT NULL";
        }
        return array($params, $condition.' '.$requiredcondition);
    }

    /**
     * combines two things: sorting for grouping and column sorting
     * @param local_directory_search_options $searchoptions
     * @return string
     */
    public function getorderexpression(local_directory_search_options $searchoptions) {
        if (count($groupings = $searchoptions->groupings)) {
            $result = $groupings;
        } else {
            $result = array("usr.id");
        }
        // TODO: add column sorting!
        return implode(",", $result);
    }

}