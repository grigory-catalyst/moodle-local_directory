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
 * Opensearch for directory local plugin
 * @package    local_directory
 * @author Grigory Baleevskiy (grigory@catalyst-au.net)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright  Catalyst
 */

require_once('../../config.php');

header('Content-Type: application/xml; charset=utf-8');
$body = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/">
    <ShortName>%s</ShortName>
    <Description>%s</Description>
    <Contact>%s</Contact>
    <Url type="text/html"
         template="%s/local/directory/?q={searchTerms}&amp;page={startPage?}"/>
</OpenSearchDescription>
EOT;
echo sprintf($body,
    $pagename = local_directory_settings::get_config('page_name'),
    get_string('opensearch_description', 'local_directory', $pagename),
    get_config('moodle', 'supportemail'),
    $CFG->wwwroot
);
