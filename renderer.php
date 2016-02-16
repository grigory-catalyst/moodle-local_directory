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


/**
 * Class directory_user
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright Catalyst
 */
class directory_user implements renderable {
    /**
     * @var stdclass
     */
    private $__user;
    /**
     * @var stdClass
     */
    private $__options;

    /**
     * directory_user constructor.
     *
     * @param stdclass $user item to be rendered
     * @param array $options array of options
     */
    public function __construct(stdclass $user, $options) {
        $this->__user = $user;
        $this->__options = $options;
    }

    /**
     * attribute getter
     * @param string $name
     * @return mixed
     */
    public function __get($name) {
        return $this->__user->$name;
    }

    /**
     * field list getter
     * @param array $attrs
     * @return array
     */
    public function getattrs($attrs=null) {
        if (is_null($attrs)) {
            return (array) $this->__user;
        }

        $result = array();
        foreach ($attrs as $field) {
            $result[$field] = $this->__user->$field;
        }
        return $result;
    }

    /**
     * option getter
     * @param string $name
     * @return mixed
     */
    public function option($name) {
        return $this->__options[$name];
    }

    /**
     * option setter
     * @param string $name
     * @param mixed $value
     */
    public function setoption($name, $value) {
        $this->__options[$name] = $value;
    }
}

/**
 * Class directory_user_list
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright Catalyst
 */
class directory_user_list implements renderable {
    /**
     * @var array List of users
     */
    public $list;
    /**
     * @var array Array of options
     */
    protected $_options;

    /**
     * directory_user_list constructor.
     * @param array $list
     * @param array $options
     */
    public function __construct($list = array(), $options = array()) {
        $this->list = $list;
        $this->setoptions($options);
    }

    /**
     * options setter method
     * @param array $options
     * @return directory_user_list $this
     */
    public function setoptions($options) {
        $this->_options = (object) $options;
        return $this;
    }

    /**
     * option array getter
     * @return array
     */
    public function getoptions() {
        return $this->_options;
    }

}

/**
 * Class directory_grouping_row
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright Catalyst
 */
class directory_grouping_row implements renderable {
    /**
     * stores new groupings to be a base for next rows
     * @var array
     */
    public $newgroupings = array();

    /**
     * groupings to be displayed
     * @var array
     */
    public $showgroupings = array();

    /**
     * level of particular grouping
     * @var array
     */
    public $groupinglevel = array();

    /**
     * current row colspan
     * @var int
     */
    public $colspan = 0;

    /**
     * grouping row constructor
     * @param array $lastgroupings
     * @param array $current
     * @param array $fieldsgrouping
     */
    public function __construct($lastgroupings, $current, $fieldsgrouping, $colspan) {
        $result = array();
        $newgrouping = array();
        foreach ($fieldsgrouping as $k => $field) {
            if (!isset($lastgroupings[$field]) or $lastgroupings[$field] != $current[$field]) {
                $result[$field] = $current[$field];
            }
            $this->groupinglevel[$field] = $k + 1;
            $newgrouping[$field] = $current[$field];
        }
        $this->newgroupings = $newgrouping;
        $this->showgroupings = $result;
        $this->colspan = $colspan;
    }
}

/**
 * Class local_directory_renderer
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright Catalyst
 */
class local_directory_renderer extends plugin_renderer_base {
    /**
     * @var array List of fields to render
     */
    protected $_fields;

    /**
     * config getter
     * @param string $key
     * @return array
     * @throws dml_exception
     */
    public function getconfig($key) {
        return explode(',', get_config('local_directory', $key));
    }

    /**
     * config loader
     * @return array
     */
    public function getfields() {
        if (!count($this->_fields)) {
            $this->_fields = $this->getconfig('fields_display');
        }
        return $this->_fields;
    }

    /**
     * renderer for user
     * @param directory_user $user
     * @return string
     * @throws coding_exception
     */
    protected function render_directory_user(directory_user $user) {
        $out = $this->render($user->option('grouping'));
        $out .= html_writer::start_tag('tr');
        foreach ($this->getfields() as $field) {
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

    /**
     * renderer for grouping_row
     * @param directory_grouping_row $row
     * @return string
     */
    protected function render_directory_grouping_row(directory_grouping_row $row) {
        $out = '';
        foreach ($row->showgroupings as $key => $value) {
            $out .= html_writer::start_tag('tr');
            $out .= html_writer::tag('td',
                html_writer::tag('h'.$row->groupinglevel[$key], get_user_field_name($key).": ".$row->newgroupings[$key]),
                array('colspan' => $row->colspan)
                );
            $out .= html_writer::end_tag('tr');
        }
        return $out;
    }

    /**
     * main renderer
     * @param directory_user_list $list
     * @return string
     */
    protected function render_directory_user_list(directory_user_list $list) {
        $out = $findresults = $this->render_find_results($list);

        if ($list->getoptions()->total == 0) {
            return $out;
        }
        $out .= html_writer::start_tag('table', array('class' => 'directory'));

        $out .= html_writer::start_tag('tr');
        foreach ($this->getfields() as $field) {
            $out .= html_writer::tag('th', get_user_field_name($field));
        }
        $out .= html_writer::end_tag('tr');

        $lastgrouping = array();
        foreach ($list->list as $user) {
            $user->setoption('grouping', $newgrouping = new directory_grouping_row(
                $lastgrouping,
                $user->getattrs(),
                $list->getoptions()->groupings,
                count($this->getfields())
            ));

            $lastgrouping = $newgrouping->newgroupings;
            $out .= $this->render($user);
        }
        $out .= html_writer::end_tag('table');
        $out .= $findresults;
        return $out;
    }

    /**
     * renders a string of results like "found 5 users"
     * @param directory_user_list $list
     * @return string
     * @throws coding_exception
     */
    protected function render_find_results(directory_user_list $list) {

        $listoptions = $list->getoptions();
        if ($listoptions->total > 1) {
            $options = array(
                'from' => $listoptions->page * $listoptions->perpage + 1,
                'to' => $listoptions->page * $listoptions->perpage + $listoptions->found,
                'total' => $listoptions->total,
            );
            $out = html_writer::div(get_string('found_users'.($listoptions->total > $listoptions->perpage ? '' : '_all'),
                'local_directory',
                (object) $options
            ));
        } else if ($listoptions->total == 1) {
            $out = html_writer::div(get_string('found_one_user',
                'local_directory'));
        } else {
            $out = html_writer::div(get_string('not_found_users',
                'local_directory'));
        }

        return $out;
    }

}


