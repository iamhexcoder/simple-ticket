
<?php

function add_simple_support_admin_node( $wp_admin_bar ) {

  // add a parent item
	$args = array(
		'id'    => 'simple_support',
		'title' => 'Simple Support'
	);
	$wp_admin_bar->add_node( $args );

  // add a child item to our parent item
  $args = array(
    'parent' => 'simple_support',
    'id'     => 'simple_support_form',
    'title'  => '
    <form id="simple_support_form">
      <input name="name" type="text" required value="" placeholder="Subject"/>
      <input type="text" name="email" placeholder="Email">
      <input class="simple-support-submit" type="submit" value="Create Ticket" />
    </form>
    '
  );
  $wp_admin_bar->add_node( $args );

}

add_action( 'admin_bar_menu', 'add_simple_support_admin_node', 999 );

function link_to_stylesheet() { ?>
  <style type="text/css">
    #wp-admin-bar-simple_support input,
    #wp-admin-bar-simple_support textarea {
      margin: 0 0 10px;
    }

    #wp-admin-bar-simple_support .ab-item {
      height: auto !important;
    }

    #wp-admin-bar-simple_support .ab-submenu {
      padding: 10px 0;
    }

    .simple-support-submit {
      display: block;
      margin-top: 10px;
    }
  </style>
<?php }

add_action('wp_head', 'link_to_stylesheet');
add_action( 'admin_bar_menu', 'add_nodes_and_groups_to_toolbar', 999 );
