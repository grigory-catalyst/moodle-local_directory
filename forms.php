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
 * Forms for directory local plugin
 * @package    local_directory
 * @author Grigory Baleevskiy (grigory@catalyst-au.net)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright  Catalyst
 */

require_once("$CFG->libdir/formslib.php");

class local_directory_form extends moodleform {

    public function definition() {
        global $CFG;

        $mform = $this->_form;
        $mform->addElement('text', 'search_fields', get_string('search_fields')); // Add elements to your form
        $mform->setType('email', PARAM_NOTAGS);                   //Set type of element
        $mform->setDefault('email', 'Please enter email');        //Default value

        $select = $mform->addElement('select', 'colors', get_string('colors'), array('red', 'blue', 'green'));
        $select->setMultiple(true);
    }
    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}

class local_directory_search_form extends moodleform {

    public function definition() {
        global $CFG;

        $mform = $this->_form;
        $mform->addElement('text', 'q', get_string('search'));
        $mform->setType('q', PARAM_ALPHANUMEXT);
        $mform->setDefault('q', 'Search query');

    }

    function validation($data, $files) {
        $errors = parent::validation($data, $files);

        if(!isset($data['q']) or strlen($data['q']) < 3) {
            return array('q' => get_string('error_short_query'));
        }
        return array();
    }
}
