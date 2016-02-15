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
if ($hassiteconfig) {

    $searchfieldsarray = array(
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
        'url',
    );


    $settings = new admin_settingpage('local_directory', get_string('pluginname', 'local_directory'));

    $ADMIN->add('localplugins', $settings);

    foreach (array('fields_search', 'fields_display', 'fields_required') as $key) {
        $label = get_string($key, 'local_directory');
        $desc = get_string($key, 'local_directory');
        $default = $searchfieldsarray;
        $settings->add(new \admin_setting_configmulticheckbox("local_directory/$key",
            $label, $desc, $default,
            array_combine($searchfieldsarray, array_map("get_user_field_name", $searchfieldsarray))));
    }
    $choices = range(25, 100, 25);
    $default = $choices[0];
    $choices = array_combine($choices, $choices);
    $settings->add(new \admin_setting_configselect("local_directory/show_per_page",
        get_string('show_per_page', 'local_directory'),
        get_string('show_per_page_desc', 'local_directory'),
        $default,
        $choices));
}
