<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Simple_Ticket
 * @subpackage Simple_Ticket/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Simple_Ticket
 * @subpackage Simple_Ticket/admin
 * @author     Daniel Olson <emaildano@gmail.com>
 */
class Simple_Ticket_Admin {

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
	 * @param      string    $simple_ticket       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $simple_ticket, $version ) {

		$this->simple_ticket = $simple_ticket;
		$this->version = $version;

		// add_action( 'admin_bar_menu', array($this, 'st_admin_bar'), 1000 );
		add_action( 'admin_bar_menu', array($this, 'st_admin_bar'), 999 );
		add_action('wp_footer', array($this, 'st_footer_form') );

	}

	/**
	 * Register the stylesheets for the admin area.
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
	 * Register the JavaScript for the admin area.
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

	/**
	 * Create admin bar nodes
	 */
	public function st_admin_bar( $wp_admin_bar ) {
		// Parent Form Node
		$wp_admin_bar->add_node(
			array(
				'id' => 'simple-ticket-menu-item',
				'title' => __( 'j2 Support', 'textdomain' ),
				'href' => false,
				'meta'  => array( 'class' => 'simple-ticket-links' )
			)
		);

		$wp_admin_bar->add_node(
			array(
				'id' => 'simple-ticket-menu-call-form',
				'title' => __( 'Submit a Ticket', 'textdomain' ),
				'parent' => 'simple-ticket-menu-item',
				'href' => '#st-submit-form',
				'meta'  => array( 'class' => 'simple-ticket-link-form-toggle' )
			)
		);
	}

	/**
	 * Create form HTML and inject into footer
	 */
	public function st_footer_form() {

		if(is_admin_bar_showing()) {
			$url = 'http://' . $_SERVER['HTTP_HOST']  . $_SERVER['REQUEST_URI'];
			global $current_user;

			$html =		'<div id="st-submit-form" class="st-form-wrapper">';
			$html .=		'<p class="st-submit-form--url">Current URL: <span>' . $url . '</span></p>';
			$html .= 		'<form id="simple-ticket-form">';
			$html .= 			'<input type="hidden" name="simple-ticket-current-url" value="http://' . $url . '" />';
			$html .= 			'<input type="hidden" name="simple-ticket-user-name" value="' . $current_user->display_name . '" />';
			$html .= 			'<input type="hidden" name="simple-ticket-user-email" value="' . $current_user->user_email . '" />';
			$html .= 			'<label for="simple-ticket-description">Description</label>';
			$html .= 			'<textarea name="simple-ticket-description"></textarea>';
			$html .= 			'<label for="simple-ticket-screenshot">Upload Screenshot</label>';
			$html .= 			'<input type="file" name="simple-ticket-screenshot" accept="image/*">';
			$html .= 			'<input type="submit">';
			$html .= 		'</form>';
			$html .=	'</div>';

			echo $html;
		}

	}
}
