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
$PAGE->set_url('/local/directory/index.php');
$PAGE->set_context(context_system::instance());
require_login();
require_capability('moodle/site:config', \context_system::instance());
$mform = new local_directory_search_form();
echo $OUTPUT->header();
if ($formdata = $mform->get_data()) {
    echo 'Searching for '.html_writer::tag('h3', $formdata->term);
}
$mform->display();
echo $OUTPUT->footer();