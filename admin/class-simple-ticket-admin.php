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

	public $options;

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
		$this->options = get_option('simple_ticket_options');

		// Add buttons to admin bar
		add_action( 'admin_bar_menu', array($this, 'st_admin_bar'), 999 );
		add_action('wp_footer', array($this, 'st_footer_form') );

		// Options Page
		add_action('admin_menu', array($this, 'st_options') );
		add_action( 'admin_init', array( $this, 'st_settings_init' ) );

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
				'title' => __( 'j2 Support', 'textdomain' ),					  // TODO TK -- POT
				'href' => false,
				'meta'  => array( 'class' => 'simple-ticket-links' )
			)
		);

		$wp_admin_bar->add_node(
			array(
				'id' => 'simple-ticket-menu-call-form',
				'title' => __( 'Submit a Ticket', 'textdomain' ),				  // TODO TK -- POT
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





	/**
	 * OPTIONS MENU PAGE
	 *
	 * Add options page
	 */
	public function st_options() {

    add_submenu_page(
      'options-general.php',
      'Simple Ticket',
      'Simple Ticket',
      'manage_options',
      'simple-ticket',
      array( $this, 'st_options_page_render')
    );
	}





	/**
	 ****************************************************************************
	 * REGISTER SETTINGS
	 *
	 */
	public function st_settings_init() {

		$page = 'st_settings_page';

		register_setting( $page, 'simple_ticket_options' );
		$options_section = 'st_options';


		/****************************************************************************
     * Settings Sections
     *
     */

		// Options
		add_settings_section(
		  $options_section, __( 'Advanced Email Options', 'wordpress' ),	  // TODO TK -- POT
		  array( $this, 'st_ht_render'),
		  $page
		);



		/****************************************************************************
		 * Settings Fields
		 *
		 */

		// From Email Address
    add_settings_field(
      'st_from_email_address',
      __( 'From Email Address', 'wordpress' ),			// TODO TK -- POT
      array( $this, 'st_from_email_address_render'),
      $page, $options_section
    );

    // From Email Name
    add_settings_field(
      'st_from_email_name',
      __( 'From Email Name', 'wordpress' ),				  // TODO TK -- POT
      array( $this, 'st_from_email_name_render'),
      $page, $options_section
    );

    // Send Via Option
    add_settings_field(
      'st_email_via',
      __( 'Send email via', 'wordpress' ),				  // TODO TK -- POT
      array( $this, 'st_email_via_render'),
      $page, $options_section
    );

    // SMTP Options
    add_settings_field(
      'st_smtp_hostname',
      __( 'SMTP Hostname', 'wordpress' ),				  // TODO TK -- POT
      array( $this, 'st_smtp_hostname_render'),
      $page, $options_section
    );

    // SMTP Port
    add_settings_field(
      'st_smtp_port',
      __( 'SMTP Port', 'wordpress' ),				  // TODO TK -- POT
      array( $this, 'st_smtp_port_render'),
      $page, $options_section
    );

    // SMTP SSL
    add_settings_field(
      'st_ssl',
      __( 'SMTP Encryption', 'wordpress' ),				  // TODO TK -- POT
      array( $this, 'st_encryption_render'),
      $page, $options_section
    );



	}





	/**
	 ****************************************************************************
	 * RENDER FUNCTIONS
	 */

	/* Horizontal Rule */
	public function st_ht_render() {
		echo '<hr>';
	}


	/* Create Textfield for From Email Address */
	public function st_from_email_address_render() {
		$val = isset($this->options['from_email_address']) ? $this->options['from_email_address'] : '';

	  $html =		'<input name="simple_ticket_options[from_email_address]" type="text" value="' . $val . '" class="regular-text" />';
	  $html .= 	'<p class="description">';
	  $html .= 		__('You can specify the email address that emails should be sent from. If you leave this blank, the default email will be used.', 'wp_mail_smtp');		  // TODO TK -- POT
	  $html .=	'</p>';

	  echo $html;
	}

	/* Create Textfield for From Email Name */
	public function st_from_email_name_render() {
		$val = isset($this->options['from_email_name']) ? $this->options['from_email_name'] : '';

	  $html =		'<input name="simple_ticket_options[from_email_name]" type="text" value="' . $val . '" class="regular-text" />';
	  $html .= 	'<p class="description">';
	  $html .= 		__('You can specify the name that emails should be sent from. If you leave this blank, the emails will be sent from WordPress.', 'wp_mail_smtp');  // TODO TK -- POT
	  $html .=	'</p>';

	  echo $html;
	}

	/* SMTP Option */
	public function st_email_via_render() {
    if( !isset( $this->options['email_via'] ) ) $this->options['email_via'] = 'smtp';

    $html =		'<select name="simple_ticket_options[email_via]">';
    $html .= 		'<option value="smtp"' . selected( $this->options['email_via'], 'smtp', false) . '>SMTP</option>';
    $html .= 		'<option value="php"' . selected( $this->options['email_via'], 'php', false)
                . '>PHP mail() function</option>';
    $html .= 	'</select>';

    echo $html;
	}

	/* Create SMTP Hostname Field */
	public function st_smtp_hostname_render() {
		$val = isset($this->options['smtp_host']) ? $this->options['smtp_host'] : '';
	  echo '<input name="simple_ticket_options[smtp_host]" type="text" value="' . $val . '" class="regular-text" />';
	}

	/* Create SMTP Pass Field */
	public function st_smtp_port_render() {
		$val = isset($this->options['smtp_port']) ? $this->options['smtp_port'] : '';
	  echo '<input name="simple_ticket_options[smtp_port]" type="text" value="' . $val . '" />';
	}

	/* Create SMTP Pass Field */
	public function st_encryption_render() {
		$val = isset($this->options['smtp_encrypt']) ? $this->options['smtp_encrypt'] : '0';

		$html = 	'<label for="st-smtp-ssl">SSL</label>';
	  $html .= 	'<input type="radio" id="st-smpt-ssl" name="simple_ticket_options[smtp_encrypt]" value="ssl"' . checked( 'ssl', $val, false ) . '/>';
	  $html .= 	'<label for="st-smtp-tls">TLS</label>';
	  $html .= '<input type="radio" id="st-smpt-tls" name="simple_ticket_options[smtp_encrypt]" value="tls"' . checked( 'tls', $val, false ) . '/>';
	  $html .= 	'<label for="st-smpt-no-encrypt">No Encryption</label>';
	  $html .= '<input type="radio" id="st-smpt-no-encrypt" name="simple_ticket_options[smtp_encrypt]" value="no_encrypt"' . checked( 'no_encrypt', $val, false ) . '/>';

	  echo $html;
	}






	/**
	 * OPTIONS PAGE OPTIONS
	 */
	public function st_options_page_render() {
		?>
		<div class="wrap">
			<form action="options.php" method="post">
			  <?php
			    settings_fields( 'st_settings_page' );
			    do_settings_sections( 'st_settings_page' );
			    submit_button();
			  ?>
			</form>
		</div>
		<?php
	}



}

	/*

	<label for="mail_from_name"><?php _e('From Name', 'wp_mail_smtp'); ?></label>
	<input name="mail_from_name" type="text" id="mail_from_name" value="<?php print(get_option('mail_from_name')); ?>" size="40" class="regular-text" />
	<span class="description"><?php _e('You can specify the name that emails should be sent from. If you leave this blank, the emails will be sent from WordPress.', 'wp_mail_smtp'); ?></span></td>
	</tr>
	</table>







}






<div class="wrap">
<h2><?php _e('Advanced Email Options', 'wp_mail_smtp'); ?></h2>
<form method="post" action="options.php">
<?php wp_nonce_field('email-options'); ?>




<table class="optiontable form-table">
<tr valign="top">
<th scope="row"><?php _e('Mailer', 'wp_mail_smtp'); ?> </th>
<td><fieldset><legend class="screen-reader-text"><span><?php _e('Mailer', 'wp_mail_smtp'); ?></span></legend>
<p><input id="mailer_smtp" type="radio" name="mailer" value="smtp" <?php checked('smtp', get_option('mailer')); ?> />
<label for="mailer_smtp"><?php _e('Send all WordPress emails via SMTP.', 'wp_mail_smtp'); ?></label></p>
<p><input id="mailer_mail" type="radio" name="mailer" value="mail" <?php checked('mail', get_option('mailer')); ?> />
<label for="mailer_mail"><?php _e('Use the PHP mail() function to send emails.', 'wp_mail_smtp'); ?></label></p>
</fieldset></td>
</tr>
</table>


<table class="optiontable form-table">
<tr valign="top">
<th scope="row"><?php _e('Return Path', 'wp_mail_smtp'); ?> </th>
<td><fieldset><legend class="screen-reader-text"><span><?php _e('Return Path', 'wp_mail_smtp'); ?></span></legend><label for="mail_set_return_path">
<input name="mail_set_return_path" type="checkbox" id="mail_set_return_path" value="true" <?php checked('true', get_option('mail_set_return_path')); ?> />
<?php _e('Set the return-path to match the From Email'); ?></label>
</fieldset></td>
</tr>
</table>

<h3><?php _e('SMTP Options', 'wp_mail_smtp'); ?></h3>
<p><?php _e('These options only apply if you have chosen to send mail by SMTP above.', 'wp_mail_smtp'); ?></p>

<table class="optiontable form-table">
<tr valign="top">
<th scope="row"><label for="smtp_host"><?php _e('SMTP Host', 'wp_mail_smtp'); ?></label></th>
<td><input name="smtp_host" type="text" id="smtp_host" value="<?php print(get_option('smtp_host')); ?>" size="40" class="regular-text" /></td>
</tr>
<tr valign="top">
<th scope="row"><label for="smtp_port"><?php _e('SMTP Port', 'wp_mail_smtp'); ?></label></th>
<td><input name="smtp_port" type="text" id="smtp_port" value="<?php print(get_option('smtp_port')); ?>" size="6" class="regular-text" /></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Encryption', 'wp_mail_smtp'); ?> </th>
<td><fieldset><legend class="screen-reader-text"><span><?php _e('Encryption', 'wp_mail_smtp'); ?></span></legend>
<input id="smtp_ssl_none" type="radio" name="smtp_ssl" value="none" <?php checked('none', get_option('smtp_ssl')); ?> />
<label for="smtp_ssl_none"><span><?php _e('No encryption.', 'wp_mail_smtp'); ?></span></label><br />
<input id="smtp_ssl_ssl" type="radio" name="smtp_ssl" value="ssl" <?php checked('ssl', get_option('smtp_ssl')); ?> />
<label for="smtp_ssl_ssl"><span><?php _e('Use SSL encryption.', 'wp_mail_smtp'); ?></span></label><br />
<input id="smtp_ssl_tls" type="radio" name="smtp_ssl" value="tls" <?php checked('tls', get_option('smtp_ssl')); ?> />
<label for="smtp_ssl_tls"><span><?php _e('Use TLS encryption. This is not the same as STARTTLS. For most servers SSL is the recommended option.', 'wp_mail_smtp'); ?></span></label>
</td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Authentication', 'wp_mail_smtp'); ?> </th>
<td>
<input id="smtp_auth_false" type="radio" name="smtp_auth" value="false" <?php checked('false', get_option('smtp_auth')); ?> />
<label for="smtp_auth_false"><span><?php _e('No: Do not use SMTP authentication.', 'wp_mail_smtp'); ?></span></label><br />
<input id="smtp_auth_true" type="radio" name="smtp_auth" value="true" <?php checked('true', get_option('smtp_auth')); ?> />
<label for="smtp_auth_true"><span><?php _e('Yes: Use SMTP authentication.', 'wp_mail_smtp'); ?></span></label><br />
<span class="description"><?php _e('If this is set to no, the values below are ignored.', 'wp_mail_smtp'); ?></span>
</td>
</tr>
<tr valign="top">
<th scope="row"><label for="smtp_user"><?php _e('Username', 'wp_mail_smtp'); ?></label></th>
<td><input name="smtp_user" type="text" id="smtp_user" value="<?php print(get_option('smtp_user')); ?>" size="40" class="code" /></td>
</tr>
<tr valign="top">
<th scope="row"><label for="smtp_pass"><?php _e('Password', 'wp_mail_smtp'); ?></label></th>
<td><input name="smtp_pass" type="text" id="smtp_pass" value="<?php print(get_option('smtp_pass')); ?>" size="40" class="code" /></td>
</tr>
</table>

<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save Changes'); ?>" /></p>
<input type="hidden" name="action" value="update" />
</p>
<input type="hidden" name="option_page" value="email">
</form>

<h3><?php _e('Send a Test Email', 'wp_mail_smtp'); ?></h3>

<form method="POST" action="options-general.php?page=<?php echo plugin_basename(__FILE__); ?>">
<?php wp_nonce_field('test-email'); ?>
<table class="optiontable form-table">
<tr valign="top">
<th scope="row"><label for="to"><?php _e('To:', 'wp_mail_smtp'); ?></label></th>
<td><input name="to" type="text" id="to" value="" size="40" class="code" />
<span class="description"><?php _e('Type an email address here and then click Send Test to generate a test email.', 'wp_mail_smtp'); ?></span></td>
</tr>
</table>
<p class="submit"><input type="submit" name="wpms_action" id="wpms_action" class="button-primary" value="<?php _e('Send Test', 'simple_ticket'); ?>" /></p>
</form>

</div>

*/
