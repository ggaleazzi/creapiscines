<?php
/*
Plugin Name:    Custom Swatches for Iris Color Picker
Plugin URI:     https://www.icebergwebdesign.com/custom-swatches-iris-color-picker/
Description:    A plugin to choose custom swatches for the Iris Color Picker
Author:         Iceberg Web Design
Author URI:     https://www.icebergwebdesign.com
License:        GPL2
License URI:    https://www.gnu.org/licenses/gpl-2.0.html
Version:           1.0
*/

# http://kovshenin.com/2012/the-wordpress-settings-api/
# http://codex.wordpress.org/Settings_API

add_action( 'admin_menu', 'add_iceberg_iris_menu' );
function add_iceberg_iris_menu() {
    $page_title = 'Custom Swatch Options for Iris Color Picker';
    $menu_title = 'Color Swatches';
    $capability = 'manage_options';
    $menu_slug = 'iceberg_iris_color';
    $function = 'create_iceberg_iris_admin_page';
    $text_domain = 'iceberg_iris_swatches';

    add_options_page( __($page_title, $text_domain), $menu_title, $capability, $menu_slug, $function );
}
add_action( 'admin_init', 'iceberg_iris_admin_init' );

function iceberg_iris_admin_init() {
  
  /* 
     * http://codex.wordpress.org/Function_Reference/register_setting
     * register_setting( $option_group, $option_name, $sanitize_callback );
     * The second argument ($option_name) is the option name. It’s the one we use with functions like get_option() and update_option()
     * */
    # With input validation:
    register_setting( 'iceberg_iris_option_group', 'iceberg_iris_settings', 'iceberg_iris_sanitize' );    
    # register_setting( 'iceberg_iris_option_group', 'iceberg_iris_settings' );
    
    /* 
     * http://codex.wordpress.org/Function_Reference/add_settings_section
     * add_settings_section( $id, $title, $callback, $page ); 
     * */    
    add_settings_section( 'iceberg_iris_section_1', __( 'Custom Swatches', 'iceberg_iris_swatches' ), 'iceberg_iris_section_1_callback', 'iceberg_iris_color' );
    
    /* 
     * http://codex.wordpress.org/Function_Reference/add_settings_field
     * add_settings_field( $id, $title, $callback, $page, $section, $args );
     * */
    add_settings_field( 'iceberg_iris_color_1', __( 'Color 1:', 'iceberg_iris_swatches' ), 'iceberg_iris_color_1_callback', 'iceberg_iris_color', 'iceberg_iris_section_1' );
    add_settings_field( 'iceberg_iris_color_2', __( 'Color 2:', 'iceberg_iris_swatches' ), 'iceberg_iris_color_2_callback', 'iceberg_iris_color', 'iceberg_iris_section_1' );
    add_settings_field( 'iceberg_iris_color_3', __( 'Color 3:', 'iceberg_iris_swatches' ), 'iceberg_iris_color_3_callback', 'iceberg_iris_color', 'iceberg_iris_section_1' );
    add_settings_field( 'iceberg_iris_color_4', __( 'Color 4:', 'iceberg_iris_swatches' ), 'iceberg_iris_color_4_callback', 'iceberg_iris_color', 'iceberg_iris_section_1' );
    add_settings_field( 'iceberg_iris_color_5', __( 'Color 5:', 'iceberg_iris_swatches' ), 'iceberg_iris_color_5_callback', 'iceberg_iris_color', 'iceberg_iris_section_1' );
    add_settings_field( 'iceberg_iris_color_6', __( 'Color 6:', 'iceberg_iris_swatches' ), 'iceberg_iris_color_6_callback', 'iceberg_iris_color', 'iceberg_iris_section_1' );
    add_settings_field( 'iceberg_iris_color_7', __( 'Color 7:', 'iceberg_iris_swatches' ), 'iceberg_iris_color_7_callback', 'iceberg_iris_color', 'iceberg_iris_section_1' );
    add_settings_field( 'iceberg_iris_color_8', __( 'Color 8:', 'iceberg_iris_swatches' ), 'iceberg_iris_color_8_callback', 'iceberg_iris_color', 'iceberg_iris_section_1' );

    
}
/* 
 * THE ACTUAL PAGE 
 * */
function create_iceberg_iris_admin_page() {

?>

  <div class="wrap">
      <h2><?php _e('Custom Swatches for Iris Color Picker', 'iceberg_iris_swatches'); ?></h2>
      <form action="options.php" method="POST">  
        <?php settings_fields('iceberg_iris_option_group'); ?>
        <?php do_settings_sections('iceberg_iris_color'); ?>
        <?php submit_button(); ?>
      </form>
  </div>
<?php }
/*
* THE SECTIONS
* Hint: You can omit using add_settings_field() and instead
* directly put the input fields into the sections.
* */
function iceberg_iris_section_1_callback() {
    _e( 'Enter your custom colors with hex values.<br>This will replace the default swatches under the Iris Color Picker.<br>If you would like to revert to default, please clear the box and <strong>Save Changes</strong>.', 'iceberg_iris_swatches' );
}
/*
* THE FIELDS
* */

$defaults = array (
    'iceberg_iris_color_1' => '#000000',
    'iceberg_iris_color_2' => '#ffffff',
    'iceberg_iris_color_3' => '#dd3333',
    'iceberg_iris_color_4' => '#dd9933',
    'iceberg_iris_color_5' => '#eeee22',
    'iceberg_iris_color_6' => '#81d742',
    'iceberg_iris_color_7' => '#1e73be',
    'iceberg_iris_color_8' => '#8224e3',
);

$settings = wp_parse_args(get_option( 'iceberg_iris_settings' ) , $defaults);

function iceberg_iris_color_1_callback() {

    global $defaults;
    global $settings;
    $field = "iceberg_iris_color_1";
    $value = esc_attr( $settings[$field] );
    
    echo "<input type='text' name='iceberg_iris_settings[$field]' value='$value' />";
    echo "<div class='color_preview' style='background-color: " . $value . ";'></div>";
}
function iceberg_iris_color_2_callback() {
    
    global $defaults;
    global $settings;
    $field = "iceberg_iris_color_2";
    $value = esc_attr( $settings[$field] );
    
    echo "<input type='text' name='iceberg_iris_settings[$field]' value='$value' />";
    echo "<div class='color_preview' style='background-color: " . $value . ";'></div>";
}
function iceberg_iris_color_3_callback() {
    
    global $defaults;
    global $settings;
    $field = "iceberg_iris_color_3";
    $value = esc_attr( $settings[$field] );
    
    echo "<input type='text' name='iceberg_iris_settings[$field]' value='$value' />";
    echo "<div class='color_preview' style='background-color: " . $value . ";'></div>";
}
function iceberg_iris_color_4_callback() {
    
    global $defaults;
    global $settings;
    $field = "iceberg_iris_color_4";
    $value = esc_attr( $settings[$field] );
    
    echo "<input type='text' name='iceberg_iris_settings[$field]' value='$value' />";
    echo "<div class='color_preview' style='background-color: " . $value . ";'></div>";
}
function iceberg_iris_color_5_callback() {
    
    global $defaults;
    global $settings;
    $field = "iceberg_iris_color_5";
    $value = esc_attr( $settings[$field] );
    
    echo "<input type='text' name='iceberg_iris_settings[$field]' value='$value' />";
    echo "<div class='color_preview' style='background-color: " . $value . ";'></div>";
}
function iceberg_iris_color_6_callback() {
    
    global $defaults;
    global $settings;
    $field = "iceberg_iris_color_6";
    $value = esc_attr( $settings[$field] );
    
    echo "<input type='text' name='iceberg_iris_settings[$field]' value='$value' />";
    echo "<div class='color_preview' style='background-color: " . $value . ";'></div>";
}
function iceberg_iris_color_7_callback() {
    
    global $defaults;
    global $settings;
    $field = "iceberg_iris_color_7";
    $value = esc_attr( $settings[$field] );
    
    echo "<input type='text' name='iceberg_iris_settings[$field]' value='$value' />";
    echo "<div class='color_preview' style='background-color: " . $value . ";'></div>";
}
function iceberg_iris_color_8_callback() {
    
    global $defaults;
    global $settings;
    $field = "iceberg_iris_color_8";
    $value = esc_attr( $settings[$field] );
    
    echo "<input type='text' name='iceberg_iris_settings[$field]' value='$value' />";
    echo "<div class='color_preview' style='background-color: " . $value . ";'></div>";
}

/**
 * Sanitises a HEX value.
 * The way this works is by splitting the string in 6 substrings.
 * Each sub-string is individually sanitized, and the result is then returned.
 *
 * This function is part of the `Kirki_Color` class in the [Kirki](http://kirki.org) Toolkit.
 *
 * @var     string      The hex value of a color
 * @param   boolean     Whether we want to include a hash (#) at the beginning or not
 * @return  string      The sanitized hex color.
 */
function aristath_sanitize_hex( $color,  $hash = true ) {

    // Remove any spaces and special characters before and after the string
    $color = trim( $color );

    // Remove any trailing '#' symbols from the color value
    $color = str_replace( '#', '', $color );

    // If the string is 6 characters long then use it in pairs.
    if ( 3 == strlen( $color ) ) {
        $color = substr( $color, 0, 1 ) . substr( $color, 0, 1 ) . substr( $color, 1, 1 ) . substr( $color, 1, 1 ) . substr( $color, 2, 1 ) . substr( $color, 2, 1 );
    }

    $substr = array();
    for ( $i = 0; $i <= 5; $i++ ) {
        $default    = ( 0 == $i ) ? 'F' : ( $substr[$i-1] );
        $substr[$i] = substr( $color, $i, 1 );
        $substr[$i] = ( false === $substr[$i] || ! ctype_xdigit( $substr[$i] ) ) ? $default : $substr[$i];
    }
    $hex = implode( '', $substr );

    return ( ! $hash ) ? $hex : '#' . $hex;

}
/*
* INPUT VALIDATION:
* */
function iceberg_iris_sanitize( $input ) {

    
    $settings = (array) get_option( 'iceberg_iris_settings' );
    
    if ( '' === $input['iceberg_iris_color_1'] ) {
        $output['iceberg_iris_color_1'] = '#000000';
    } else {
        $output['iceberg_iris_color_1'] = aristath_sanitize_hex( $input['iceberg_iris_color_1']);
    }
    if ( '' === $input['iceberg_iris_color_2'] ) {
        $output['iceberg_iris_color_2'] = '#ffffff';
    } else {
        $output['iceberg_iris_color_2'] = aristath_sanitize_hex( $input['iceberg_iris_color_2']);
    }
    if ( '' === $input['iceberg_iris_color_3'] ) {
        $output['iceberg_iris_color_3'] = '#dd3333';
    } else {
        $output['iceberg_iris_color_3'] = aristath_sanitize_hex( $input['iceberg_iris_color_3']);
    }
    if ( '' === $input['iceberg_iris_color_4'] ) {
        $output['iceberg_iris_color_4'] = '#dd9933';
    } else {
        $output['iceberg_iris_color_4'] = aristath_sanitize_hex( $input['iceberg_iris_color_4']);
    }
    if ( '' === $input['iceberg_iris_color_5'] ) {
        $output['iceberg_iris_color_5'] = '#eeee22';
    } else {
        $output['iceberg_iris_color_5'] = aristath_sanitize_hex( $input['iceberg_iris_color_5']);
    }
    if ( '' === $input['iceberg_iris_color_6'] ) {
        $output['iceberg_iris_color_6'] = '#81d742';
    } else {
        $output['iceberg_iris_color_6'] = aristath_sanitize_hex( $input['iceberg_iris_color_6']);
    }
    if ( '' === $input['iceberg_iris_color_7'] ) {
        $output['iceberg_iris_color_7'] = '#1e73be';
    } else {
        $output['iceberg_iris_color_7'] = aristath_sanitize_hex( $input['iceberg_iris_color_7']);
    }
    if ( '' === $input['iceberg_iris_color_8'] ) {
        $output['iceberg_iris_color_8'] = '#8224e3';
    } else {
        $output['iceberg_iris_color_8'] = aristath_sanitize_hex( $input['iceberg_iris_color_8']);
    }
        
    return $output;
}

function iceberg_iris_load_scripts() {

wp_register_script( 'iceberg_iris_swatches', plugin_dir_url( __FILE__ ) . 'js/iceberg_iris_swatches.js');

$custom_colors = get_option('iceberg_iris_settings');
$colors = array(
    'iceberg_iris_color1' => $custom_colors['iceberg_iris_color_1'], 
    'iceberg_iris_color2' => $custom_colors['iceberg_iris_color_2'], 
    'iceberg_iris_color3' => $custom_colors['iceberg_iris_color_3'], 
    'iceberg_iris_color4' => $custom_colors['iceberg_iris_color_4'], 
    'iceberg_iris_color5' => $custom_colors['iceberg_iris_color_5'], 
    'iceberg_iris_color6' => $custom_colors['iceberg_iris_color_6'], 
    'iceberg_iris_color7' => $custom_colors['iceberg_iris_color_7'],
    'iceberg_iris_color8' => $custom_colors['iceberg_iris_color_8']
     );
wp_localize_script('iceberg_iris_swatches', 'iceberg_iris_color' , $colors);
wp_enqueue_script ( 'iceberg_iris_swatches', plugin_dir_url( __FILE__ ) . 'js/iceberg_iris_swatches.js');
wp_enqueue_style ( 'iceberg_iris' , plugin_dir_url( __FILE__ ) . 'css/iceberg_iris.css');
}

add_action('admin_enqueue_scripts', 'iceberg_iris_load_scripts');