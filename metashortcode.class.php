<?php

class metashortcode {

    /**
     * Initializes actions and filters
     */
    function __construct() {
        $enabled = get_option('metashortcode_enabled')  && true;
        if ($enabled) {
            add_shortcode('msc', 'metashortcode_shortcode');
            if (get_option('metashortcode_altshortcodes_enabled') && !is_admin()) {
                add_filter('the_content', 'metashortcode_preprocess_altshortcodes');
            }
        }
        add_action('admin_menu', [$this, 'create_plugin_settings_page']);
        add_action('admin_init', [$this, 'setup_sections']);
        add_action('admin_init', [$this, 'setup_fields']);
        add_action('admin_init', [$this, 'metashortcode_scripts']);
    }

    /**
     * Sets up the admin page
     */
    function create_plugin_settings_page() {
        $page_title = 'MetaShortcode Settings Page';
        $menu_title = 'MetaShortcode';
        $capability = 'manage_options';
        $slug = 'metashortcode_settings';
        $callback = [$this, 'plugin_settings_page_content'];
        $icon = 'dashicons-admin-plugins';
        $position = 301;

        add_menu_page($page_title, $menu_title, $capability, $slug, $callback, $icon, $position);
    }

    /**
     * Basic page setup
     */
    function plugin_settings_page_content() {
        ?>
        <div class="wrap">
            <h2>MetaShortcode Settings</h2><?php
                if (isset($_GET['settings-updated']) && $_GET['settings-updated']) {
                    self::admin_notice();
                }
                ?>
            <form method="POST" action="options.php">
        <?php
        settings_fields('metashortcode_fields');
        do_settings_sections('metashortcode_fields');
        submit_button();
        ?>
            </form>
        </div> <?php
    }

    /**
     * Notification setup
     */
    function admin_notice() {
        ?>
        <div class="notice notice-success is-dismissible">
            <p>Your settings have been updated!</p>
        </div><?php
    }

    /**
     * Add page sections
     */
    function setup_sections() {
        add_settings_section('main_section', 'General Settings', [$this, 'section_callback'], 'metashortcode_fields');
    }

    /**
     * Page section router
     * @param array $arguments
     */
    function section_callback($arguments) {
        switch ($arguments['id']) {
            case 'main_section':
                echo 'Configure the MetaShortcode behavior here. Please refer to the official plugin documentation page for more information.';
                break;
        }
    }

    /**
     * Setup the fields
     */
    function setup_fields() {
        $fields = [
            [
                'uid' => 'metashortcode_enabled',
                'label' => 'Enable',
                'section' => 'main_section',
                'type' => 'checkbox',
                'options' => ['enabled' => 'Enabled'],
                'default' => [],
                'supplemental' => 'Uncheck the box to disable the plugin.'
            ],
            [
                'uid' => 'metashortcode_altshortcodes_enabled',
                'label' => 'Enable nested shortcode resolution',
                'section' => 'main_section',
                'type' => 'checkbox',
                'options' => ['enabled' => 'Enabled'],
                'default' => [],
                'supplemental' => 'Check the box to enable the resolution of alternative syntax (curly braces) shortcodes. Leave this box unchecked unless you have a specific implementation issue: efer to the documentation for more information.'
            ]
        ];
        foreach ($fields as $field) {
            add_settings_field($field['uid'], $field['label'], [$this, 'field_callback'], 'metashortcode_fields', $field['section'], $field);
            register_setting('metashortcode_fields', $field['uid']);
        }
    }

    /**
     * Field templates
     * @param array $arguments An array of properties to build fields from
     */
    function field_callback($arguments) {

        $value = get_option($arguments['uid']);

        if (!$value) {
            $value = $arguments['default'];
        }

        switch ($arguments['type']) {
            case 'text':
            case 'password':
            case 'number':
                printf('<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value);
                break;
            case 'textarea':
                printf('<textarea name="%1$s" id="%1$s" placeholder="%2$s" rows="5" cols="50">%3$s</textarea>', $arguments['uid'], $arguments['placeholder'], $value);
                break;
            case 'select':
            case 'multiselect':
                if (!empty($arguments['options']) && is_array($arguments['options'])) {
                    $attributes = '';
                    $options_markup = '';
                    foreach ($arguments['options'] as $key => $label) {
                        $options_markup .= sprintf('<option value="%s" %s>%s</option>', $key, selected($value[array_search($key, $value, true)], $key, false), $label);
                    }
                    if ($arguments['type'] === 'multiselect') {
                        $attributes = ' multiple="multiple" ';
                    }
                    printf('<select name="%1$s[]" id="%1$s" %2$s>%3$s</select>', $arguments['uid'], $attributes, $options_markup);
                }
                break;
            case 'radio':
            case 'checkbox':
                if (!empty($arguments['options']) && is_array($arguments['options'])) {
                    $options_markup = '';
                    $iterator = 0;
                    foreach ($arguments['options'] as $key => $label) {
                        $iterator++;
                        $options_markup .= sprintf('<label for="%1$s_%6$s"><input id="%1$s_%6$s" name="%1$s[]" type="%2$s" value="%3$s" %4$s /> %5$s</label><br/>', $arguments['uid'], $arguments['type'], $key, checked($value[array_search($key, $value, true)], $key, false), $label, $iterator);
                    }
                    printf('<fieldset>%s</fieldset>', $options_markup);
                }
                break;
        }

        if ($helper = $arguments['helper']) {
            printf('<span class="helper"> %s</span>', $helper);
        }

        if ($supplemental = $arguments['supplemental']) {
            printf('<p class="description">%s</p>', $supplemental);
        }
    }

    /**
     * Script injector
     */
    function metashortcode_scripts() {
        //wp_register_script('metashortcode-admin', plugins_url('js/metashortcode.admin.js', __FILE__), filemtime(plugin_dir_path(__FILE__) . 'js/metashortcode.admin.js'), true);
        wp_register_script( 'metashortcode-admin',  plugins_url('js/metashortcode.admin.js', __FILE__ ),"1.0", true);
        wp_enqueue_script('metashortcode-admin');
    }

}
