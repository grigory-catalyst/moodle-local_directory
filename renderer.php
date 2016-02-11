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
 * Renderer for directory local plugin
 * @package    local_directory
 * @author Grigory Baleevskiy (grigory@catalyst-au.net)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright  Catalyst
 */

class directory_user implements renderable {
    private $__user;
    private $__options;

    public function __construct(stdclass $user, $options) {
        $this->__user = $user;
        $this->__options = $options;
    }
    public function __get($name)
    {
        return $this->__user->$name;
    }
    public function option($name){
        return $this->__options[$name];
    }
}

class directory_user_list implements renderable {
    public $list;

    public function __construct($list = array()) {
        $this->list = $list;
    }
}

class local_directory_renderer extends plugin_renderer_base {

    protected $_fields;
    public function __construct(moodle_page $page, $target)
    {
        $this->_fields = explode(',', get_config('local_directory', 'fields_display'));
        parent::__construct($page, $target);
    }

    protected function render_directory_user(directory_user $user) {
        $out = html_writer::start_tag('tr');
        foreach ($this->_fields as $field) {

            switch($field) {
                case 'email':
                case 'phone1':
                case 'phone2':
                case 'skype':
                case 'url':
                    $quotedsearch = preg_quote($user->option('term'));
                    $out .= html_writer::tag('td',
                        preg_replace('/(<a.*?>.*?)('.$quotedsearch.')(.*?<\/a>)/im',
                            '$1<mark>$2</mark>$3',
                            get_string("render_$field", 'local_directory', $user->$field)
                            )

                    );
                    break;
                default:
                    $out .= html_writer::tag('td',
                        preg_replace('/('.preg_quote($user->option('term')).')/im',
                            '<mark>$1</mark>',
                            $user->$field));
            }
        }
        $out .= html_writer::end_tag('tr');
        return $out;
    }

    protected function render_directory_user_list(directory_user_list $list) {
        $out = html_writer::div(get_string('found_users', 'local_directory', count($list->list)));
        if (!count($list->list)) {
            return $out;
        }
        $out .= html_writer::start_tag('table', array('class' => 'directory'));
        $out .= html_writer::start_tag('tr');
        foreach ($this->_fields as $field) {
            $out .= html_writer::tag('th', get_user_field_name($field));
        }
        $out .= html_writer::end_tag('tr');
        foreach ($list->list as $user) {
            $out .= $this->render($user);
        }
        $out .= html_writer::end_tag('table');
        return $out;

    }
}


