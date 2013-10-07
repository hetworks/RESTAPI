<?php /**
 * Plugin Name: VVV api
 * Plugin URI: http://www.hetworks.nl
 * Description: VVV evenementen data.
 * Version: 1.0
 * Author: Bart Bresse
 * Author URI: http://URI_Of_The_Plugin_Author
 * License: A "Slug" license name e.g. GPL2
 */
 
 
 //http://vvv-vallei-evenementen.nl.84-38-229-61.eftwee.nl/api/events

 //custom post type
 add_action( 'init', 'create_post_type' );
 
 function create_post_type() {
	
	$labels = array(
		'name'               => _x( 'Products', 'post type general name' ),
		'singular_name'      => _x( 'Product', 'post type singular name' ),
		'add_new'            => _x( 'Add New', 'book' ),
		'add_new_item'       => __( 'Add New Product' ),
		'edit_item'          => __( 'Edit Product' ),
		'new_item'           => __( 'New Product' ),
		'all_items'          => __( 'All Products' ),
		'view_item'          => __( 'View Product' ),
		'search_items'       => __( 'Search Products' ),
		'not_found'          => __( 'No products found' ),
		'not_found_in_trash' => __( 'No products found in the Trash' ), 
		'parent_item_colon'  => '',
		'menu_name'          => 'Products'
	);
	$args = array(
		'labels'        => $labels,
		'description'   => 'Holds our products and product specific data',
		'public'        => true,
		'menu_position' => 5,
		'supports'      => array( 'title', 'date', 'post_date_gmt', 'title', 'comments' ),
		'has_archive'   => true);
	
	
	register_post_type( 'evenement', $args);
}
 
//CRONJOB
add_action( 'wp', 'REST_setup_schedule' );
function REST_setup_schedule() {
	if ( ! wp_next_scheduled( 'REST_hourly_event' ) ) {
		wp_schedule_event( time(), 'daily', 'REST_hourly_event');
	}
}
add_action( 'REST_hourly_event', 'REST_do_this_hourly' );
function REST_do_this_hourly() {
	
	include('RESTapi.php');
	$evenementen = CallAPI('GET', '?city=veenendaal', $data = false);

	$decoded = json_decode($evenementen,TRUE);	
	
	foreach($decoded['evenement'] as $evenement)
	{
		/*Array ( [0] => title  //post_name
				[1] => short_description //post_content_filtered
				[2] => long_description //post_content
				[3] => ndtrc_categories // array met categorien
				[4] => website 
				[5] => street 
				[6] => house_number 
				[7] => zipcode 
				[8] => city 
				[9] => latitude 
				[10] => longitude 
				[11] => contact_email 
				[12] => contact_phone 
				[13] => images  is image array
				[14] => opening_hours    dagen = > openingstijden array
				[15] => date  start en einde array )	*/
	
		//insert standard post reference
		wp_insert_post( $post, $wp_error );
		
		$postID = get_the_ID();
		//insert custom fields
		//add_post_meta($post_id, $meta_key, $meta_value, $unique);
		
		foreach($evenement['ndtrc_categories'] as $categorie)
		{ add_post_meta($postID, 'ndtrc_category', $categorie, false);	}
	}
		
}
 
 
 
 
 
 
 
 
// Hook for adding admin menus
add_action('admin_menu', 'mt_add_pages');

// action function for above hook
function mt_add_pages() {



    // Add a new submenu under Settings:
    add_options_page(__('Test Settings','menu-test'), __('Test Settings','menu-test'), 'manage_options', 'testsettings', 'mt_settings_page');

    // Add a new submenu under Tools:
    add_management_page( __('Test Tools','menu-test'), __('Test Tools','menu-test'), 'manage_options', 'testtools', 'mt_tools_page');

    // Add a new top-level menu (ill-advised):
    add_menu_page(__('evenementen','menu-test'), __('evenementen','menu-test'), 'manage_options', 'mt-top-level-handle', 'mt_toplevel_page' );

	/*
    // Add a submenu to the custom top-level menu:
    add_submenu_page('mt-top-level-handle', __('Test Sublevel','menu-test'), __('Test Sublevel','menu-test'), 'manage_options', 'sub-page', 'mt_sublevel_page');

    // Add a second submenu to the custom top-level menu:
    add_submenu_page('mt-top-level-handle', __('Test Sublevel 2','menu-test'), __('Test Sublevel 2','menu-test'), 'manage_options', 'sub-page2', 'mt_sublevel_page2');
	*/
	//add_action('admin_menu', 'my_plugin_menu');

// Here you can check if plugin is configured (e.g. check if some option is set). If not, add new hook. 
// In this example hook is always added.
	//add_action( 'admin_notices', 'my_plugin_admin_notices' );
}

// mt_settings_page() displays the page content for the Test settings submenu
function mt_settings_page() {
    //must check that the user has the required capability 
    if (!current_user_can('manage_options'))
    {
      wp_die( __('You do not have sufficient permissions to access this page.') );
    }

    // variables for the field and option names 
    $opt_name = 'mt_favorite_color';
    $hidden_field_name = 'mt_submit_hidden';
    $data_field_name = 'mt_favorite_color';

    // Read in existing option value from database
    $opt_val = get_option( $opt_name );

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value
        $opt_val = $_POST[ $data_field_name ];

        // Save the posted value in the database
        update_option( $opt_name, $opt_val );

        // Put an settings updated message on the screen

	?>
	<div class="updated"><p><strong><?php _e('settings saved.', 'menu-test' ); ?></strong></p></div>
	<?php

		}

		// Now display the settings editing screen

		echo '<div class="wrap">';

		// header

		echo "<h2>" . __( 'Menu Test Plugin Settings', 'menu-test' ) . "</h2>";

		// settings form
		
		?>

	<form name="form1" method="post" action="">
	<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

	<p><?php _e("Favorite Color:", 'menu-test' ); ?> 
	<input type="text" name="<?php echo $data_field_name; ?>" value="<?php echo $opt_val; ?>" size="20">
	</p><hr />

	<p class="submit">
	<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
	</p>

	</form>
	</div>

	<?php
}

// mt_tools_page() displays the page content for the Test Tools submenu
function mt_tools_page() {
    echo "<h2>" . __( 'Test Tools', 'menu-test' ) . "</h2>";
}

// mt_toplevel_page() displays the page content for the custom Test Toplevel menu
function mt_toplevel_page() {
	
	
	
	$html .= "<h2>" . __( 'Evenementen', 'menu-test' ) . "</h2>";
	
	echo $html;
}

// mt_sublevel_page() displays the page content for the first submenu
// of the custom Test Toplevel menu
/*
function mt_sublevel_page() {
    echo "<h2>" . __( 'Test Sublevel', 'menu-test' ) . "</h2>";
}

// mt_sublevel_page2() displays the page content for the second submenu
// of the custom Test Toplevel menu
function mt_sublevel_page2() {
    echo "<h2>" . __( 'Test Sublevel2', 'menu-test' ) . "</h2>";
}

*/
function my_plugin_menu() {
	// Add the new admin menu and page and save the returned hook suffix
	$hook_suffix = add_options_page('My Plugin Options', 'My Plugin', 'manage_options', 'my-unique-identifier', 'my_plugin_options');
	// Use the hook suffix to compose the hook and register an action executed when plugin's options page is loaded
	add_action( 'load-' . $hook_suffix , 'my_load_function' );
}

function my_load_function() {
	// Current admin page is the options page for our plugin, so do not display the notice
	// (remove the action responsible for this)
	remove_action( 'admin_notices', 'my_plugin_admin_notices' );
}

function my_plugin_admin_notices() {
	echo "<div id='notice' class='updated fade'><p>My Plugin is not configured yet. Please do it now.</p></div>\n";
}

function my_plugin_options() {
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	echo '<div class="wrap">';
	echo '<p>Here is where the form would go if I actually had options.</p>';
	echo '</div>';
}
?>