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
 * Settings for directory local plugin
 * @package    local_directory
 * @author Grigory Baleevskiy (grigory@catalyst-au.net)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright  Catalyst
 */

defined('MOODLE_INTERNAL') || die;
require(dirname(__FILE__).'/config.php');

if ($hassiteconfig) {
    $settings = new admin_settingpage('local_directory', get_string('pluginname', 'local_directory'));

    $ADMIN->add('localplugins', $settings);

    $label = get_string('fields_search', 'local_directory');
    $desc = get_string('fields_search', 'local_directory');
    $default = array_keys($searchfieldsarray);
    $settings->add(new \admin_setting_configmulticheckbox('local_directory/fields_search', $label, $desc, $default, array_combine($searchfieldsarray, $searchfieldsarray)));

    $label = get_string('fields_display', 'local_directory');
    $desc = get_string('fields_display', 'local_directory');
    $default = array_keys($searchfieldsarray);
    $settings->add(new \admin_setting_configmulticheckbox('local_directory/fields_display', $label, $desc, $default,  array_combine($searchfieldsarray, $searchfieldsarray)));

}
