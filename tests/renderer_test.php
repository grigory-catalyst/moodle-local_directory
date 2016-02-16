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
 * Renderer test for directory local plugin
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
class local_directory_renderer_testcase extends advanced_testcase {

    public function test_term_replaced_with_mark() {
        $renderermock = $this->getMockBuilder('local_directory_renderer')->disableOriginalConstructor();
        $renderermock = $renderermock->setMethods(array('getconfig'))->getMock();
        $renderermock->method('getconfig')->willReturn(array('firstname', 'lastname'));
        $list = new directory_user_list();
        $list->list[] = new directory_user((object) array(
            'firstname' => 'test',
            'lastname' => 'test',
        ), array(
            'term' => 'es'
        ));

        $list->setoptions(array(
                'total' => 1,
                'found' => 1,
                'perpage' => 10,
                'page' => 20,
                'groupings' => array(),
            )
        );

        $out = $renderermock->render($list);
        $this->assertContains('t<mark>es</mark>t', $out);
    }
}
