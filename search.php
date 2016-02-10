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
 * Search for directory local plugin
 * @package    local_directory
 * @author Grigory Baleevskiy (grigory@catalyst-au.net)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright  Catalyst
 */


require_once('../../config.php');
require_once(dirname(__FILE__).'/forms.php');
require_once('config.php');

require_login();
$PAGE->set_context(context_system::instance());

$term = optional_param('term', '',PARAM_RAW_TRIMMED);
$page = optional_param('p', 1, PARAM_INT);
$role = optional_param('role', '', PARAM_ALPHANUM);
$role = optional_param('sort', 'firstname', PARAM_ALPHANUM);


echo json_encode(array_values($result));
