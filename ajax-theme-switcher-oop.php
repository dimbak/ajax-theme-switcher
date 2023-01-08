<?php
/**
 * Plugin Name:       AJAX theme switcher OOP
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Allows you to change themes from the topbar
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            DB
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       ajax-theme-switcher-oop
 * Domain Path:       /languages
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation. You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */



if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

Class Att {


	public function __construct( ) {
		add_action( 'init', array( $this, 'att_textdomain' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'att_admin_enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'att_enqueue_scripts' ) );
		add_action( 'admin_bar_menu', array( $this, 'att_toolbar_quick_menu'),999 );
		add_action( 'wp_ajax_att_action', array( $this, 'att_action_callback' ) );
		add_action( 'wp_ajax_nopriv_att_action', array( $this, 'att_action_callback' ) );
	}

	function att_textdomain() {
		load_plugin_textdomain( 'ajax-theme-switcher', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	function att_admin_enqueue_scripts() {

		wp_register_script( 'att-handle', plugin_dir_url( __FILE__ ) . 'js/ajax-theme-switcher.js', array( 'jquery' ), '1.0', true );

		wp_localize_script( 
			'att-handle', 
			'ajax_object', 
			array( 
				'ajaxurl'    => admin_url( 'admin-ajax.php' ),
				'ajax_nonce' => wp_create_nonce( 'my-special' ),
			) 
		);

		wp_enqueue_script( 'att-handle' );
	}

	function att_enqueue_scripts() {

		wp_register_script( 'att-handle', plugin_dir_url( __FILE__ ) . 'js/ajax-theme-switcher.js', array( 'jquery' ), '1.0', true );

		wp_localize_script(
			'att-handle',
			'ajax_object',
			array(
				'ajaxurl'    => admin_url( 'admin-ajax.php' ),
				'ajax_nonce' => wp_create_nonce( 'my-special' ),
			)
		);

		wp_enqueue_script( 'att-handle' );
	}

	function att_toolbar_quick_menu( $wp_admin_bar ) {

		$all_themes = wp_get_themes();

		$args = array(
			'id'    => 'quick_menu',
			'title' => __( 'Switch Theme', 'ajax-theme-switcher' ),
			'href'  => admin_url() . 'themes.php',
		);
		$wp_admin_bar->add_node( $args );

		foreach ( $all_themes as $keys => $values ) {
			$menu[] =
			array(
				'id'    => $keys,
				'title' => $keys,
				'href'  => '#',

				'parent' => 'quick_menu',
			);
		}

		foreach ( $menu as $args ) {
			$wp_admin_bar->add_node( $args );
		}
	}

	function att_action_callback() {
		check_ajax_referer( 'my-special', 'security' );
		switch_theme( $_POST[ 'theme' ] );
		die();
	}
}

$obj = new Att();
