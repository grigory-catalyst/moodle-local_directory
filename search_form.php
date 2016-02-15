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

/**
 * Class local_directory_search_form
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright Catalyst
 */
class local_directory_search_form extends moodleform {

    /**
     * defines the form
     * @throws coding_exception
     */
    public function definition() {
        $mform = $this->_form;
        $el = $mform->addElement('text', 'term', get_string('search'));
        $mform->setType('term', PARAM_RAW);
        $el->updateAttributes(array('autofocus' => true));
        $this->add_action_buttons(false, get_string('button_search', 'local_directory'));
    }

    /**
     * performs validation
     * @param array $data
     * @param array $files
     * @return array
     * @throws coding_exception
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        if (!isset($data['term']) or strlen($data['term']) < 2) {
            $errors['term'] = get_string('error_short_query', 'local_directory');
        }
        return $errors;
    }
}
