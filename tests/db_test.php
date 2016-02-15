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
 * Base test for directory local plugin
 * @package    local_directory
 * @author Grigory Baleevskiy (grigory@catalyst-au.net)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright  Catalyst
 */

require(dirname(__FILE__).'/../renderer.php');

/**
 * Class local_directory_base_test
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright Catalyst
 */
class local_directory_db_testcase extends advanced_testcase {

    public function setUp() {
        for ($x = 0; $x < 20; $x++) {
            $this->getDataGenerator()->create_user(
                array(
                    'email' => sprintf('useremail%d@example.com', $x + 1),
                    'username' => sprintf('_username%d', $x)));
        }

        parent::setUp();
    }

    public function test_got_twenty_username_and_ten_records() {
        $this->resetAfterTest(true);
        list($count , $users) = $this->search_users('username', array('username'));
        $this->assertEquals(20, $count);
        $this->assertCount(10, $users);
    }

    public function test_found_twenty_on_underscore() {
        $this->resetAfterTest(true);
        list($count , $users) = $this->search_users('_', array('username'));
        $this->assertEquals(20, $count);
    }

    public function test_zero_found_by_email() {
        $this->resetAfterTest(true);
        list($count , $users) = $this->search_users('username', array('email'));
        $this->assertEquals(0, $count);
    }

    public function test_found_on_sq() {
        $this->resetAfterTest(true);
        list($count , $users) = $this->search_users('username1', array('username'));
        $this->assertEquals(11, $count);
    }

    public function test_search_two_fields() {
        $this->resetAfterTest(true);
        list($count , $users) = $this->search_users('15', array('username', 'email'));
        $this->assertEquals(2, $count);
    }

    /**
     * simple search functions
     * @param string $term
     * @param array $fields
     * @return array
     */
    public function search_users($term, $fields) {
        $formdata = (object) array('term' => $term, 'page' => 0);
        $searchhandler = new local_directory_search();
        $searchoptions = new local_directory_search_options(array(
            'fieldssearch' => $fields,
            'showperpage' => 10,
        ));
        return $searchhandler->search($formdata, $searchoptions);
    }
}
