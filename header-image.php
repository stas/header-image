<?php
/*
Plugin Name: Header Image
Plugin URI: http://wordpress.org/extend/plugins/header-image/
Description: Add a header image from backend if theme doesn't have this feature
Version: 0.1
Author: sushkov
Author URI: http://wordpress.org/extend/plugins/header-image/
*/
?>
<?php
/*  Copyright 2011  Stas Suscov <stas@net.utcluj.ro>

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define( 'HEADER-IMAGE', '0.1' );

/**
 * Main Header-image Class
 */
class HeaderImage {
    /**
     * Static constructor
     */
    function init() {
        add_action( 'admin_init', array( __CLASS__, 'load_settings' ) );
        add_action( 'wp_head', array( __CLASS__, 'generate_css' ), 100 );
    }

    /**
     * Register with settings API
     */
    function load_settings() {
        add_settings_section(
            'header-image',
            _( 'Select one of the uploaded images to be placed in header' ),
            array( __CLASS__, 'section_description' ),
            'media'
        );
        add_settings_field(
            'header_image',
            _( 'The header image file' ),
            array( __CLASS__, 'section_field' ),
            'media',
            'header-image'
        );
        add_settings_field(
            'header_image_element',
            _( 'The header image css element' ),
            array( __CLASS__, 'css_field' ),
            'media',
            'header-image'
        );
        register_setting( 'media', 'header_image' );
        register_setting( 'media', 'header_image_element' );
    }

    /**
     * Output the field html
     */
    function section_field() {
        self::render( 'section_field' );
    }

    /**
     * Output the field html
     */
    function css_field() {
        self::render( 'css_field' );
    }

    /**
     * Helper to simplify access to header image value
     */
    function generate_css() {
      $img_css = get_option( 'header_image_element', null );
      // Return nothing if no CSS element was set
      if ( !$img_css )
        return;

      $img_src = wp_get_attachment_url( get_option( 'header_image', null ) );
      printf(
          "<style type=\"text/css\">%s{ background: url('%s') no-repeat 0 0 !important; }</style>",
          $img_css,
          $img_src
      );
    }
    
    /**
     * i18n
     */
    function localization() {
        load_plugin_textdomain( 'header-image', false, basename( dirname( __FILE__ ) ) . '/languages' );
    }
    
    /**
     * render( $name, $vars = null, $echo = true )
     *
     * Helper to load and render templates easily
     * @param String $name, the name of the template
     * @param Mixed $vars, some variables you want to pass to the template
     * @param Boolean $echo, to echo the results or return as data
     * @return String $data, the resulted data if $echo is `false`
     */
    function render( $name, $vars = null, $echo = true ) {
        ob_start();
        if( !empty( $vars ) )
            extract( $vars );
        
        include dirname( __FILE__ ) . '/templates/' . $name . '.php';
        
        $data = ob_get_clean();
        
        if( $echo )
            echo $data;
        else
            return $data;
    }
}
HeaderImage::init();
?>
