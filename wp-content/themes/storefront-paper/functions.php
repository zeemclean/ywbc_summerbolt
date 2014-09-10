<?php
/**
 * sfpaper functions and definitions
 *
 * @package sfpaper
 * @since sfpaper 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since sfpaper 1.0
 */
if ( ! isset( $content_width ) )
	$content_width = 680; /* pixels */

if ( ! function_exists( 'sfpaper_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since sfpaper 1.0
 */
function sfpaper_setup() {
	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on sfpaper, use a find and replace
	 * to change 'sfpaper' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'sfpaper', get_template_directory() . '/languages' );

	/* Add default posts and comments RSS feed links to head */
	add_theme_support( 'automatic-feed-links' );

	/* Enable support for Post Thumbnails */
	add_theme_support( 'post-thumbnails' );

	/* This theme uses wp_nav_menu() in one location. */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'sfpaper' ),
	) );

	/* Add support for the Aside Post Formats */
	add_theme_support( 'post-formats', array( 'aside', ) );
}
endif; // sfpaper_setup
add_action( 'after_setup_theme', 'sfpaper_setup' );

/* Custom template tags for this theme. */
require( get_template_directory() . '/inc/template-tags.php' );

/* Custom functions that act independently of the theme templates */
require( get_template_directory() . '/inc/extras.php' );

/* Load custom sfpaper Theme Customizer options. */
require( get_template_directory() . '/inc/template-customizer.php' );

/**
 * Register widgetized area and update sidebar with default widgets
 *
 * @since sfpaper 1.0
 */
function sfpaper_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Sidebar', 'sfpaper' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
add_action( 'widgets_init', 'sfpaper_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function sfpaper_scripts() {
if ( ! is_admin() ) {
		wp_enqueue_style( 'style', get_stylesheet_uri() );
		wp_register_style('titillium-font', 'http://fonts.googleapis.com/css?family=Titillium+Web:300,600,700');
            wp_enqueue_style( 'titillium-font');
     }

	wp_enqueue_script( 'small-menu', get_template_directory_uri() . '/js/small-menu.js', array( 'jquery' ), '20120206', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}
}
add_action( 'wp_enqueue_scripts', 'sfpaper_scripts' );


if( class_exists( 'WP_eCommerce' ) )  {
	/**
	 * Deregister WPEC styles
	 */
	 
	add_action('wp_enqueue_scripts',
	'sfpaper_deregister_wp_e_commerce_dynamic_style', 30);
	
	function sfpaper_deregister_wp_e_commerce_dynamic_style() {
		wp_deregister_style( 'wpsc-theme-css' );
		wp_deregister_style( 'wpsc-theme-css-compatibility' );
		wp_deregister_style( 'wp-e-commerce-dynamic' );
		wp_deregister_style( 'wpsc-thickbox' );
		wp_deregister_script( 'wpsc_colorbox' );
	}
	
	add_action('wpsc_product_after_featured_image', 'sfpaper_product_image_gallery');
	
	function sfpaper_product_image_gallery() {
		if(wpsc_is_single_product()) {
			global $wp_query;
			global $post;
			$id = $post->ID;
			if(has_post_thumbnail()){$featured_image = get_post_thumbnail_id($id);}
			$args = array( 'post_type' => 'attachment', 'orderby' => 'menu_order', 'order' => 'ASC', 'post_mime_type' => 'image' ,'post_status' => null, 'post_parent' => $post->ID, 'exclude' => $featured_image,'numberposts' => -1 );
			$attachments = get_posts($args);
			if ($attachments) {
			$tabs = 1;
			echo '<div class="storefront-wpec-product-add-images">';
			//echo '<h3>';
			//_e( 'Additional Images', 'storefront' );
			//echo '</h3>';
			$thumbwidth = 125;
			$thumbheight = 125;
			foreach ( $attachments as $attachment ) { 
					$thumbnail = wp_get_attachment_url( $attachment->ID , false );
			$image = sfpaper_resize( $attachment->ID, '', $thumbwidth, $thumbheight, true );
			
			?>
		     	      <a rel="<?php echo wpsc_the_product_title(); ?>" class="preview_link thumbnail" href="<?php echo wp_get_attachment_url( $attachment->ID , false ); ?>"><img class="product_image" src="<?php echo $image['url']; ?>" alt="<?php the_title(); ?>" width="<?php echo $thumbwidth;?>" height="<?php echo $thumbwidth;?>" border="0" /></a>
			<?php }
			echo "<div class='clear'></div></div>";
			}
		}
	}
	
	/*
	 * Resize images dynamically using wp built in functions
	 * Victor Teixeira
	 *
	 * php 5.2+
	 *
	 * Exemplo de uso:
	 *
	 * <?php
	 * $thumb = get_post_thumbnail_id();
	 * $image = sfpaper_resize( $thumb, '', 140, 110, true );
	 * ?>
	 * <img src="<?php echo $image[url]; ?>" width="<?php echo $image[width]; ?>" height="<?php echo $image[height]; ?>" />
	 *
	 * @param int $attach_id
	 * @param string $img_url
	 * @param int $width
	 * @param int $height
	 * @param bool $crop
	 * @return array
	 */
	if ( ! function_exists( 'sfpaper_resize' ) ) {
		function sfpaper_resize( $attach_id = null, $img_url = null, $width, $height, $crop = false ) {
	
			// Cast $width and $height to integer
			$width = intval( $width );
			$height = intval( $height );
	
			// this is an attachment, so we have the ID
			if ( $attach_id ) {
				$image_src = wp_get_attachment_image_src( $attach_id, 'full' );
				$file_path = get_attached_file( $attach_id );
			// this is not an attachment, let's use the image url
			} else if ( $img_url ) {
				$file_path = parse_url( esc_url( $img_url ) );
				$file_path = $_SERVER['DOCUMENT_ROOT'] . $file_path['path'];
	
				//$file_path = ltrim( $file_path['path'], '/' );
				//$file_path = rtrim( ABSPATH, '/' ).$file_path['path'];
	
				$orig_size = getimagesize( $file_path );
	
				$image_src[0] = $img_url;
				$image_src[1] = $orig_size[0];
				$image_src[2] = $orig_size[1];
			}
	
			$file_info = pathinfo( $file_path );
	
			// check if file exists
			if ( !isset( $file_info['dirname'] ) && !isset( $file_info['filename'] ) && !isset( $file_info['extension'] )  )
				return;
			
			$base_file = $file_info['dirname'].'/'.$file_info['filename'].'.'.$file_info['extension'];
			if ( !file_exists($base_file) )
				return;
	
			$extension = '.'. $file_info['extension'];
	
			// the image path without the extension
			$no_ext_path = $file_info['dirname'].'/'.$file_info['filename'];
	
			$cropped_img_path = $no_ext_path.'-'.$width.'x'.$height.$extension;
	
			// checking if the file size is larger than the target size
			// if it is smaller or the same size, stop right here and return
			if ( $image_src[1] > $width ) {
				// the file is larger, check if the resized version already exists (for $crop = true but will also work for $crop = false if the sizes match)
				if ( file_exists( $cropped_img_path ) ) {
					$cropped_img_url = str_replace( basename( $image_src[0] ), basename( $cropped_img_path ), $image_src[0] );
	
					$vt_image = array (
						'url' => $cropped_img_url,
						'width' => $width,
						'height' => $height
					);
					return $vt_image;
				}
	
				// $crop = false or no height set
				if ( $crop == false OR !$height ) {
					// calculate the size proportionally
					$proportional_size = wp_constrain_dimensions( $image_src[1], $image_src[2], $width, $height );
					$resized_img_path = $no_ext_path.'-'.$proportional_size[0].'x'.$proportional_size[1].$extension;
	
					// checking if the file already exists
					if ( file_exists( $resized_img_path ) ) {
						$resized_img_url = str_replace( basename( $image_src[0] ), basename( $resized_img_path ), $image_src[0] );
	
						$vt_image = array (
							'url' => $resized_img_url,
							'width' => $proportional_size[0],
							'height' => $proportional_size[1]
						);
						return $vt_image;
					}
				}
	
				// check if image width is smaller than set width
				$img_size = getimagesize( $file_path );
				if ( $img_size[0] <= $width ) $width = $img_size[0];
				
				// Check if GD Library installed
				if ( ! function_exists ( 'imagecreatetruecolor' ) ) {
				    echo 'GD Library Error: imagecreatetruecolor does not exist - please contact your web host and ask them to install the GD library';
				    return;
				}
	
				// no cache files - let's finally resize it
				if ( function_exists( 'wp_get_image_editor' ) ) {
					$image = wp_get_image_editor( $file_path );
					if ( ! is_wp_error( $image ) ) {
						$image->resize( $width, $height, $crop );
						$save_data = $image->save();
						if ( isset( $save_data['path'] ) ) $new_img_path = $save_data['path'];
					}
				} else {
					$new_img_path = wp_get_image_editor( $file_path, $width, $height, $crop );
				}		
				
				$new_img_size = getimagesize( $new_img_path );
				$new_img = str_replace( basename( $image_src[0] ), basename( $new_img_path ), $image_src[0] );
	
				// resized output
				$vt_image = array (
					'url' => $new_img,
					'width' => $new_img_size[0],
					'height' => $new_img_size[1]
				);
	
				return $vt_image;
			}
	
			// default output - without resizing
			$vt_image = array (
				'url' => $image_src[0],
				'width' => $width,
				'height' => $height
			);
	
			return $vt_image;
		}
	}
	
	/* Ajax Update Cart
	----------------------------------------------- */
	
	/**
	* add a custom AJAX request handler
	*/
	function sfpaper_theme_wpsc_cart_update() {
	    $data = array(
	        'cart_count' => wpsc_cart_item_count(),
	        'cart_total' => wpsc_cart_total_widget(),
	    );
	    echo json_encode($data);
	    exit;
	}
	add_action('wp_ajax_theme_wpsc_cart_update', 'sfpaper_theme_wpsc_cart_update');
	add_action('wp_ajax_nopriv_theme_wpsc_cart_update', 'sfpaper_theme_wpsc_cart_update');
	 
	/**
	* add JavaScript event handler to the page footer
	*/
	function sfpaper_wpsc_footer() {
	    if (!is_admin()) {
	    ?>
	 
	    <script>
	    jQuery(function($) {
	        /**
	        * catch WP e-Commerce cart update event
	        * @param {jQuery.Event} event
	        */
	        $(document).on("wpsc_fancy_notification", function(event) {
	            updateMinicart();
	        });
	 
	        /**
	        * catch AJAX complete events, to catch clear cart
	        * @param {jQuery.Event} event
	        * @param {jqXHR} xhr XmlHttpRequest object
	        * @param {Object} ajaxOpts options for the AJAX request
	        */
	        $(document).ajaxComplete(function(event, xhr, ajaxOpts) {
	            // check for WP e-Commerce "empty_cart" action
	            if ("data" in ajaxOpts && ajaxOpts.data.indexOf("action=empty_cart") != -1) {
	                updateMinicart();
	            }
	        });
	 
	        /**
	        * submit AJAX request to update mini-cart
	        */
	        function updateMinicart() {
	            // ask server for updated data
	            $.ajax({
	                url: "<?php echo admin_url('admin-ajax.php'); ?>",
	                cache: false,
	                dataType: "json",
	                data: { action: "theme_wpsc_cart_update" },
	                success: function(data) {
	                    // update our mini-cart elements
	                    $("#theme-checkout-count").html(data.cart_count);
	                    $("#theme-checkout-total").html(data.cart_total);
	                }
	            });
	        }
	 
	    });
	    </script>
	 
	    <?php
	    }
	}
	add_action('wp_footer', 'sfpaper_wpsc_footer');
	
	/*
	===============================================================
	ADD CART TO NAVIGATION
	===============================================================
	*/
	//Add login/logout link to naviagation menu
	function sfpaper_add_login_out_item_to_menu( $items, $args ){
	
		//change theme location with your them location name
		if( is_admin() ||  $args->theme_location != 'primary' )
			return $items; 
	
			return $items.= '<li id="cart-icon" class="menu-item menu-type-link"><a class="button" href="'.get_option('shopping_cart_url').'"><span id="theme-checkout-total">'. wpsc_cart_total_widget(false,false,false) . '</span></a></li>';
	}
	add_filter( 'wp_nav_menu_items', 'sfpaper_add_login_out_item_to_menu', 50, 2 );
}

/*
==========================================================
REQUIRED PLUGINS
==========================================================
*/
require_once dirname( __FILE__ ) . '/class-tgm-plugin-activation.php';
add_action( 'tgmpa_register', 'sfpaper_register_required_plugins' );
function sfpaper_register_required_plugins() {

    /**
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(

        // This is an example of how to include a plugin from the WordPress Plugin Repository.
        array(
            'name'      => 'WP e-Commerce',
            'slug'      => 'wp-e-commerce',
            'required'  => false,
        ),
        array(
            'name'      => 'Meta Slider',
            'slug'      => 'ml-slider',
            'required'  => false,
        ),
        array(
            'name'      => 'Easy Fancybox',
            'slug'      => 'easy-fancybox',
            'required'  => false,
        ),

    );

    /**
     * Array of configuration settings. Amend each line as needed.
     * If you want the default strings to be available under your own theme domain,
     * leave the strings uncommented.
     * Some of the strings are added into a sprintf, so see the comments at the
     * end of each line for what each argument will be.
     */

    tgmpa( $plugins);

}