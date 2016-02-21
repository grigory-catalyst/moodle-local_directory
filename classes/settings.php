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
class local_directory_settings {
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
     * default name of the page
     * @var string
     */
    public static $defaultpagename = "Directory";

    /**
     * default navigation_levels
     * @var int
     */
    public static $defaultnavigationlevels = 2;

    /**
     * default maximum children of navigation menu on the page
     * @var int
     */
    public static $defaultmaximumchildren = 15;


    /**
     * default template for columns
     * @var string
     */
    public static $defaultcolumntemplate = <<<EOT
Name: {{firstname}} {{lastname}}
{{email}}
{{phone1}}
EOT;
    /**
     * array of default values
     * @var array
     */
    protected static $defaultconfig = array();

    /**
     * defines a default configs for some settings
     * @param string $name
     * @return mixed
     * @throws dml_exception
     */
    public static function get_config($name) {
        if (count(self::$defaultconfig) == 0) {
            self::$defaultconfig = array(
                'column_template' => self::$defaultcolumntemplate,
                'search_groupings' => self::$defaultsearchgrouping,
                'fields_search' => implode(',', self::$fieldlist),
                'show_per_page' => 25,
                'page_name' => self::$defaultpagename,
                'navigation_levels' => self::$defaultnavigationlevels,
                'navigation_max_children' => self::$defaultmaximumchildren,
            );
            foreach (self::$defaultconfig as $k => $v) {
                if (!empty($cfg = get_config('local_directory', $k))) {
                    self::$defaultconfig[$k] = $cfg;
                }
            }

        }
        if (!isset(self::$defaultconfig[$name])) {
            self::$defaultconfig[$name] = get_config('local_directory', $name);
        }
        return self::$defaultconfig[$name];
    }

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

