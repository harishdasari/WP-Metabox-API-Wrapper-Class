<?php
/*
Name: WordPress Metabox API Wrapper Class
URI: http://github.com/harishdasari
Description: A PHP Library for creating WordPress Custom Metabox Options using WordPress Metadata API
Author: Harish Dasari
Author URI: http://twitter.com/harishdasari
Version: 1.0
License: GNU General Public License v2.0 or later
License URI: http://www.opensource.org/licenses/gpl-license.php
*/

/*  Copyright 2014 Harish Dasari  (email : harishdasari@outlook.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*=================================================================================
	WordPress Metabox API Wrapper Class
 =================================================================================*/

require_once( 'class-hd-html-helper.php' );

if ( ! class_exists( 'HD_WP_Metabox_API' ) ) :
/**
 * WordPress Metabox API Wrapper Class
 *
 * @version 1.0
 * @author  Harish Dasari
 * @link    http://github.com/harishdasari
 */
class HD_WP_Metabox_API {

	/**
	 * Holds Options for Menu Page
	 * @var array
	 */
	var $options          = array();

	/**
	 * Holds Settings fields data
	 * @var array
	 */
	var $fields           = array();

	/**
	 * Supported input field types to white label while saving
	 * @var array
	 */
	var $supported_fields = array();

	/**
	 * Holds instance of HD_HTML_Helper class
	 * @var object
	 */
	var $html_helper;

	/**
	 * Holds Current Folder Path
	 * @var string
	 */
	var $dir_path;

	/**
	 * Holds Current Folder URI
	 * @var string
	 */
	var $dir_uri;

	/**
	 * Constructor
	 *
	 * @param array $options
	 * @param array $fields
	 * @return null
	 */
	function __construct( $options = array(), $fields = array() ) {

		// Set directory path
		$this->dir_path = str_replace( '\\', '/', dirname( __FILE__ ) );

		// Set directory uri
		$this->dir_uri  = trailingslashit( home_url() ) . str_replace( str_replace( '\\', '/', ABSPATH ), '', $this->dir_path );

		// Default page options
		$options_default = array(
			'metabox_id'    => '',
			'metabox_title' => '',
			'post_type'     => '',
			'context'       => 'normal',
			'priority'      => 'high',
		);

		$this->options = wp_parse_args( $options, $options_default );

		extract( $this->options );

		// Titles and slugs should not be empty
		if ( empty( $metabox_id ) || empty( $metabox_title ) || empty( $post_type ) )
			return false;

		// Set Input fields
		$this->fields = (array) $fields;

		$field_default = array(
			'title'    => '',
			'type'     => '',
			'desc'     => '',
			'choices'  => array(),
			'multiple' => false,
			'sanit'    => '',
		);

		// to eliminate warning we need to set default empty values.
		foreach ( $this->fields as $key => $field )
			$this->fields[ $key ] = wp_parse_args( $field, $field_default );

		// Set list of input field types. we are white labeling these field types while saving meta.
		$this->supported_fields = (array) apply_filters( 'hd_metabox_api_supported_fields', array( 'text', 'textarea', 'radio', 'checkbox', 'select', 'multicheck', 'upload', 'color', 'editor' ) );

		$this->html_helper = class_exists( 'HD_HTML_Helper' ) ? new HD_HTML_Helper : false;

		add_action( 'add_meta_boxes', array( $this, 'register_metabox' ) );
		add_action( 'save_post', array( $this, 'save_metabox' ), 10, 2 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles_scripts' ) );

	}

	/**
	 * Enqueue Styles and Scripts
	 *
	 * @param  string $hook_suffix
	 * @return null
	 */
	function enqueue_styles_scripts( $hook_suffix ) {

		if ( ! in_array( $hook_suffix , array( 'post.php', 'post-new.php' ) ) )
			return;

		if ( ! in_array( get_post_type(), (array) $this->options['post_type'] ) )
			return;

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'hd-html-helper', $this->dir_uri . '/js/admin.js', array( 'jquery', 'wp-color-picker' ), null, true );

	}

	/**
	 * Register Metabox
	 *
	 * @return mull
	 */
	function register_metabox() {

		extract( $this->options );

		foreach ( (array) $post_type as $type )
			if ( post_type_exists( $type ) )
				add_meta_box( $metabox_id, esc_html( $metabox_title ), array( $this, 'display_metabox' ), $type, $context, $priority );

	}

	/**
	 * Print Metabox Content
	 *
	 * @return null
	 */
	function display_metabox( $post ) {

		foreach ( (array) $this->fields as $meta_key => $meta_field ) {
			$this->fields[ $meta_key ]['id'] = $meta_key;
			$this->fields[ $meta_key ]['value'] = get_post_meta( $post->ID, $meta_key, true );
		}

		wp_nonce_field( $this->options['metabox_id'] . '_' . $post->ID, $this->options['metabox_id'] . '_' . $post->ID . '_nonce' );

		do_action( 'hd_metabox_api_metabox_before', $this->options, $this->fields );

		echo '<div class="hd-metabox-inner ' . sanitize_html_class( $this->options['metabox_id'] ) . '">';

		$this->html_helper->display_form_table( $this->fields, true );

		echo '</div>';

		do_action( 'hd_metabox_api_metabox_after', $this->options, $this->fields );

	}

	/**
	 * Save Metabox
	 *
	 * @param  int      $post_id Post ID
	 * @param  object   $post    Post Data Object
	 * @return null|int
	 */
	function save_metabox( $post_id, $post ) {

		extract( $this->options );

		if ( ! isset( $_POST[ $metabox_id . '_' . $post_id . '_nonce' ] ) || ! wp_verify_nonce( $_POST[ $metabox_id . '_' . $post_id . '_nonce' ], $metabox_id . '_' . $post_id ) )
			return $post_id;

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;

		$post_type = get_post_type_object( $post->post_type );

		if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) )
			return $post_id;

		foreach ( (array) $this->fields as $meta_key => $meta_field ) {

			$meta_field['id'] = $meta_key;

			// White label field types
			if ( ! in_array( $meta_field['type'], $this->supported_fields ) )
				continue;

			$post_value = isset( $_POST[ $meta_key ] ) ? $_POST[ $meta_key ] : '';

			$new_value = $this->sanitize_value( $post_value, $meta_field );

			$old_value = get_post_meta( $post_id, $meta_key, true );

			if ( ( $new_value || 0 == $new_value ) && $new_value != $old_value )
				update_post_meta( $post_id, $meta_key, $new_value, $old_value );
			elseif ( '' == $new_value && $old_value )
				delete_post_meta( $post_id, $meta_key, $new_value );

		}

		do_action( 'hd_metabox_api_save_metabox', $post_id, $post );

	}

	/**
	 * Sanitize Metabox Value
	 *
	 * @param  mixed $new_value Submitted new value
	 * @param  array $field     Meta field options
	 * @return mixed            Sanitized value
	 */
	function sanitize_value( $new_value, $field ) {

		if ( ! isset( $field['sanit'] ) )
			$field['sanit'] = '';

		switch ( $field['sanit'] ) {

			case 'int' :
				return is_array( $new_value ) ? array_map( 'intval', $new_value ) : intval( $new_value );
				break;

			case 'absint' :
				return is_array( $new_value ) ? array_map( 'absint', $new_value ) : absint( $new_value );
				break;

			case 'email' :
				return is_array( $new_value ) ? array_map( 'sanitize_email', $new_value ) : sanitize_email( $new_value );
				break;

			case 'url' :
				return is_array( $new_value ) ? array_map( 'esc_url_raw', $new_value ) : esc_url_raw( $new_value );
				break;

			case 'bool' :
				return (bool) $new_value;
				break;

			case 'color' :
				return $this->sanitize_hex_color( $new_value );
				break;

			case 'html' :
				if ( current_user_can( 'unfiltered_html' ) )
					return is_array( $new_value ) ? array_map( 'wp_kses_post', $new_value ) : wp_kses_post( $new_value );
				else
					return is_array( $new_value ) ? array_map( 'wp_strip_all_tags', $new_value ) : wp_strip_all_tags( $new_value );
				break;

			case 'nohtml' :
				return is_array( $new_value ) ? array_map( 'wp_strip_all_tags', $new_value ) : wp_strip_all_tags( $new_value );
				break;

			default :
				return apply_filters( 'hd_metabox_api_sanitize_option', $new_value, $field, $setting );
				break;

		}

	}

	/**
	 * Sanitize Hex Color (taken from WP Core)
	 *
	 * @param  string $color Hex Color
	 * @return mixed         Sanitized Hex Color or null
	 */
	function sanitize_hex_color( $color ) {

		if ( '' === $color )
			return '';

		if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) )
			return $color;

		return null;

	}

} // HD_WP_Metabox_API end

endif; // class_exists check