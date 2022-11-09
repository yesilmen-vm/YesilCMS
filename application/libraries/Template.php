<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * CodeIgniter Template Class
 *
 * Build your CodeIgniter pages much easier with partials, breadcrumbs, layouts and themes
 *
 * @package            CodeIgniter
 * @subpackage         Libraries
 * @category           Libraries
 * @author             Philip Sturgeon
 * @license            http://philsturgeon.co.uk/code/dbad-license
 * @link               http://getsparks.org/packages/template/show
 */
class Template
{
    private $_module = '';
    private $_controller = '';
    private $_method = '';

    private $_theme = null;
    private $_theme_path = null;
    private $_layout = false; // By default, dont wrap the view with anything
    private $_layout_subdir = ''; // Layouts and partials will exist in views/layouts
    // but can be set to views/foo/layouts with a subdirectory

    private $_title = '';
    private $_metadata = array();

    private $_partials = array();

    private $_breadcrumbs = array();

    private $_title_separator = ' | ';

    private $_parser_enabled = true;
    private $_parser_body_enabled = true;

    private $_theme_locations = array();

    private $_is_mobile = false;

    // Minutes that cache will be alive for
    private $cache_lifetime = 0;

    private $_ci;

    private $_data = array();

    /**
     * Constructor - Sets Preferences
     *
     * The constructor can be passed an array of config values
     */
    public function __construct($config = array())
    {
        $this->_ci = &get_instance();

        if (! empty($config)) {
            $this->initialize($config);
        }

        $js     = APPPATH . 'js/';
        $css    = APPPATH . 'css/';
        $assets = 'assets/';
        $routes = APPPATH . 'themes/' . $this->_theme . '/';

        $this->set_metadata('charset', 'utf-8', 'other');
        $this->set_metadata('viewport', 'width=device-width, initial-scale=1');
        if ($this->_ci->config->item('seo_meta_enable')) {
            $this->set_metadata('description', $this->_ci->config->item('seo_meta_desc'));
            $this->set_metadata('keywords', $this->_ci->config->item('seo_meta_keywords'));
        }
        if ($this->_ci->config->item('seo_og_enable')) {
            $this->set_metadata('og:title', $this->_ci->config->item('seo_title'), 'property');
            $this->set_metadata('og:type', $this->_ci->config->item('seo_meta_desc'), 'property');
            $this->set_metadata('og:image', $this->_ci->config->item('seo_imgurl'), 'property');
            $this->set_metadata('og:url', $this->_ci->config->item('base_url'), 'property');
        }
        if ($this->_ci->config->item('seo_twitter_enable')) {
            $this->set_metadata('twitter:card', 'summary');
            $this->set_metadata('twitter:title', $this->_ci->config->item('seo_title'));
            $this->set_metadata('twitter:url', $this->_ci->config->item('base_url'));
            $this->set_metadata('twitter:description', $this->_ci->config->item('seo_meta_desc'));
            $this->set_metadata('twitter:image', $this->_ci->config->item('seo_imgurl'));
        }
        $this->set_metadata('stylesheet', base_url($assets . 'core/amaranjs/css/amaran.min.css'), 'link');
        $this->set_metadata('script', base_url($assets . 'core/js/jquery.min.js'), 'script');
        $this->set_metadata('script', base_url($assets . 'core/fontawesome/js/solid.min.js'), 'script');
        $this->set_metadata('script', base_url($assets . 'core/fontawesome/js/regular.min.js'), 'script');
        $this->set_metadata('script', base_url($assets . 'core/fontawesome/js/fontawesome.min.js'), 'script');
        $this->set_metadata('script', base_url($assets . 'core/amaranjs/js/jquery.amaran.min.js'), 'script');

        log_message('debug', 'Template Class Initialized');
    }

    // --------------------------------------------------------------------

    /**
     * Initialize preferences
     *
     * @access    public
     *
     * @param  array
     *
     * @return    void
     */
    public function initialize($config = array())
    {
        foreach ($config as $key => $val) {
            if ($key == 'theme' and $val != '') {
                $this->set_theme($val);
                continue;
            }

            $this->{'_' . $key} = $val;
        }

        // No locations set in config?
        if ($this->_theme_locations === array()) {
            // Let's use this obvious default
            $this->_theme_locations = array(APPPATH . 'themes/');
        }

        // Theme was set
        if ($this->_theme) {
            $this->set_theme($this->_theme);
        }

        // If the parse is going to be used, best make sure it's loaded
        if ($this->_parser_enabled === true) {
            $this->_ci->load->library('parser');
        }

        // Modular Separation / Modular Extensions has been detected
        if (method_exists($this->_ci->router, 'fetch_module')) {
            $this->_module = $this->_ci->router->fetch_module();
        }

        // What controllers or methods are in use
        $this->_controller = $this->_ci->router->fetch_class();
        $this->_method     = $this->_ci->router->fetch_method();

        // Load user agent library if not loaded
        $this->_ci->load->library('user_agent');

        // We'll want to know this later
        $this->_is_mobile = $this->_ci->agent->is_mobile();
    }

    // --------------------------------------------------------------------

    /**
     * Magic Get function to get data
     *
     * @access    public
     *
     * @param  string
     *
     * @return    mixed
     */
    public function __get($name)
    {
        return isset($this->_data[$name]) ? $this->_data[$name] : null;
    }

    // --------------------------------------------------------------------

    /**
     * Magic Set function to set data
     *
     * @access    public
     *
     * @param  string
     *
     * @return    mixed
     */
    public function __set($name, $value)
    {
        $this->_data[$name] = $value;
    }

    // --------------------------------------------------------------------

    /**
     * Set data using a chainable metod. Provide two strings or an array of data.
     *
     * @access    public
     *
     * @param  string
     *
     * @return    mixed
     */
    public function set($name, $value = null)
    {
        // Lots of things! Set them all
        if (is_array($name) or is_object($name)) {
            foreach ($name as $item => $value) {
                $this->_data[$item] = $value;
            }
        } // Just one thing, set that
        else {
            $this->_data[$name] = $value;
        }

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Build the entire HTML output combining partials, layouts and views.
     *
     * @access    public
     *
     * @param  string
     *
     * @return    void
     */
    public function build($view, $data = array(), $return = false)
    {
        // Set whatever values are given. These will be available to all view files
        is_array($data) or $data = (array)$data;

        // Merge in what we already have with the specific data
        $this->_data = array_merge($this->_data, $data);

        // We don't need you any more buddy
        unset($data);

        if (empty($this->_title)) {
            $this->_title = $this->_guess_title();
        }

        // Output template variables to the template
        $template['title']       = $this->_title;
        $template['breadcrumbs'] = $this->_breadcrumbs;
        $template['metadata']    = implode("\n    ", $this->_metadata);
        $template['partials']    = array();
        $template['location']    = base_url('application/themes/' . $this->get_theme() . '/');
        $template['assets']      = base_url('assets/');

        // Assign by reference, as all loaded views will need access to partials
        $this->_data['template'] = &$template;

        foreach ($this->_partials as $name => $partial) {
            // We can only work with data arrays
            is_array($partial['data']) or $partial['data'] = (array)$partial['data'];

            // If it uses a view, load it
            if (isset($partial['view'])) {
                $template['partials'][$name] = $this->_find_view($partial['view'], $partial['data']);
            } // Otherwise the partial must be a string
            else {
                if ($this->_parser_enabled === true) {
                    $partial['string'] = $this->_ci->parser->parse_string($partial['string'], $this->_data + $partial['data'], true, true);
                }

                $template['partials'][$name] = $partial['string'];
            }
        }

        // Disable sodding IE7's constant cacheing!!
        $this->_ci->output->set_header('Expires: Sat, 01 Jan 2000 00:00:01 GMT');
        $this->_ci->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->_ci->output->set_header('Cache-Control: post-check=0, pre-check=0, max-age=0');
        $this->_ci->output->set_header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        $this->_ci->output->set_header('Pragma: no-cache');

        // Let CI do the caching instead of the browser
        $this->_ci->output->cache($this->cache_lifetime);

        // Test to see if this file
        $this->_body = $this->_find_view($view, array(), $this->_parser_body_enabled);

        // Want this file wrapped with a layout file?
        if ($this->_layout) {
            // Added to $this->_data['template'] by refference
            $template['body'] = $this->_body;

            // Find the main body and 3rd param means parse if its a theme view (only if parser is enabled)
            $this->_body = self::_load_view('layouts/' . $this->_layout, $this->_data, true, self::_find_view_folder());
        }

        // Want it returned or output to browser?
        if (! $return) {
            $this->_ci->output->set_output($this->_body);
        }

        return $this->_body;
    }

    /**
     * Set the title of the page
     *
     * @access    public
     *
     * @param  string
     *
     * @return    void
     */
    public function title()
    {
        // If we have some segments passed
        if (func_num_args() >= 1) {
            $title_segments = func_get_args();
            $this->_title   = implode($this->_title_separator, $title_segments);
        }

        return $this;
    }


    /**
     * Put extra javascipt, css, meta tags, etc before all other head data
     *
     * @access    public
     *
     * @param  string  $line  The line being added to head
     *
     * @return    void
     */
    public function prepend_metadata($line)
    {
        array_unshift($this->_metadata, $line);

        return $this;
    }


    /**
     * Put extra javascipt, css, meta tags, etc after other head data
     *
     * @access    public
     *
     * @param  string  $line  The line being added to head
     *
     * @return    void
     */
    public function append_metadata($line)
    {
        $this->_metadata[] = $line;

        return $this;
    }


    /**
     * Set metadata for output later
     *
     * @access    public
     *
     * @param  string  $name     keywords, description, etc
     * @param  string  $content  The content of meta data
     * @param  string  $type     Meta-data comes in a few types, links for example
     *
     * @return    void
     */
    public function set_metadata($name, $content, $type = 'meta', $extra = '')
    {
        $name    = htmlspecialchars(strip_tags($name));
        $content = htmlspecialchars(strip_tags($content));

        // Keywords with no comments? ARG! comment them
        if ($name == 'keywords' and ! strpos($content, ',')) {
            $content = preg_replace('/[\s]+/', ', ', trim($content));
        }

        switch ($type) {
            case 'meta':
                $this->_metadata[$name] = '<meta name="' . $name . '" content="' . $content . '" ' . $extra . ' />';
                break;

            case 'other':
                $this->_metadata[$content] = '<meta ' . $name . '="' . $content . '" />';
                break;

            case 'property':
                $this->_metadata[$name] = '<meta property="' . $name . '" content="' . $content . '" />';
                break;

            case 'link':
                $this->_metadata[$content] = '<link rel="' . $name . '" href="' . $content . '" ' . $extra . ' />';
                break;

            case 'script':
                $this->_metadata[$content] = '<script src="' . $content . '" ></script>';
                break;
        }

        return $this;
    }


    /**
     * Which theme are we using here?
     *
     * @access    public
     *
     * @param  string  $theme  Set a theme for the template library to use
     *
     * @return    void
     */
    public function set_theme($theme = null)
    {
        $this->_theme = $theme;
        foreach ($this->_theme_locations as $location) {
            if ($this->_theme and file_exists($location . $this->_theme)) {
                $this->_theme_path = rtrim($location . $this->_theme . '/');
                break;
            }
        }

        return $this;
    }

    /**
     * Get the current theme
     *
     * @access public
     * @return string    The current theme
     */
    public function get_theme()
    {
        return $this->_theme;
    }

    /**
     * Get the current theme path
     *
     * @access    public
     * @return    string The current theme path
     */
    public function get_theme_path()
    {
        return $this->_theme_path;
    }


    /**
     * Which theme layout should we using here?
     *
     * @access    public
     *
     * @param  string  $view
     *
     * @return    void
     */
    public function set_layout($view, $_layout_subdir = '')
    {
        $this->_layout = $view;

        $_layout_subdir and $this->_layout_subdir = $_layout_subdir;

        return $this;
    }

    /**
     * Set a view partial
     *
     * @access    public
     *
     * @param  string
     * @param  string
     * @param  boolean
     *
     * @return    void
     */
    public function set_partial($name, $view, $data = array())
    {
        $this->_partials[$name] = array('view' => $view, 'data' => $data);

        return $this;
    }

    /**
     * Set a view partial
     *
     * @access    public
     *
     * @param  string
     * @param  string
     * @param  boolean
     *
     * @return    void
     */
    public function inject_partial($name, $string, $data = array())
    {
        $this->_partials[$name] = array('string' => $string, 'data' => $data);

        return $this;
    }


    /**
     * Helps build custom breadcrumb trails
     *
     * @access    public
     *
     * @param  string  $name     What will appear as the link text
     * @param  string  $url_ref  The URL segment
     *
     * @return    void
     */
    public function set_breadcrumb($name, $uri = '')
    {
        $this->_breadcrumbs[] = array('name' => $name, 'uri' => $uri);

        return $this;
    }

    /**
     * Set a the cache lifetime
     *
     * @access    public
     *
     * @param  string
     * @param  string
     * @param  boolean
     *
     * @return    void
     */
    public function set_cache($minutes = 0)
    {
        $this->cache_lifetime = $minutes;

        return $this;
    }


    /**
     * enable_parser
     * Should be parser be used or the view files just loaded normally?
     *
     * @access    public
     *
     * @param  string  $view
     *
     * @return    void
     */
    public function enable_parser($bool)
    {
        $this->_parser_enabled = $bool;

        return $this;
    }

    /**
     * enable_parser_body
     * Should be parser be used or the body view files just loaded normally?
     *
     * @access    public
     *
     * @param  string  $view
     *
     * @return    void
     */
    public function enable_parser_body($bool)
    {
        $this->_parser_body_enabled = $bool;

        return $this;
    }

    /**
     * theme_locations
     * List the locations where themes may be stored
     *
     * @access    public
     *
     * @param  string  $view
     *
     * @return    array
     */
    public function theme_locations()
    {
        return $this->_theme_locations;
    }

    /**
     * add_theme_location
     * Set another location for themes to be looked in
     *
     * @access    public
     *
     * @param  string  $view
     *
     * @return    array
     */
    public function add_theme_location($location)
    {
        $this->_theme_locations[] = $location;
    }

    /**
     * theme_exists
     * Check if a theme exists
     *
     * @access    public
     *
     * @param  string  $view
     *
     * @return    array
     */
    public function theme_exists($theme = null)
    {
        $theme or $theme = $this->_theme;

        foreach ($this->_theme_locations as $location) {
            if (is_dir($location . $theme)) {
                return true;
            }
        }

        return false;
    }

    /**
     * get_layouts
     * Get all current layouts (if using a theme you'll get a list of theme layouts)
     *
     * @access    public
     *
     * @param  string  $view
     *
     * @return    array
     */
    public function get_layouts()
    {
        $layouts = array();

        foreach (glob(self::_find_view_folder() . 'layouts/*.*') as $layout) {
            $layouts[] = pathinfo($layout, PATHINFO_BASENAME);
        }

        return $layouts;
    }


    /**
     * get_layouts
     * Get all current layouts (if using a theme you'll get a list of theme layouts)
     *
     * @access    public
     *
     * @param  string  $view
     *
     * @return    array
     */
    public function get_theme_layouts($theme = null)
    {
        $theme or $theme = $this->_theme;

        $layouts = array();

        foreach ($this->_theme_locations as $location) {
            // Get special web layouts
            if (is_dir($location . $theme . '/views/web/layouts/')) {
                foreach (glob($location . $theme . '/views/web/layouts/*.*') as $layout) {
                    $layouts[] = pathinfo($layout, PATHINFO_BASENAME);
                }
                break;
            }

            // So there are no web layouts, assume all layouts are web layouts
            if (is_dir($location . $theme . '/views/layouts/')) {
                foreach (glob($location . $theme . '/views/layouts/*.*') as $layout) {
                    $layouts[] = pathinfo($layout, PATHINFO_BASENAME);
                }
                break;
            }
        }

        return $layouts;
    }

    /**
     * layout_exists
     * Check if a theme layout exists
     *
     * @access    public
     *
     * @param  string  $view
     *
     * @return    array
     */
    public function layout_exists($layout)
    {
        // If there is a theme, check it exists in there
        if (! empty($this->_theme) and in_array($layout, self::get_theme_layouts())) {
            return true;
        }

        // Otherwise look in the normal places
        return file_exists(self::_find_view_folder() . 'layouts/' . $layout . self::_ext($layout));
    }

    /**
     * load_view
     * Load views from theme paths if they exist.
     *
     * @access    public
     *
     * @param  string  $view
     * @param  mixed   $data
     *
     * @return    array
     */
    public function load_view($view, $data = array())
    {
        return $this->_find_view($view, (array)$data);
    }

    // find layout files, they could be mobile or web
    private function _find_view_folder()
    {
        if ($this->_ci->load->get_var('template_views')) {
            return $this->_ci->load->get_var('template_views');
        }

        // Base view folder
        $view_folder = APPPATH . 'views/';

        // Using a theme? Put the theme path in before the view folder
        if (! empty($this->_theme)) {
            $view_folder = $this->_theme_path . 'views/';
        }

        // Would they like the mobile version?
        if ($this->_is_mobile === true and is_dir($view_folder . 'mobile/')) {
            // Use mobile as the base location for views
            $view_folder .= 'mobile/';
        } // Use the web version
        elseif (is_dir($view_folder . 'web/')) {
            $view_folder .= 'web/';
        }

        // Things like views/admin/web/view admin = subdir
        if ($this->_layout_subdir) {
            $view_folder .= $this->_layout_subdir . '/';
        }

        // If using themes store this for later, available to all views
        $this->_ci->load->vars('template_views', $view_folder);

        return $view_folder;
    }

    // A module view file can be overriden in a theme
    private function _find_view($view, array $data, $parse_view = true)
    {
        // Only bother looking in themes if there is a theme

        if (! empty($this->_theme)) {
            foreach ($this->_theme_locations as $location) {
                $theme_views = array(
                    $this->_theme . '/modules/' . $this->_module . '/' . $view,
                    $this->_theme . '/views/' . $view
                );

                foreach ($theme_views as $theme_view) {
                    if (file_exists($location . $theme_view . self::_ext($theme_view))) {
                        return self::_load_view($theme_view, $this->_data + $data, $parse_view, $location);
                    }
                }
            }
        }

        // Not found it yet? Just load, its either in the module or root view
        return self::_load_view($view, $this->_data + $data, $parse_view);
    }

    private function _load_view($view, array $data, $parse_view = true, $override_view_path = null)
    {
        // Sevear hackery to load views from custom places AND maintain compatibility with Modular Extensions
        if ($override_view_path !== null) {
            if ($this->_parser_enabled === true and $parse_view === true) {
                // Load content and pass through the parser
                $content = $this->_ci->parser->parse_string(
                    $this->_ci->load->file(
                        $override_view_path . $view . self::_ext($view),
                        true
                    ), $data, true
                );
            } else {
                $this->_ci->load->vars($data);

                // Load it directly, bypassing $this->load->view() as ME resets _ci_view
                $content = $this->_ci->load->file(
                    $override_view_path . $view . self::_ext($view),
                    true
                );
            }
        } // Can just run as usual
        else {
            // Grab the content of the view (parsed or loaded)
            $content = ($this->_parser_enabled === true and $parse_view === true)

                // Parse that bad boy
                ? $this->_ci->parser->parse($view, $data, true)

                // None of that fancy stuff for me!
                : $this->_ci->load->view($view, $data, true);
        }

        return $content;
    }

    private function _guess_title()
    {
        $this->_ci->load->helper('inflector');

        // Obviously no title, lets get making one
        $title_parts = array();

        // If the method is something other than index, use that
        if ($this->_method != 'index') {
            $title_parts[] = $this->_method;
        }

        // Make sure controller name is not the same as the method name
        if (! in_array($this->_controller, $title_parts)) {
            $title_parts[] = $this->_controller;
        }

        // Is there a module? Make sure it is not named the same as the method or controller
        if (! empty($this->_module) and ! in_array($this->_module, $title_parts)) {
            $title_parts[] = $this->_module;
        }

        // Glue the title pieces together using the title separator setting
        $title = humanize(implode($this->_title_separator, $title_parts));

        return $title;
    }

    private function _ext($file)
    {
        return pathinfo($file, PATHINFO_EXTENSION) ? '' : '.php';
    }
}

// END Template class
