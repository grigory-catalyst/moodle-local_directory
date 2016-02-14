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
require_once(dirname(__FILE__).'/search_form.php');
require_once(dirname(__FILE__).'/locallib.php');

$PAGE->set_url('/local/directory/index.php');
$PAGE->set_context(context_system::instance());

require_capability('local/directory:viewdirectory', \context_system::instance());

$mform = new local_directory_search_form(null,null,'get');
$output = $PAGE->get_renderer('local_directory');
$PAGE->requires->css('/local/directory/style.css');
$PAGE->set_title(get_string('page_title', 'local_directory'));
echo $output->header();

$formdata = $mform->get_data();
$mform->display();

if ($formdata) {
    $renderable_list = new directory_user_list();
    foreach (local_directory_search($formdata) as $id => $userdata) {
        $renderable_list->list[] = new directory_user($userdata, array(
            'term' => $formdata->term,
        ));
    }
    echo $output->render($renderable_list);
    echo $OUTPUT->paging_bar(
        count($renderable_list->list),
        $formdata->page, get_config('local_directory', 'show_per_page'),
        new moodle_url('/local/directory/index.php', array_merge(
            (array) $formdata,
            array(
                'sesskey' => sesskey(),
                '_qf__local_directory_search_form' => 1,
            )))
    );

}

echo $output->footer();