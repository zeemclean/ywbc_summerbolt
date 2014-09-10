<?php

/*
==================================================================
THEME CUSTOMIZER
Defines an array of options that will be saved as 'theme_mod'
settings in your options table.
==================================================================
*/

add_action('customize_register', 'sfpaper_customizer');
function sfpaper_customizer($wp_customize) {
global $wp_customize;
if ( isset( $_GET['resetmods'] ) ) {
	remove_theme_mods();
}

/* Add a custom class for reset button */
class sfpaper_Customize_Reset_Control extends WP_Customize_Control {
	public $type = 'reset_button';

	public function render_content() {
		?>
		<form action="customize.php" method="get">
		<label>
		<span style="font-weight:normal;margin-bottom:10px;" class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
		<input type="submit" class="reset-button button-secondary" name="resetmods" value="<?php esc_attr_e( 'Reset Theme Mods', 'sfpaper' ); ?>" onclick="return confirm( '<?php print esc_js( __( 'Click OK to reset. Any theme mods you have set in the Theme Customizer will be lost!', 'sfpaper' ) ); ?>' );" />
		</label>
		</form>
		<?php
	}
}

/* Add a custom class for support tab */
class sfpaper_Customize_Support_Control extends WP_Customize_Control {
	public $type = 'support_tab';

	public function render_content() {
		?>
		<h3><?php esc_attr_e( 'Welcome to Storefront Paper!', 'chronology' ); ?></h3>
		<p><?php esc_attr_e( 'We\'ve released this theme as a free theme and have a video tutorial to help you get up and running with it. To watch the video, simply visit the theme\'s homepage using the button below.', 'chronology' ); ?></p>
		<a class="button-secondary" href="http://storefrontthemes.com/themes/paper"><?php esc_attr_e( 'Learn More', 'sfpaper' ); ?></a>
		<p><?php esc_attr_e( 'We also offer premium level support for a low cost which includes access to our support forums and additional video tutorials to help you with WP e-Commerce and WooThemes setup. Simply click on the button below to learn more.', 'chronology' ); ?></p>
		<a class="button-primary" href="http://storefrontthemes.com/membership-signup/"><?php esc_attr_e( 'Get Support', 'sfpaper' ); ?></a>
		<?php
	}
}

	do_action('sfpaper_add_to_customizer');

} //END OF sfpaper_customizer
/*
==================================================================
NOW WE REGISTER ALL THE CORE THEME CUSTOMIZER OPTIONS AND ADD THEM
USING THE sfpaper_add_to_customizer ACTION HOOK. WE DO THIS SO
THAT THEY CAN BE EASILY REMOVED BY DEVELOPERS. ALSO, IF YOU WANT
TO REGISTER YOUR OWN, SIMPLY COPY ANY OF THE SECTIONS BELOW INTO
YOUR OWN THEME OR PLUGIN AND EDIT FOR YOUR NEEDS. 
==================================================================
*/
/*
==================================================================
Logo
==================================================================
*/
add_action('sfpaper_add_to_customizer','sfpaper_logo_customizer_options');
function sfpaper_logo_customizer_options($wp_customize) {
	global $wp_customize;
	global $sfpaper_fontchoices;
	
	$wp_customize->add_section( 'site_logo_settings', array(
		'title'          => 'Logo',
		'priority'       => 125,
	) );

	/* Logo Image Upload */
	$wp_customize->add_setting( 'logo_image', array(
		'sanitize_callback' => 'sfpaper_sanitize_file_name'
	) );
	
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'logo_image', array(
		'label'    => __( 'Logo Image', 'sfpaper'),
		'section'  => 'site_logo_settings',
		'settings' => 'logo_image',
	) ) );

	/* Logo font 
	$wp_customize->add_setting( 'logo_font_family', array(
	'default'        => 'Open Sans',
	) );

	$wp_customize->add_control( 'logo_font_family', array(
	'label'   => 'Logo Font Family:',
	'section' => 'site_logo_settings',
	'type'    => 'select',
	'priority'        => 25,
	'choices'    => $fontchoices,
	) );*/
}
/*
==================================================================
BACKGROUND
==================================================================
*/
add_action('sfpaper_add_to_customizer','sfpaper_background_customizer_options');
function sfpaper_background_customizer_options($wp_customize) {
	global $wp_customize;
	$wp_customize->add_section( 'site_background_settings', array(
		'title'          => 'Background',
		'priority'       => 130,
	) );

	/* Background Color */
	$wp_customize->add_setting( 'site_background_color', array(
		'default'        => '',
		'sanitize_callback' => 'sanitize_hex_color'
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'site_background_color', array(
		'label'   => 'Background Color',
		'section' => 'site_background_settings',
		'settings'   => 'site_background_color',
	) ) );

	/* Background Image Upload */
	$wp_customize->add_setting( 'site_background_image', array(
		'sanitize_callback' => 'sfpaper_sanitize_file_name'
	) );
	
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'site_background_image', array(
		'label'    => __( 'Background Image' , 'sfpaper'),
		'section'  => 'site_background_settings',
		'settings' => 'site_background_image',
	) ) );

	/* Background Image Repeat */
	$wp_customize->add_setting( 'site_background_repeat', array(
		'default'        => 'repeat',
		'sanitize_callback' => 'sfpaper_bg_repeat'
	) );
	
	$wp_customize->add_control( 'site_background_repeat', array(
		'label'   => 'Background Image Repeat:',
		'section' => 'site_background_settings',
		'type'    => 'select',
		'choices'    => array(
			'repeat' => 'Repeat',
			'repeat-x' => 'Repeat Horizontally',
			'repeat-y' => 'Repeat Vertically',
			'no-repeat' => 'No Repeat',
			),
	) );
	
	/* Background Image Position */
	$wp_customize->add_setting( 'site_background_position', array(
		'default'        => 'top center',
		'sanitize_callback' => 'sfpaper_bg_position'

	) );
	
	$wp_customize->add_control( 'site_background_position', array(
		'label'   => 'Background Image Position:',
		'section' => 'site_background_settings',
		'type'    => 'select',
		'choices'    => array(
			'top center' => 'Top Center',
			'top left' => 'Top Left',
			'top right' => 'Top Right',
			'bottom center' => 'Bottom Center',
			'bottom left' => 'Bottom Left',
			'bottom right' => 'Bottom Right',
			),
	) );
	
	/* Background Image Attachment */
	$wp_customize->add_setting( 'site_background_attachment', array(
		'default'        => 'scroll',
		'sanitize_callback' => 'sfpaper_bg_attachment'
	) );
	
	$wp_customize->add_control( 'site_background_attachment', array(
		'label'   => 'Background Image Attachment:',
		'section' => 'site_background_settings',
		'type'    => 'select',
		'choices'    => array(
			'scroll' => 'Scroll (moves with the content)',
			'fixed' => 'Fixed (remains static behind content)',
			),
	) );
}// END BACKGROUND SETTINGS


add_action('sfpaper_add_to_customizer','sfpaper_shop_customizer_options');
function sfpaper_shop_customizer_options($wp_customize) {
	global $wp_customize;
	$wp_customize->add_section( 'shop_options', array(
		'title'          => 'Shop Options',
		'priority'       => 140,
	) );

	/* Background Image Repeat */
	$wp_customize->add_setting( 'shop_layout', array(
		'default'        => 'threecolumn',
		'sanitize_callback' => 'sfpaper_shop_layout'
	) );
	
	$wp_customize->add_control( 'shop_layout', array(
		'label'   => 'Shop Page Layout:',
		'section' => 'shop_options',
		'type'    => 'select',
		'choices'    => array(
			'threecolumn' => '3 Column',
			'fourcolumn' => '4 Column',
			),
	) );
	
}// END BACKGROUND SETTINGS

/*
==================================================================
SITE COLORS
==================================================================
*/
add_action('sfpaper_add_to_customizer','sfpaper_colors_customizer_options');
function sfpaper_colors_customizer_options($wp_customize) {
	global $wp_customize;
	$wp_customize->add_section( 'color_settings', array(
		'title'          => 'Color Scheme',
		'priority'       => 140,
		'description'    => __( 'Choose colors for your theme.', 'sfpaper' ),
	) );
	
	/* Headings Color */
	$wp_customize->add_setting( 'heading_color', array(
		'default'        => '',
		'sanitize_callback' => 'sanitize_hex_color'
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'heading_color', array(
		'label'   => 'Headings Color',
		'section' => 'color_settings',
		'settings'   => 'heading_color',
		'priority'       => 10,
	) ) );
	
	/* Main Text Color */
	$wp_customize->add_setting( 'body_color', array(
		'default'        => '',
		'sanitize_callback' => 'sanitize_hex_color'
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'body_color', array(
		'label'   => 'Main Text Color',
		'section' => 'color_settings',
		'settings'   => 'body_color',
		'priority'       => 20,
	) ) );

	/* Small/Meta Text Color */
	$wp_customize->add_setting( 'small_color', array(
		'default'        => '',
		'sanitize_callback' => 'sanitize_hex_color'
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'small_color', array(
		'label'   => 'Small/Meta Text Color',
		'section' => 'color_settings',
		'settings'   => 'small_color',
		'priority'       => 30,
	) ) );
	
	/* Link Color */
	$wp_customize->add_setting( 'link_color', array(
		'default'        => '',
		'sanitize_callback' => 'sanitize_hex_color'
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'link_color', array(
		'label'   => 'Link Color',
		'section' => 'color_settings',
		'settings'   => 'link_color',
		'priority'       => 40,
	) ) );
	
	/* Border Color */
	$wp_customize->add_setting( 'border_color', array(
		'default'        => '',
		'sanitize_callback' => 'sanitize_hex_color'
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'border_color', array(
		'label'   => 'Border Color',
		'section' => 'color_settings',
		'settings'   => 'border_color',
		'priority'       => 50,
	) ) );
}// END COLOR SETTINGS
/*
==================================================================
FOOTER
==================================================================
*/
add_action('sfpaper_add_to_customizer','sfpaper_footer_customizer_options');
function sfpaper_footer_customizer_options($wp_customize) {
	global $wp_customize;
	/* Add footer section and color styles to customizer */
	$wp_customize->add_section( 'footer_settings', array(
		'title'          => 'Footer',
		'priority'       => 150,
	) );
	
	/* Footer Credits */
	$wp_customize->add_setting( 'footer_credits', array(
		'default'        => '',
		'sanitize_callback' => 'sanitize_text_field'
	) );
	
	$wp_customize->add_control( 'footer_credits', array(
		'label'   => 'Footer Credits',
		'section' => 'footer_settings',
		'settings'   => 'footer_credits',
		'type' => 'text',
		'priority'       => 45,
	) );
}// END FOOTER SETTINGS

/*
==================================================================
RESET CONTROL
==================================================================
*/
add_action('sfpaper_add_to_customizer','sfpaper_reset_customizer_options');
function sfpaper_reset_customizer_options($wp_customize) {
	global $wp_customize;
	/* Reset Theme Mods */
	$wp_customize->add_section( 'reset_settings', array(
		'title'          => 'Reset Theme Mods',
		'priority'       => 200,
	) );	
	
	$wp_customize->add_setting( 'reset_button', array(
		'default'        => '',
		'sanitize_callback' => 'sfpaper_reset_button'
	) );
	
	$wp_customize->add_control( new sfpaper_Customize_Reset_Control( $wp_customize, 'reset_button', array(
		'label'   => 'Reset all theme mods made for this theme to their default values by clicking the button below.',
		'section' => 'reset_settings',
		'settings'   => 'reset_button',
		'priority'       => 45,
	) ) );
	
}// END RESET CONTROL

/*
==================================================================
SUPPORT TAB
==================================================================
*/
add_action('sfpaper_add_to_customizer','sfpaper_support_customizer_options');
function sfpaper_support_customizer_options($wp_customize) {
	global $wp_customize;
	/* Support */
	$wp_customize->add_section( 'support_settings', array(
		'title'          => 'Theme Support',
		'priority'       => 1,
	) );
	
	$wp_customize->add_setting( 'support_tab', array(
		'sanitize_callback' => 'sanitize_text_field'
	) );
	
	$wp_customize->add_control( new sfpaper_Customize_Support_Control( $wp_customize, 'support_tab', array(
		'label'   => 'Theme Support',
		'section' => 'support_settings',
		'settings'    => 'support_tab',
		'priority'       => 1,
	) ) );
}// END SUPPORT TAB

/*
==================================================================
SANITIZATION CALLBACKS
==================================================================
*/
/*
Make use of existing sanitize_text_field in addition to new
functions below.
*/

/* REUSABLE */
if ( ! function_exists( 'sfpaper_sanitize_checkbox' ) ) :
	function sfpaper_sanitize_integer( $input ) {
		return absint( $input );
	}
endif;

if ( ! function_exists( 'sfpaper_sanitize_checkbox' ) ) :
	function sfpaper_sanitize_checkbox( $input ) {
		if ( $input == 1 ) {
			return 1;
		} else {
			return 0;
		}
	}
endif;

if ( ! function_exists( 'sfpaper_sanitize_file_name' ) ) :
	function sfpaper_sanitize_file_name( $input ) {
		return esc_url( $input );
	}
endif;

/* UNIQUE OPTIONS */
function sfpaper_bg_repeat( $input ) {
	if ( ! in_array( $input, array( 'repeat','repeat-x','repeat-y','no-repeat' ) ) ) {
		$input = 'repeat';
	}
	return $input;
}

function sfpaper_bg_position( $input ) {
	if ( ! in_array( $input, array( 'top center','top left','top right','bottom center','bottom left','bottom right' ) ) ) {
		$input = 'repeat';
	}
	return $input;
}

function sfpaper_bg_attachment( $input ) {
	if ( ! in_array( $input, array( 'scroll','fixed' ) ) ) {
		$input = 'scroll';
	}
	return $input;
}

function sfpaper_shop_layout( $input ) {
	if ( ! in_array( $input, array( 'threecolumn','fourcolumn' ) ) ) {
		$input = 'scroll';
	}
	return $input;
}

if ( ! function_exists( 'sfpaper_reset_button' ) ) :
	function sfpaper_reset_button() {
		return '';
	}
endif;

/*
==========================================================
Loads the custom styles from the Theme Customizer
==========================================================
*/
add_action( 'wp_head', 'sfpaper_custom_style');
function sfpaper_custom_style() { ?>
<style>
/* BODY */
<?php
/* Set Variables */
$bg_color = get_theme_mod( 'site_background_color');
$bg_image = get_theme_mod( 'site_background_image');
$bg_repeat = get_theme_mod( 'site_background_repeat');
$bg_position = get_theme_mod( 'site_background_position');
$bg_attachment = get_theme_mod( 'site_background_attachment');
$body_size = get_theme_mod( 'body_size');
$body_font = get_theme_mod( 'body_font');
$body_color = get_theme_mod( 'body_color');
$body_line = get_theme_mod( 'body_line_height');
$logo_font = get_theme_mod( 'logo_font_family');
$heading_font = get_theme_mod( 'heading_font');
$heading_color = get_theme_mod( 'heading_color');
$small_color = get_theme_mod( 'small_color');
$link_color = get_theme_mod( 'link_color');
$border_color = get_theme_mod( 'border_color');
$heading_color = get_theme_mod( 'heading_color');
$shop_layout = get_theme_mod( 'shop_layout');

/* Site Background */
echo 'body {';
if($bg_color) {echo 'background-color:' .$bg_color.';';}
if($bg_image) {echo 'background-image:url("' .$bg_image.'");';}
if($bg_repeat) {echo 'background-repeat:' .$bg_repeat.';';}
if($bg_position) {echo 'background-position:' .$bg_position.';';}
if($bg_attachment) {echo 'background-attachment:' .$bg_attachment.';';}
echo '}';

/* Main Text Typography */
echo 'body {';
if($body_font){echo 'font-family:"' .$body_font.'",helvetica,arial,sans-serif;';}
if($body_color){echo 'color:' .$body_color.';';}
if($body_size){echo 'font-size:'.$body_size.'px;';}
if($body_line){echo 'line-height:' .$body_line.';';}
echo '}';
if($body_line){echo 'h4, h5, h6 {line-height:' .$body_line.';}';}

/* Headings Typography */
echo 'h1,h2,h3 {';
if($heading_font){echo 'font-family:"' .$heading_font.'",helvetica,arial,sans-serif;';}
if($heading_color){echo 'color:' .$heading_color.';';}
echo'}';

/* Small Color */
if($small_color){echo 'h1 small, h2 small, h3 small, h4 small, h5 small, h6 small, blockquote small, .entry-meta {color:' .$small_color.';}';}

/* Link Color */
if($link_color){echo 'a,.site-title a:hover,.main-navigation a:hover {color:'.$link_color.';} .main-navigation .sub-menu a:hover,.main-navigation ul.children a:hover {background:'.$link_color.'}@media (max-width:768px) {.menu a:hover {text-decoration:none;background:'.$link_color.';}}';}

/* Border Color */
if($border_color){echo '.hentry,.wp-caption,#main,.page .hentry,.widget li,#colophon,blockquote,input[type="text"], input[type="email"], input[type="password"], textarea,.nav-previous a,.nav-next a,a.more-link {border-color:' .$border_color.';}';}

/* Shop Layout */
if($shop_layout == 'fourcolumn'){
echo '.default_product_display:nth-child(4n+4) {margin-right:0;}.default_product_display:nth-child(4n+5) {clear:both;}.default_product_display {float: left;width: 22%;margin: 0 4% 10px 0;}';
} else {echo '.default_product_display:nth-child(3n+3) {margin-right:0;}.default_product_display:nth-child(3n+4) {clear:both;}.default_product_display {float: left;width: 31%;margin: 0 3.5% 10px 0;}';}

do_action('sfpaper_add_to_custom_style');

?>
</style>
<?php }
/*
==========================================================
GOOGLE FONTS
==========================================================
*/
/*add_action('sfpaper_head', 'sfpaper_add_google_fonts', 5);
function sfpaper_add_google_fonts() {
	$googlefonts = false;
	$webfonts = array('Helvetica Neue','Georgia','Lucida Bright','Arial','Times New Roman');
	
	$logofont = get_theme_mod('logo_font_family', 'Helvetica Neue');
	$bodyfont = get_theme_mod('body_font_family', 'Helvetica Neue');
	$headingfont = get_theme_mod('heading_font_family', 'Helvetica Neue');
	
	if (!in_array($logofont, $webfonts)) {$googlefonts .= $logofont.'|';}
	if (!in_array($bodyfont, $webfonts)) {$googlefonts .= $bodyfont.'|';}
	if (!in_array($headingfont, $webfonts)) {$googlefonts .= $headingfont.'|';}
	
	if(!$googlefonts == false) {
		echo '<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family='.$googlefonts.'" media="screen">';
	}
}*/
