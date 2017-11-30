<?php

namespace Includes\Modules\Social;

/**
 * Social Links Options Page
 */

// Exit if accessed directly.
if (! defined('ABSPATH')) {
    exit;
}
class SocialSettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
    }

    public function createPage()
    {
        add_action('admin_menu', [ $this, 'add_menu_item' ]);
        add_action('admin_init', [ $this, 'page_fields_init' ]);
    }

    /**
     * Add options page menu item
     */
    public function add_menu_item()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin',
            'Social Media Links',
            'manage_options',
            'social-setting-admin',
            [ $this, 'create_admin_page' ]
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option('social_option_name'); ?>
		<div class="wrap">
			<h1>Social Media Settings Settings</h1>
			<form class="form form-horizontal" method="post" action="options.php">
				<?php
                // This prints out all hidden setting fields
                settings_fields('social_option_group');
        do_settings_sections('social-setting-admin');
        submit_button(); ?>
			</form>
		</div>
		<?php
    }

    /**
     * Register and add settings
     */
    public function page_fields_init()
    {
        register_setting(
            'social_option_group', // Option group
            'social_option_name', // Option name
            [ $this, 'sanitize' ] // Sanitize
        );

        /*add_settings_section(
            'setting_display_section_id', // ID
            'Display Options', // Title
            array( $this, 'print_display_section_info' ), // Callback
            'social-setting-admin' // Page
        ); */

        add_settings_section(
            'setting_section_id', // ID
            'Manage Social Media Links', // Title
            [ $this, 'print_link_section_info' ], // Callback
            'social-setting-admin' // Page
        );

        //Configure fields
        add_settings_field(
            'facebook', // ID
            'Facebook', // Title
            [ $this, 'facebook_callback' ], // Callback
            'social-setting-admin', // Page
            'setting_section_id' // Section
        );

        add_settings_field(
            'youtube', // ID
            'YouTube', // Title
            [ $this, 'youtube_callback' ], // Callback
            'social-setting-admin', // Page
            'setting_section_id' // Section
        );

        add_settings_field(
            'linkedin', // ID
            'LinkedIn', // Title
            [ $this, 'linkedin_callback' ], // Callback
            'social-setting-admin', // Page
            'setting_section_id' // Section
        );

        add_settings_field(
            'twitter', // ID
            'Twitter', // Title
            [ $this, 'twitter_callback' ], // Callback
            'social-setting-admin', // Page
            'setting_section_id' // Section
        );

        add_settings_field(
            'googleplus', // ID
            'Google+', // Title
            [ $this, 'googleplus_callback' ], // Callback
            'social-setting-admin', // Page
            'setting_section_id' // Section
        );

        add_settings_field(
            'vimeo', // ID
            'Vimeo', // Title
            [ $this, 'vimeo_callback' ], // Callback
            'social-setting-admin', // Page
            'setting_section_id' // Section
        );

        add_settings_field(
            'atom', // ID
            'Atom/Rss', // Title
            [ $this, 'atom_callback' ], // Callback
            'social-setting-admin', // Page
            'setting_section_id' // Section
        );

        add_settings_field(
            'blogger', // ID
            'Blogger', // Title
            [ $this, 'blogger_callback' ], // Callback
            'social-setting-admin', // Page
            'setting_section_id' // Section
        );

        add_settings_field(
            'instagram', // ID
            'Instagram', // Title
            [ $this, 'instagram_callback' ], // Callback
            'social-setting-admin', // Page
            'setting_section_id' // Section
        );

        add_settings_field(
            'pinterest', // ID
            'Pinterest', // Title
            [ $this, 'pinterest_callback' ], // Callback
            'social-setting-admin', // Page
            'setting_section_id' // Section
        );

        add_settings_field(
            'ios', // ID
            'Apple/iOS', // Title
            [ $this, 'ios_callback' ], // Callback
            'social-setting-admin', // Page
            'setting_section_id' // Section
        );

        add_settings_field(
            'android', // ID
            'Android', // Title
            [ $this, 'android_callback' ], // Callback
            'social-setting-admin', // Page
            'setting_section_id' // Section
        );

        //display options
        /*add_settings_field(
            'size', // ID
            'Icon Size', // Title
            array( $this, 'size_callback' ), // Callback
            'social-setting-admin', // Page
            'setting_display_section_id' // Section
        );

        add_settings_field(
            'color', // ID
            'Icon Color', // Title
            array( $this, 'color_callback' ), // Callback
            'social-setting-admin', // Page
            'setting_display_section_id' // Section
        );
        */
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize($input)
    {
        $new_input = [];
        if (isset($input['facebook'])) {
            $new_input['facebook'] = sanitize_text_field($input['facebook']);
        }

        if (isset($input['youtube'])) {
            $new_input['youtube'] = sanitize_text_field($input['youtube']);
        }

        if (isset($input['linkedin'])) {
            $new_input['linkedin'] = sanitize_text_field($input['linkedin']);
        }

        if (isset($input['twitter'])) {
            $new_input['twitter'] = sanitize_text_field($input['twitter']);
        }

        if (isset($input['googleplus'])) {
            $new_input['googleplus'] = sanitize_text_field($input['googleplus']);
        }

        if (isset($input['vimeo'])) {
            $new_input['vimeo'] = sanitize_text_field($input['vimeo']);
        }

        if (isset($input['atom'])) {
            $new_input['atom'] = sanitize_text_field($input['atom']);
        }

        if (isset($input['blogger'])) {
            $new_input['blogger'] = sanitize_text_field($input['blogger']);
        }

        if (isset($input['instagram'])) {
            $new_input['instagram'] = sanitize_text_field($input['instagram']);
        }

        if (isset($input['pinterest'])) {
            $new_input['pinterest'] = sanitize_text_field($input['pinterest']);
        }

        if (isset($input['ios'])) {
            $new_input['ios'] = sanitize_text_field($input['ios']);
        }

        if (isset($input['android'])) {
            $new_input['android'] = sanitize_text_field($input['android']);
        }

        /*if( isset( $input['size'] ) )
            $new_input['size'] = sanitize_text_field( $input['size'] );

        if( isset( $input['color'] ) )
            $new_input['color'] = sanitize_text_field( $input['color'] );*/

        return $new_input;
    }

    // Print the Section text
    /*public function print_display_section_info(){
        print 'Copy the entire URL to your profile page in the blanks below. Simply leave unused social media platforms blank.';
    }*/

    // Print the Section text
    public function print_link_section_info()
    {
        print '<p>Copy the entire URL to your profile page in the blanks below. Simply leave unused social media platforms blank.</p>
		<p>PHP function usage: getSocialLinks(); returns an array of platform ids and links.</p>';
    }

    // Get the settings option array and print the values
    public function facebook_callback()
    {
        printf(
            '<input class="form-control" type="text" id="facebook" name="social_option_name[facebook]" value="%s" />',
            isset($this->options['facebook']) ? esc_attr($this->options['facebook']) : ''
        );
    }

    public function twitter_callback()
    {
        printf(
            '<input class="form-control" type="text" id="twitter" name="social_option_name[twitter]" value="%s" />',
            isset($this->options['twitter']) ? esc_attr($this->options['twitter']) : ''
        );
    }

    public function googleplus_callback()
    {
        printf(
            '<input class="form-control" type="text" id="googleplus" name="social_option_name[googleplus]" value="%s" />',
            isset($this->options['googleplus']) ? esc_attr($this->options['googleplus']) : ''
        );
    }

    public function youtube_callback()
    {
        printf(
            '<input class="form-control" type="text" id="youtube" name="social_option_name[youtube]" value="%s" />',
            isset($this->options['youtube']) ? esc_attr($this->options['youtube']) : ''
        );
    }

    public function vimeo_callback()
    {
        printf(
            '<input class="form-control" type="text" id="vimeo" name="social_option_name[vimeo]" value="%s" />',
            isset($this->options['vimeo']) ? esc_attr($this->options['vimeo']) : ''
        );
    }

    public function atom_callback()
    {
        printf(
            '<input class="form-control" type="text" id="atom" name="social_option_name[atom]" value="%s" />',
            isset($this->options['atom']) ? esc_attr($this->options['atom']) : ''
        );
    }

    public function blogger_callback()
    {
        printf(
            '<input class="form-control" type="text" id="blogger" name="social_option_name[blogger]" value="%s" />',
            isset($this->options['blogger']) ? esc_attr($this->options['blogger']) : ''
        );
    }

    public function linkedin_callback()
    {
        printf(
            '<input class="form-control" type="text" id="linkedin" name="social_option_name[linkedin]" value="%s" />',
            isset($this->options['linkedin']) ? esc_attr($this->options['linkedin']) : ''
        );
    }

    public function instagram_callback()
    {
        printf(
            '<input class="form-control" type="text" id="instagram" name="social_option_name[instagram]" value="%s" />',
            isset($this->options['instagram']) ? esc_attr($this->options['instagram']) : ''
        );
    }

    public function pinterest_callback()
    {
        printf(
            '<input class="form-control" type="text" id="pinterest" name="social_option_name[pinterest]" value="%s" />',
            isset($this->options['pinterest']) ? esc_attr($this->options['pinterest']) : ''
        );
    }

    public function ios_callback()
    {
        printf(
            '<input class="form-control" type="text" id="ios" name="social_option_name[ios]" value="%s" />',
            isset($this->options['ios']) ? esc_attr($this->options['ios']) : ''
        );
    }

    public function android_callback()
    {
        printf(
            '<input class="form-control" type="text" id="android" name="social_option_name[android]" value="%s" />',
            isset($this->options['android']) ? esc_attr($this->options['android']) : ''
        );
    }

    public function getSocialLinks($format = 'svg', $shape = 'square', $data = '')
    {
        $supportedPlatforms = ($data != '' ? $data : get_option('social_option_name'));

        $socialArray = [];
        if (is_array($supportedPlatforms)) {
            foreach ($supportedPlatforms as $plat => $platLink) {
                if ($platLink != '') {
                    $iconUrl = get_template_directory() . '/inc/modules/Social/icons/'.$format.'/'.$shape.'/'.$plat.'.svg';
                    $iconData = file_get_contents(wp_normalize_path($iconUrl));
                    $socialArray[ $plat ][0] = $platLink;
                    $socialArray[ $plat ][1] = $iconData;
                }
            }
        }
        return $socialArray;
    }
}
