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
 * Navigation for directory local plugin
 * @package    local_directory
 * @author Grigory Baleevskiy (grigory@catalyst-au.net)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright  Catalyst
 */

/**
 * Class local_directory_navigation
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright  Catalyst
 */
class local_directory_navigation implements renderable {
    /**
     * @var
     */
    protected $levels;

    /**
     * Takes a slice of grouping array
     * @param local_directory_search_options $searchoptions
     * @return array of levels for navigation
     */
    public function getlevels(local_directory_search_options $searchoptions) {
        if (is_null($this->levels)) {
            $this->levels = array_slice(
                array_filter(
                    array_map('trim',
                        $searchoptions->groupings
                    )
                ), 0, $searchoptions->navigation_levels);
        }
        return $this->levels;
    }

    /**
     * Group by condition, current navigation level
     * @param local_directory_search_options $searchoptions
     * @return string
     */
    public function getgroupby(local_directory_search_options $searchoptions) {
        foreach ($this->getlevels($searchoptions) as $level) {
            if (isset($searchoptions->request[$level])) {
                continue;
            } else {
                return $level;
            }
        }
        return '';
    }

    /**
     * Checks where we are. True if we currently seeing last level of navigation
     * @param local_directory_search_options $searchoptions
     * @return bool
     */
    public function isonlastlevel(local_directory_search_options $searchoptions) {
        $groups = array_values($searchoptions->groupings);
        return isset($searchoptions->request[end($groups)]);
    }

    /**
     * find a list of sublevels
     * @param local_directory_search_options $searchoptions
     * @return array
     */
    public function search(local_directory_search_options $searchoptions) {
        global $DB;

        $search = new local_directory_search();
        $groupbyfield = $this->getgroupby($searchoptions);
        $orderby = $this->get_navigation_order_expression($searchoptions);
        list($params, $condition) = $search->searchcondition($searchoptions);
        if (empty($groupbyfield)) {
            return array();
        }
        list($customselect, $customfrom, $customwhere) = $search->getcustomparts($searchoptions);
        $query = "SELECT MAX(primary_id), COUNT(*) count, {$groupbyfield}, '{$groupbyfield}' __field
                  FROM (
                        SELECT  usr.id as primary_id, usr.*,  {$customselect} FROM {user} as usr
                        {$customfrom}
                    ) as groups
                  WHERE {$condition} {$customwhere}
                  GROUP BY {$groupbyfield}
                  ORDER BY {$orderby}
         ";
        $res = $DB->get_records_sql($query, $params, 0, $searchoptions->navigation_max_children + 1);
        return $res;
    }

    /**
     * order expression getter
     * @param local_directory_search_options $searchoptions
     * @return string
     */
    public function get_navigation_order_expression(local_directory_search_options $searchoptions) {
        switch ($searchoptions->navigation_order) {
            case 'count':
                return 'count DESC';
            case 'alpha':
            default:
                return $this->getgroupby($searchoptions).' ASC';
        }
    }
}
