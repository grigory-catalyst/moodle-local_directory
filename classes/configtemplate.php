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
 * groupingsettings class for directory local plugin
 * @package    local_directory
 * @author Grigory Baleevskiy (grigory@catalyst-au.net)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright  Catalyst
 */


/**
 * Class local_directory_groupingsetting
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright  Catalyst
 */
class local_directory_configtemplate extends admin_setting_configtextarea {
    /**
     * Write the setting.
     *
     * @param mixed $data Incoming form data.
     * @return string Always empty string representing no issues.
     */
    public function write_setting($data) {
        $fieldlist = array_map('preg_quote',
            array_map(
                array($this, 'suffix_doublebraces'),
                local_directory_settings::getfieldlist(true))
        );
        preg_match_all('/\{\{(?!('.implode('|', $fieldlist).'))(.*?)\}\}/', $data, $matches);

        if (count($matches[2])) {
            return get_string('error_illegal_field', 'local_directory', $matches[2][0]);
        }
        $this->config_write($this->name, $data);
        return '';
    }

    /**
     * add a suffix to field name
     * @param string $str
     * @return string
     */
    public function suffix_doublebraces($str) {
        return $str.'}}';
    }
}
