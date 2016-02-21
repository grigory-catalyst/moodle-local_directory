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
 * Navigation test for directory local plugin
 * @package    local_directory
 * @author Grigory Baleevskiy (grigory@catalyst-au.net)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright  Catalyst
 */

require(dirname(__FILE__).'/../renderer.php');
require(dirname(__FILE__).'/../classes/search.php');
/**
 * Class local_directory_base_test
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright Catalyst
 */
class local_directory_navigation_testcase extends advanced_testcase {
    /**
     * options factory
     * @param array $formdata
     * @return local_directory_search_options
     */
    public function search_options($formdata = array()) {
        return new local_directory_search_options(
            array_merge(
                array(
                    'fieldssearch' => array('firstname', 'lastname'),
                    'showperpage' => 20,
                    'groupings' => array(),
                    'navigation_levels' => 3,
                    'navigation_max_children' => 10,
                    'request' => array(),
                ),
                $formdata)
        );
    }

    public function test_getlevels_count() {
        $nav = new local_directory_navigation();
        $options = $this->search_options(array(
            'groupings' => array('inst', 'dep', 'test', 'foo', 'bar'),
            'navigation_levels' => 3,
        ));

        $this->assertEquals(array('inst', 'dep', 'test'), $nav->getlevels($options));
    }

    public function test_getlevels_count_empty() {
        $nav = new local_directory_navigation();
        $options = $this->search_options(array(
            'groupings' => array('inst', 'dep', ' ', '', ''),
            'navigation_levels' => 3,
        ));

        $this->assertCount(2, $nav->getlevels($options));
    }

    public function test_getgroupby_empty_request() {
        $nav = new local_directory_navigation();
        $options = $this->search_options(array(
            'groupings' => array('inst', 'dep', 'foo', 'bar', ''),
            'navigation_levels' => 5,
        ));
        $this->assertEquals('inst', $nav->getgroupby($options));
    }

    public function test_getgroupby_nonempty_request() {
        $nav = new local_directory_navigation();
        $options = $this->search_options(array(
            'groupings' => array('inst', 'dep', 'foo', 'bar', ''),
            'navigation_levels' => 5,
            'request' => array('inst' => 'inst', 'dep' => 'dep')
        ));
        $this->assertEquals('foo', $nav->getgroupby($options));
    }

}

