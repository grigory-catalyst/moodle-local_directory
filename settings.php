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
        'firstname',
        'lastname',
        'email',
        'skype',
        'phone1',
        'phone2',
        'description',
        'lastnamephonetic',
        'firstnamephonetic',
        'middlename',
        'alternatename',
    );


    $settings = new admin_settingpage('local_directory', get_string('pluginname', 'local_directory'));

    $ADMIN->add('localplugins', $settings);

    foreach(array('fields_search', 'fields_display', 'fields_required') as $key){
        $label = get_string($key, 'local_directory');
        $desc = get_string($key, 'local_directory');
        $default = $searchfieldsarray;
        $settings->add(new \admin_setting_configmulticheckbox("local_directory/$key",
            $label, $desc, $default,
            array_combine($searchfieldsarray, array_map("get_user_field_name", $searchfieldsarray))));

    }

}
