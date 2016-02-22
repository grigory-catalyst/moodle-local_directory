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
 * Index for directory local plugin
 * @package    local_directory
 * @author Grigory Baleevskiy (grigory@catalyst-au.net)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright  Catalyst
 */

require_once('../../config.php');
$CFG->additionalhtmlhead .= '
<link rel="search"
type="application/opensearchdescription+xml"
href="'.$CFG->wwwroot.'/local/directory/opensearch.php"
title="Directory search" />';

$PAGE->set_url('/local/directory/index.php');
$PAGE->set_context(context_system::instance());

require_capability('local/directory:viewdirectory', \context_system::instance());
$output = $PAGE->get_renderer('local_directory');
$PAGE->requires->css('/local/directory/style.css');
$PAGE->set_title(local_directory_settings::get_config('page_name'));

$mform = new local_directory_form();
$formdata = $mform->getdata();

list($isvalid, $errors) = $mform->validate($formdata);
$ellipsis = isset($_GET['ellipsis']);
unset($_GET['ellipsis']);

$renderablelist = new directory_user_list();
$searchhandler = new local_directory_search();
$navsearch = new local_directory_navigation();
$searchoptions = new local_directory_search_options(
    array_merge(
        array(
            'fieldssearch' => explode(',', local_directory_settings::get_config('fields_search')),
            'showperpage' => local_directory_settings::get_config('show_per_page'),
            'groupings' => array_filter(explode("\n", local_directory_settings::get_config('search_groupings'))),
            'navigation_levels' => local_directory_settings::get_config('navigation_levels'),
            'navigation_max_children' => $ellipsis ? 500 : local_directory_settings::get_config('navigation_max_children'),
            'navigation_order' => local_directory_settings::get_config('navigation_order'),
            'request' => $_GET,
        ),
        $formdata)
);


$navbar = new directory_navigation($navsearch->search($searchoptions), $searchoptions);
$crumbs = new directory_breadcrumbs($searchoptions);
$crumbs->create_crumbs($PAGE->navbar);
echo $output->header();
require('form.tpl');
echo $output->render($navbar);

if ($isvalid or $navsearch->isonlastlevel($searchoptions) or $navbar->isnothingtodisplay()) {
    list($count , $users) = $searchhandler->search($searchoptions);
    foreach ($users as $id => $userdata) {
        $renderablelist->list[] = new directory_user($userdata, array('q' => $formdata['q']));
    }
    $renderablelist->setoptions(array_merge(
        array(
            'total' => $count,
            'found' => count($renderablelist->list),
            'perpage' => $searchoptions->showperpage,
            'column_template' => local_directory_settings::get_config('column_template'),
        ),
        $searchoptions->getoptions()
    ));
    echo $pageingbar = $OUTPUT->paging_bar(
        $count,
        $formdata['page'], $searchoptions->showperpage,
        new moodle_url('/local/directory/', array_merge($formdata, $searchhandler->getnavigationfilter($searchoptions)))
    );
    echo $output->render($renderablelist);
    echo $pageingbar;

}

echo $output->footer();