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

    $searchfieldsarray = local_directory_settings::getfieldlist();
    $settings = new admin_settingpage('local_directory', get_string('pluginname', 'local_directory'));

    $ADMIN->add('localplugins', $settings);

    $settings->add(new admin_setting_configtext('local_directory/page_name',
            get_string('setting_page_name', 'local_directory'),
            get_string('setting_page_name_desc', 'local_directory'),
            local_directory_settings::$defaultpagename
        ));

    foreach (array('fields_search', 'fields_required') as $key) {
        $label = get_string($key, 'local_directory');
        $desc = get_string($key, 'local_directory');
        $default = $searchfieldsarray;
        $settings->add(new \admin_setting_configmulticheckbox("local_directory/$key",
            $label, $desc, $default,
            local_directory_settings::getfieldlist(false, true)));
    }
    $choices = range(1, 5);
    $default = local_directory_settings::$defaultnavigationlevels;
    $choices = array_combine($choices, $choices);
    $settings->add(new \admin_setting_configselect("local_directory/navigation_levels",
        get_string('navigation_levels', 'local_directory'),
        get_string('navigation_levels_desc', 'local_directory'),
        $default,
        $choices));

    $choices = range(10, 50, 5);
    $default = local_directory_settings::$defaultmaximumchildren;
    $choices = array_combine($choices, $choices);
    $settings->add(new \admin_setting_configselect("local_directory/navigation_max_children",
        get_string('navigation_max_children', 'local_directory'),
        get_string('navigation_max_children_desc', 'local_directory'),
        $default,
        $choices));

    $choices = range(25, 100, 25);
    $default = $choices[0];
    $choices = array_combine($choices, $choices);
    $settings->add(new \admin_setting_configselect("local_directory/show_per_page",
        get_string('show_per_page', 'local_directory'),
        get_string('show_per_page_desc', 'local_directory'),
        $default,
        $choices));

    $settings->add(new local_directory_configtemplate('local_directory/column_template',
        get_string('column_template', 'local_directory'),
        get_string('column_template_desc',
            'local_directory',
            implode(', ', array_keys(local_directory_settings::getfieldlist(true, true)))),
        local_directory_settings::$defaultcolumntemplate));

    $settings->add(new local_directory_groupingsetting('local_directory/search_groupings',
        get_string('search_groupings', 'local_directory'),
        get_string('search_groupings_desc',
            'local_directory',
            implode(', ', array_keys(local_directory_settings::getfieldlist(false, true)))),
        local_directory_settings::getdefaultsearchgroupings()));

    $settings->add(new admin_setting_configselect('local_directory/navigation_order',
        get_string('navigation_order', 'local_directory'),
        get_string('navigation_order_desc', 'local_directory'),
        local_directory_settings::$defaultnavigationordering,
        array(
            'count' => get_string('sorting_count', 'local_directory'),
            'alpha' => get_string('sorting_alpha', 'local_directory'),
        )));


}
