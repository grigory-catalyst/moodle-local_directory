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
 * Settings class for directory local plugin
 * @package    local_directory
 * @author Grigory Baleevskiy (grigory@catalyst-au.net)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright  Catalyst
 */

/**
 * Class local_directory_setting
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright  Catalyst
 */
class local_directory_setting {
    /**
     * list of fields which can be used in search
     * @var array
     */
    protected static $fieldlist = array(
        'idnumber',
        'firstname',
        'lastname',
        'email',
        'skype',
        'phone1',
        'phone2',
        'description',
        'institution',
        'department',
        'address',
        'city',
        'country',
        'url'
    );

    /**
     * default groupings to be displayed under the textarea in settings page
     * @var string
     */
    protected static $defaultsearchgrouping = "institution\ndepartment";

    /**
     * fieldlist getter
     * @return array
     */
    public static function getfieldlist() {
        return self::$fieldlist;
    }

    /**
     * static getter
     * @return string
     */
    public static function getdefaultsearchgroupings() {
        return self::$defaultsearchgrouping;
    }

    /**
     * search field validator
     * @param string $field
     * @return bool
     */
    public static function isvalidfield($field) {
        return in_array($field, self::$fieldlist);
    }
}

/**
 * Class local_directory_groupingsetting
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright  Catalyst
 */
class local_directory_groupingsetting extends admin_setting_configtextarea {

    /**
     * Return the setting
     *
     * @return mixed returns config if successful else null
     */
    public function get_setting() {
        return $this->config_read($this->name);
    }

    /**
     * Write the setting.
     *
     * @param mixed $data Incoming form data.
     * @return string Always empty string representing no issues.
     */
    public function write_setting($data) {
        $newconfig = explode("\n", $data);
        $result = array();
        foreach ($newconfig as $value) {
            $value = trim($value);
            if (local_directory_setting::isvalidfield($value)) {
                $result[] = $value;
            }
        }
        $this->config_write($this->name, implode("\n", array_unique($result)));
        return '';
    }
}