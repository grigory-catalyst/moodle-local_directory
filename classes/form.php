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

/**
 * Class local_directory_form
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright  Catalyst
 */
class local_directory_form {

    /**
     * data getter
     * @return array
     * @throws coding_exception
     */
    public function getdata() {
        return array(
            'q' => optional_param('q', '', PARAM_RAW),
            'page' => optional_param('page', 0, PARAM_INT),
        );
    }

    /**
     * validates the form
     * @param array $data
     * @return array ($isvalid, $errors)
     * @throws coding_exception
     */
    public function validate($data) {
        $errors = array();
        $isvalid = true;
        if (isset($data['q']) and strlen($data['q']) < 2) {
            $isvalid = false;
            $errors[] = get_string('error_short_query', 'local_directory');
        };
        return array($isvalid, $errors);
    }

}
