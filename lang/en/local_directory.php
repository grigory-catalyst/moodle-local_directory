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
 * Language file definitions for directory local plugin
 * @package    local_directory
 * @author Grigory Baleevskiy (grigory@catalyst-au.net)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright  Catalyst
 */

$string['configplugin'] = 'Configure ';
$string['pluginname'] = 'Directory';
$string['error_short_query'] = 'Query is too short';
$string['error_illegal_field'] = 'Field {{{$a}}} can not be used';
$string['fields_search'] = 'Search through fields';
$string['fields_display'] = 'Fields to display';
$string['fields_required'] = 'Required fields';
$string['header_directory'] = 'Local directory';
$string['button_search'] = 'Search';
$string['page_title'] = 'Local Directory';
$string['found_users'] = 'Showing {$a->from} - {$a->to} users out of {$a->total}';
$string['found_users_all'] = 'Showing all {$a->total} found users';
$string['found_one_user'] = 'Showing the only found user';
$string['not_found_users'] = 'No users found';
$string['render_email'] = '<a href="mailto:{$a}">{$a}</a>';
$string['render_phone1'] = '<a href="tel:{$a}">{$a}</a>';
$string['render_phone2'] = '<a href="tel:{$a}">{$a}</a>';
$string['render_skype'] = '<a href="skype:{$a}">{$a}</a>';
$string['render_url'] = '<a href="{$a}">{$a}</a>';
$string['directory:viewdirectory'] = 'View directory';
$string['show_per_page'] = 'Number of users per page';
$string['show_per_page_desc'] = 'Number of users per page';
$string['search_groupings'] = 'Group users by';
$string['search_groupings_desc'] = 'Specify some fields from the list: {$a}';
$string['column_template'] = 'Table template';

$string['column_template_desc'] = 'One line per column;<br>
                                   format:[COLUMN_NAME:]TEMPLATE <br/>
                                   TEMPLATE: {{FIELD}} | HTML <br/>
                                   FIELD: one or many from the list: {$a} <br/>
                                   HTML: html tags <br/>
                                   COLUMN_NAME: string';
$string['column_name'] = 'Column #{$a}';
$string['setting_page_name'] = 'Page title';
$string['setting_page_name_desc'] = 'Visible page title in html and in the search bar';
$string['opensearch_description'] = 'Search in {$a}';

