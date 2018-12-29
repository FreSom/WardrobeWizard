<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Wardrobe Wizard
 *
 * @wordpress-plugin
 * Plugin Name:       Wardrobe Wizard
 * Plugin URI:
 * Description:       Select cool styles!.
 * Version:           1.0.0
 * Author:            Frederik Sommerlund
 * Author URI:        https://fresom.dk/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       nf-systems-wardrobewizard
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-nfsystems-wardrobewizard-activator.php
 */
function activate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-nfsystems-wardrobewizard-activator.php';
	Plugin_Name_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-nfsystems-wardrobewizard-deactivator.php
 */
function deactivate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-nfsystems-wardrobewizard-deactivator.php';
	Plugin_Name_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_plugin_name' );
register_deactivation_hook( __FILE__, 'deactivate_plugin_name' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-nfsystems-wardrobewizard.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_plugin_name() {

	$plugin = new Plugin_Name();
	$plugin->run();

}
run_plugin_name();

add_action('add_meta_boxes',function(){
    add_meta_box('nfsystem_ww_settings',_x('Indstillinger','Product information','nfsystems'),'nfsystems_meta_settings','nfwardrobe','side','default');
    add_meta_box('nfsystem_ww_over',_x('Overdele','Product information','nfsystems'),'nfsystems_meta_over','nfwardrobe','normal','default');
    add_meta_box('nfsystem_ww_under',_x('Underdele','Product information','nfsystems'),'nfsystems_meta_under','nfwardrobe','normal','default');
    add_meta_box('nfsystem_ww_wardrobe',_x('Wardrobe','Product information','nfsystems'),'nfsystems_meta_wardrobe','product','side','default');
},10,2);

//Male/Female settings currently not in use
function nfsystems_meta_settings(){
    global $post;
    $meta = get_post_meta($post->ID);
    ?>
    <input type="radio" name="gender" value="male">Male<br>
    <input type="radio" name="gender" value="female">Female<br>
    <?php
}

function nfsystems_meta_over(){
    global $post;
    $meta = get_post_meta($post->ID);
    $topProducts = get_post_meta($post->ID, 'wardrobe_products_top', true);
    foreach ($topProducts as $topProductID) {


        echo '<a href="' . get_edit_post_link($topProductID) . '">';
 echo wp_get_attachment_image(get_post_thumbnail_id($topProductID));
        echo "</a>";
        echo '<h2>' . get_the_title($topProductID) . '</h2>';
    }
    ?>
<?php
}

function nfsystems_meta_under(){
    global $post;
    $meta = get_post_meta($post->ID);
    $bottomProducts = get_post_meta($post->ID, 'wardrobe_products_bottom', true);
    foreach ($bottomProducts as $bottomProductID) {


        echo '<a href="' . get_edit_post_link($bottomProductID) . '">';
        echo wp_get_attachment_image(get_post_thumbnail_id($bottomProductID));
        echo "</a>";
        echo '<h2>' . get_the_title($bottomProductID) . '</h2>';
    }
    ?>
    <?php
}

function nfsystems_meta_wardrobe(){
    global $post;

    $producttype = get_post_meta($post->ID,'wardrobe_product_type', true);
    $wardrobeId = get_post_meta($post->ID,'wardrobe_product', true);
    ?>
    <h1>Tilføj til Wardrobe</h1>
    <select name="wardrobe_product">
        <option value="">Intet valgt</option>
    <?php
    // Get all wardrobe posts
    $wardrobes = get_posts(array(
        "post_type" => "nfwardrobe",
        // Get everything
        "posts_per_page" => -1
    ));
    // Autoselect wardrobe
    foreach($wardrobes as $wardrobe){
       ?> <option value="<?php echo $wardrobe->ID; ?>" <?php echo ($wardrobeId == $wardrobe->ID ? 'selected="selected"' : '')?>><?php echo $wardrobe->post_title; ?></option><?php
    }
    ?>

    </select>
    <input type="radio" name="wardrobe_product_type" value="overdel" <?php echo ($producttype == 'overdel' ? 'checked="checked"' : '')?>>Overdel<br>
    <input type="radio" name="wardrobe_product_type" value="underdel" <?php echo ($producttype == 'underdel' ? 'checked="checked"' : '')?>>Underdel<br>
    <?php
}

function nfsystem_wardrobe_attach_product_save($post_id)
{
    // Prevent autosave from disturbing
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    // Require sufficient rights. Security concern.
    if (isset($_POST['post_type']) && 'product' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return;
        }
    }
    else {
        return;
    }
// Update/create database entries
    if (isset($_POST['wardrobe_product']) && !empty($_POST['wardrobe_product'])){
        update_post_meta($post_id, 'wardrobe_product', $_POST['wardrobe_product']);
        update_post_meta($post_id, 'wardrobe_product_type', $_POST['wardrobe_product_type']);
        // check if post id exists
        if (isset($_POST['wardrobe_product_type']) && $_POST['wardrobe_product_type'] == 'overdel') {
            $wardrobeProductsTop = get_post_meta($_POST['wardrobe_product'], 'wardrobe_products_top', true);
            // if style is empty, create array
            if (empty($wardrobeProductsTop)){
                         $wardrobeProductsTop = array();
                        }
            if (!isset($wardrobeProductsTop[$post_id])) {
                $wardrobeProductsTop[$post_id] = $post_id;
            }


            // creates/update new entry
            update_post_meta($_POST['wardrobe_product'], 'wardrobe_products_top', $wardrobeProductsTop);
        } else if (isset($_POST['wardrobe_product_type']) && $_POST['wardrobe_product_type'] == 'underdel') {
                $wardrobeProductsBottom = get_post_meta($_POST['wardrobe_product'], 'wardrobe_products_bottom', true);
           // if style is empty, create array
            if (empty($wardrobeProductsBottom)){
                $wardrobeProductsBottom = array();
            }
                if (!isset($wardrobeProductsBottom[$post_id])) {
                    $wardrobeProductsBottom[$post_id] = $post_id;
            }
            update_post_meta($_POST['wardrobe_product'], 'wardrobe_products_bottom', $wardrobeProductsBottom);
        }
// Remove from wardrobe
    } else {
        $oldWardrobe = get_post_meta($post_id, 'wardrobe_product', true);
        $wardrobeProductsTop = get_post_meta($post_id, 'wardrobe_products_top', true);
        $wardrobeProductsBottom = get_post_meta($oldWardrobe, 'wardrobe_products_bottom', true);
        unset($wardrobeProductsTop[$post_id]);
        unset($wardrobeProductsBottom[$post_id]);
        // update database entry
        update_post_meta($oldWardrobe, 'wardrobe_products_bottom', $wardrobeProductsBottom);
        update_post_meta($oldWardrobe, 'wardrobe_products_top', $wardrobeProductsTop);
    }

    return;
}
add_action('save_post', 'nfsystem_wardrobe_attach_product_save');

/* Filter the single_template with our custom function*/
add_filter('single_template', 'nf_wardrobe_template');

function nf_wardrobe_template($single) {

    global $post;

    /* Checks for single template by post type */
if ( $post->post_type == 'nfwardrobe' ) {
    if ( file_exists( __DIR__ . '/single-nfwardrobe.php' ) ) {
        return __DIR__ . '/single-nfwardrobe.php';
    }
}

return $single;

}