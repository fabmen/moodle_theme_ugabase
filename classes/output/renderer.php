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

namespace theme_ugabase\output;

defined('MOODLE_INTERNAL') || die;

use theme_bootstrapbase_core_renderer;

/**
 * Class containing data for mustache layouts
 *
 * @package         theme_ugabase
 * @copyright       2015 eFaktor
 * @author          Urs Hunkler {@link urs.hunkler@unodo.de}
 * @license         http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class renderer extends theme_bootstrapbase_core_renderer {
    /**
     * Defer to template.
     *
     * @param $page
     *
     * @return string html for the page
     */
    public function render_embedded_layout(embedded_layout $page) {
        $data = $page->export_for_template($this);
        return parent::render_from_template('theme_ugabase/wrapper_layout', $data);
    }

    /**
     * Defer to template.
     *
     * @param $page
     *
     * @return string html for the page
     */
    public function render_maintenance_layout(maintenance_layout $page) {
        $data = $page->export_for_template($this);
        return parent::render_from_template('theme_ugabase/wrapper_layout', $data);
    }

    /**
     * Defer to template.
     *
     * @param $page
     *
     * @return string html for the page
     */
    public function render_columns1_layout(columns1_layout $page) {
        $data = $page->export_for_template($this);
        return parent::render_from_template('theme_ugabase/wrapper_layout', $data);
    }

    /**
     * Defer to template.
     *
     * @param $page
     *
     * @return string html for the page
     */
    public function render_columns2_layout(columns2_layout $page) {
        $data = $page->export_for_template($this);
        return parent::render_from_template('theme_ugabase/wrapper_layout', $data);
    }

    /**
     * Defer to template.
     *
     * @param $page
     *
     * @return string html for the page
     */
    public function render_columns3_layout(columns3_layout $page) {
        $data = $page->export_for_template($this);
        return parent::render_from_template('theme_ugabase/wrapper_layout', $data);
    }

    /**
     * Defer to template.
     *
     * @param $page
     *
     * @return string html for the page
     */
    public function render_secure_layout(secure_layout $page) {
        $data = $page->export_for_template($this);
        return parent::render_from_template('theme_ugabase/wrapper_layout', $data);
    }
    public function courses_menu() {
        global $CFG;
        $coursesmenu = new custom_menu('', current_language());
        return $this->render_courses_menu($coursesmenu);
    }

    protected function render_courses_menu(custom_menu $menu){
        if (isloggedin() && !isguestuser() ) {
            $branchtitle = get_string('mycourses');
            $branchlabel = '<i class="fa fa-briefcase"></i> '.$branchtitle;
            $branchurl   = new moodle_url('/my/index.php');
            $branchsort  = 10000;

            $branch = $menu->add($branchlabel, $branchurl, $branchtitle, $branchsort);
            if ($courses = enrol_get_my_courses(NULL, 'fullname ASC')) {
                foreach ($courses as $course) {
                    if ($course->visible){
                        $branch->add(format_string($course->fullname), new moodle_url('/course/view.php?id='.$course->id), format_string($course->shortname));
                    }
                }
            } 
            $branchtitle = get_string('showallcourses');
            $branchlabel = '<i class="fa fa-sitemap"></i> '.$branchtitle;
            $branchurl   = new moodle_url('/course/index.php');
            $branchsort  = 10000;
            $menu->add($branchlabel, $branchurl, $branchtitle, $branchsort);

        $content = '<ul class="nav navbar-nav">';
        foreach ($menu->get_children() as $item) {
            $content .= $this->render_custom_menu_item($item, 1);
        }
    
        return $content.'</ul>';
        }
    }

/*
     * This renders the bootstrap top menu.
     *
     * This renderer is needed to enable the Bootstrap style navigation.
     */
    protected function render_custom_menu(custom_menu $menu) {
        global $CFG;

        // TODO: eliminate this duplicated logic, it belongs in core, not
        // here. See MDL-39565.
        $addlangmenu = true;
        $langs = get_string_manager()->get_list_of_translations();
        if (count($langs) < 2
            or empty($CFG->langmenu)
            or ($this->page->course != SITEID and !empty($this->page->course->lang))) {
            $addlangmenu = false;
        }

        if (!$menu->has_children() && $addlangmenu === false) {
            return '';
        }

        if ($addlangmenu) {
            $language = $menu->add("<i class='fa fa-flag'></i> ".get_string('language'), new moodle_url('#'), get_string('language'), 10000);
            foreach ($langs as $langtype => $langname) {
                $language->add($langname, new moodle_url($this->page->url, array('lang' => $langtype)), $langname);
            }
        }

        $content = '<ul class="nav">';
        foreach ($menu->get_children() as $item) {
            $content .= $this->render_custom_menu_item($item, 1);
        }

        return $content.'</ul>';
    }

}
