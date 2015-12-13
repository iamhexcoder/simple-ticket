<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Simple_Ticket
 * @subpackage Simple_Ticket/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Simple_Ticket
 * @subpackage Simple_Ticket/public
 * @author     Daniel Olson <emaildano@gmail.com>
 */
class Simple_Ticket_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $simple_ticket    The ID of this plugin.
	 */
	private $simple_ticket;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $simple_ticket       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $simple_ticket, $version ) {

		$this->simple_ticket = $simple_ticket;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Simple_Ticket_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Simple_Ticket_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if(is_admin_bar_showing()) {
			wp_enqueue_style( $this->simple_ticket, plugin_dir_url( __DIR__ ) . 'assets/dist/css/simple-ticket.public.css');
		}

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Simple_Ticket_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Simple_Ticket_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if(is_admin_bar_showing()) {
			wp_enqueue_script( $this->simple_ticket, plugin_dir_url( __DIR__ ) . 'assets/dist/js/simple-ticket.public.js', array( 'jquery' ), null, false );
		}

	}

}
