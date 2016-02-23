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
 * Class directrory_templated_row
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright Catalyst
 */
class directory_templated_row implements renderable {
    /**
     * store tpl for row
     * @var string
     */
    public $template;
    /**
     * @var directory_user
     */
    public $user;

    /**
     * @var columns
     */
    public static $columns;

    /**
     * for cache
     * @var array
     */
    private static $tpl = array();
    /**
     * directrory_templated_row constructor.
     * @param string $template
     * @param directory_user $user
     */
    public function __construct($template, $user) {
        $this->template = $template;
        $this->user = $user;
    }

    /**
     * parses template
     * @param string $template
     * @return array of columnNames and columns
     */
    public static function parse_template($template) {
        $result = array(array(), array());
        if (empty(trim($template))) {
            return $result;
        }
        if (!isset(self::$tpl[$template])) {
            preg_match_all('/^(:?(\w+)\s*:\s*)?(.*)$/m', $template, $matches);

            foreach ($matches[2] as $pos => $columnname) {
                if (empty(trim($matches[2][$pos])) and empty(trim($matches[3][$pos]))) {
                    continue;
                }
                if (!empty($columnname)) {
                    $result[0][$columnname] = $columnname;
                } else if (preg_match('/\{\{(\w+)\}\}/', $matches[3][$pos], $namesincolumn)) {
                    $result[0][$namesincolumn[1]] = self::$columns[$namesincolumn[1]];
                } else {
                    $result[0]['column'.$pos] = get_string('column_name', 'local_directory', $pos + 1);
                }
                $result[1][] = $matches[3][$pos];
            }
            self::$tpl[$template] = $result;
        }
        return self::$tpl[$template];
    }

    /**
     * template validator
     * @param string $template
     * @return bool
     */
    public static function isvalidtemplate($template) {
        list($columnsnames, $columns) = self::parse_template($template);
        return count($columnsnames) > 0;
    }

    /**
     * replaces all occasions with users data
     * @param string $str template
     * @return mixed
     */
    public function renderstring($str) {
        return preg_replace_callback('/\{\{(\w+)\}\}/', array($this->user, 'renderattrcb'), $str);
    }

}

/**
 * Class directory_navigation
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright Catalyst
 */
class directory_navigation implements renderable {
    /**
     * navigation items
     * @var array
     */
    public $items = array();

    /**
     * search options for navigation
     * @var
     */
    public $searchoptions;
    /**
     * flag to show ellipsis
     * @var bool
     */
    protected $isneedshowellipsis;

    /**
     * directory_navigation constructor.
     * @param array $navitems
     * @param local_directory_search_options $searchoptions
     */
    public function __construct($navitems, local_directory_search_options $searchoptions) {
        $this->isneedshowellipsis = count($navitems) > $searchoptions->navigation_max_children;
        $this->items = array_slice($navitems, 0, $searchoptions->navigation_max_children);
        $this->searchoptions = $searchoptions;
    }

    /**
     * simple check whether we've got something to show or not
     * checks whether we've got something to show
     * @return bool
     */
    public function isnothingtodisplay() {
        return count($this->items) <= 1;
    }

    /**
     * var getter
     * getter for isellipsis
     * @return bool
     */
    public function isneedshowellips() {
        return $this->isneedshowellipsis;
    }

}


/**
 * Class directory_breadcrumbs
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright Catalyst
 */
class directory_breadcrumbs implements renderable {
    /**
     * @var local_directory_search_options
     */
    public $options;

    /**
     * directory_breadcrumbs constructor.
     * @param local_directory_search_options $searchoptions
     */
    public function __construct(local_directory_search_options $searchoptions) {
        $this->options = $searchoptions;
    }

    /**
     * puts crumbs into object
     * @param object $obj
     */
    public function create_crumbs($obj) {
        foreach ($this->get_crumbs() as $key => $crumb) {
            $obj->add($crumb['value'], new moodle_url('', $crumb['params']));
        }
    }

    /**
     * crumbs generator
     * @return array
     * @throws coding_exception
     */
    public function get_crumbs() {
        $plainrequest = array_diff_key($this->options->request, array_flip($this->options->groupings));
        unset($plainrequest['page']);

        $allcrumb = array(
            'value' => local_directory_settings::get_config('page_name'),
            'params' => $plainrequest
        );

        $result = array();
        foreach ($this->options->groupings as $grouping) {
            if (isset($this->options->request[$grouping])) {
                $plainrequest[$grouping] = $this->options->request[$grouping];
                $result[$grouping] = array(
                    'value' => $this->options->request[$grouping],
                    'params' => $plainrequest
                );
            } else {
                break;
            }
        }
        array_unshift($result, $allcrumb);

        return $result;
    }
}

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
     * renderer
     * @var
     */
    public $picturerenderer;

    /**
     * @var directory_user_list
     */
    public $list;

    /**
     * directory_user constructor.
     *
     * @param stdclass $user item to be rendered
     * @param array $options array of options
     */
    public function __construct(stdclass $user, $options) {
        $this->__user = $user;
        $this->__options = $options;
        if (isset($options['picturerenderer'])) {
            $this->picturerenderer = $options['picturerenderer'];
        }

    }

    /**
     * attribute getter
     * @param string $name
     * @return mixed
     */
    public function __get($name) {
        if (isset($this->__user->$name)) {
            return $this->__user->$name;
        }
        return '';
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

    /**
     * renders a td or plain attr depending on wrap parameter
     * @param string $fieldname
     * @param bool $wrap
     * @return string
     * @throws coding_exception
     */
    public function renderattr($fieldname, $wrap=true) {
        $params = array();
        $quotedsearch = implode('|',
            array_map('preg_quote',
                array_filter(
                    array_map('trim',
                        explode(
                            ' ',
                            htmlspecialchars($this->option('q'))
                        )
                    )
                )
            )
        );
        switch($fieldname) {
            case 'userpicture':
                $res = $this->picturerenderer->user_picture($this->__user, array('size' => 50));
                break;
            case 'email':
            case 'phone1':
            case 'phone2':
            case 'skype':
            case 'url':
                $res = get_string("render_$fieldname", 'local_directory', htmlspecialchars($this->$fieldname));

                if (!empty($this->option('q'))) {
                    $res = preg_replace('/(<a.*?>.*?)(' . $quotedsearch . ')(.*?<\/a>)/im',
                        '$1<mark>$2</mark>$3', $res
                    );
                    $params = array('class' => 'alright');
                }
                break;
            default:
                $res = htmlspecialchars($this->$fieldname);
                if (!empty($this->option('q'))) {
                    $res = preg_replace('/(' . $quotedsearch . ')/im',
                        '<mark>$1</mark>',
                        $res
                    );
                }
        }
        if ($wrap) {
            $res = html_writer::tag('td', $res, $params);
        }
        return $res;
    }

    /**
     * for preg_replace_call
     * @param array $arr
     * @return string
     */
    public function renderattrcb(array $arr) {
        return $this->renderattr($arr[1], false);
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
     * @var array List of fields to render
     */
    protected $_fields;

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
     * maps column name into displayable strings
     * @param string $field
     * @return string
     */
    public function getcolumndisplayname($field) {
        return $this->_options['columns'][$field];
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

    /**
     * depending on the template generates a list of columns
     * @return array
     */
    public function getfieldsdisplay() {
        if (!count($this->_fields)) {
            if (directory_templated_row::isvalidtemplate($tpl = $this->_options->column_template)) {
                list($this->_fields, $columns) = directory_templated_row::parse_template($tpl);
            }
        }
        return $this->_fields;
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
     * directory_grouping_row constructor.
     * @param array $lastgroupings
     * @param array $current
     * @param array $fieldsgrouping
     * @param int $colspan
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
     * renderer for user
     * @param directory_user $user
     * @return string
     * @throws coding_exception
     */
    protected function render_directory_user(directory_user $user) {
        $out = $this->render($user->option('grouping'));
        $out .= html_writer::start_tag('tr');
        foreach ($user->list->getfieldsdisplay() as $fieldname => $visiblename) {
            $out .= $user->renderattr($fieldname);
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
            $out .= html_writer::start_tag('tr', array('class' => 'groupingrow'));
            $out .= html_writer::tag('td',
                html_writer::tag('h'.$row->groupinglevel[$key], get_user_field_name($key).": ".$row->newgroupings[$key]),
                array(
                    'colspan' => $row->colspan,
                )
                );
            $out .= html_writer::end_tag('tr');
        }
        return $out;
    }

    /**
     * navigation renderer
     * @param directory_navigation $navbar
     * @return string
     */
    public function render_directory_navigation(directory_navigation $navbar) {
        $out = '';
        if ($navbar->isnothingtodisplay()) {
            return $out;
        }
        foreach ($navbar->items as $item) {
            $params = array_merge($navbar->searchoptions->request, array($item->__field => $item->{$item->__field}));
            unset($params['page']);
            $out .= html_writer::link(
                new moodle_url('', $params),
                sprintf('%s <span class="number">(%d)</span>', $item->{$item->__field}, $item->count)
            );
        }
        if ($navbar->isneedshowellips()) {
            $params = array_merge($navbar->searchoptions->request, array('ellipsis' => 1));
            $out .= html_writer::link(
                new moodle_url('', $params),
                get_string('ellipsis', 'local_directory')
            );
        }

        return html_writer::div($out, 'directorynavbar', array('class' => 'clearfix'));
    }

    /**
     * render tamplated row
     * @param directory_templated_row $row
     * @return string
     */
    protected function render_directory_templated_row(directory_templated_row $row) {
        list($columnnames, $columns) = $row->parse_template($row->template);
        $out = $this->render($row->user->option('grouping'));
        $out .= html_writer::start_tag('tr');
        foreach ($columns as $column) {
            $out .= html_writer::tag('td', $row->renderstring($column));
        }
        $out .= html_writer::end_tag('tr');
        return $out;
    }

    /**
     * main renderer
     * @param directory_user_list $list
     * @return string
     */
    protected function render_directory_user_list(directory_user_list $list) {
        $out = $findresults = $this->render_find_results($list);
        $listoptions = $list->getoptions();
        if ($listoptions->total == 0) {
            return $out;
        }
        directory_templated_row::$columns = $listoptions->columns;
        $out .= html_writer::start_tag('table', array('class' => 'directory'));

        $out .= html_writer::start_tag('tr');
        foreach ($list->getfieldsdisplay() as $field) {
            $out .= html_writer::tag('th', $field);
        }
        $out .= html_writer::end_tag('tr');

        $lastgrouping = array();
        foreach ($list->list as $user) {
            $user->list = $list;
            $user->setoption('grouping', $newgrouping = new directory_grouping_row(
                $lastgrouping,
                $user->getattrs(),
                $list->getoptions()->groupings,
                count($list->getfieldsdisplay())
            ));
            $row = new directory_templated_row($listoptions->column_template, $user);

            if ($row->isvalidtemplate($listoptions->column_template)) {
                $out .= $this->render($row);
            } else {
                $out .= $this->render($user);
            }
            $lastgrouping = $newgrouping->newgroupings;
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


