<?php
/**
 * fCorpo functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 * @subpackage fCorpo
 * @author tishonator
 * @since fCorpo 1.0.0
 *
 */

require_once( trailingslashit( get_template_directory() ) . 'customize-pro/class-customize.php' );

if ( ! function_exists( 'fcorpo_setup' ) ) {
	/**
	 * fCorpo setup.
	 *
	 * Set up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support post thumbnails.
	 *
	 */
	function fcorpo_setup() {

		load_theme_textdomain( 'fcorpo', get_template_directory() . '/languages' );

		add_theme_support( "title-tag" );

		// add the visual editor to resemble the theme style
		add_editor_style( array( 'css/editor-style.css', get_template_directory_uri() . '/style.css' ) );

		// This theme uses wp_nav_menu() in two locations.
		register_nav_menus( array(
			'top'   => __( 'top menu', 'fcorpo' ),
			'primary'   => __( 'Primary Menu', 'fcorpo' ),
		) );

		// add Custom background				 
		add_theme_support( 'custom-background', 
					   array ('default-color'  => '#FFFFFF')
					 );


		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 1200, 0, true );

		global $content_width;
		if ( ! isset( $content_width ) )
			$content_width = 900;

		add_theme_support( 'automatic-feed-links' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'comment-form', 'comment-list',
		) );

		// add custom header
	    add_theme_support( 'custom-header', array (
	                       'default-image'          => '',
	                       'random-default'         => '',
	                       'flex-height'            => true,
	                       'flex-width'             => true,
	                       'uploads'                => true,
	                       'width'                  => 900,
	                       'height'                 => 100,
	                       'default-text-color'        => '#000000',
	                       'wp-head-callback'       => 'fcorpo_header_style',
	                    ) );

	    // add custom logo
	    add_theme_support( 'custom-logo', array (
	                       'width'                  => 145,
	                       'height'                 => 36,
	                       'flex-height'            => true,
	                       'flex-width'             => true,
	                    ) );

		
	}
} // fcorpo_setup
add_action( 'after_setup_theme', 'fcorpo_setup' );

/**
 * the main function to load scripts in the fCorpo theme
 * if you add a new load of script, style, etc. you can use that function
 * instead of adding a new wp_enqueue_scripts action for it.
 */
function fcorpo_load_scripts() {

	// load main stylesheet.
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css', array( ) );
	wp_enqueue_style( 'animate-css', get_template_directory_uri() . '/css/animate.css', array( ) );
	wp_enqueue_style( 'fcorpo-style', get_stylesheet_uri(), array( ) );
	
	wp_enqueue_style( 'fcorpo-fonts', fcorpo_fonts_url(), array(), null );
	
	// Load thread comments reply script	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	
	// Load Utilities JS Script
	wp_enqueue_script( 'viewportchecker', get_template_directory_uri() . '/js/viewportchecker.js',
			array( 'jquery' ) );

	wp_enqueue_script( 'fcorpo-js', get_template_directory_uri() . '/js/utilities.js',
			array( 'jquery', 'viewportchecker' ) );

	$data = array(
		'loading_effect' => ( get_theme_mod('fcorpo_animations_display', 1) == 1 ),
	);
	wp_localize_script('fcorpo-js', 'fcorpo_options', $data);

	// Load Slider JS Scripts
	wp_enqueue_script( 'jquery.mobile.customized', get_template_directory_uri() . '/js/jquery.mobile.customized.min.js', array( 'jquery' ) );
	wp_enqueue_script( 'jquery.easing.1.3', get_template_directory_uri() . '/js/jquery.easing.1.3.js', array( 'jquery' ) );
	wp_enqueue_script( 'camera', get_template_directory_uri() . '/js/camera.min.js', array( 'jquery' ) );
}
add_action( 'wp_enqueue_scripts', 'fcorpo_load_scripts' );

/**
 *	Load google font url used in the fCorpo theme
 */
function fcorpo_fonts_url() {

    $fonts_url = '';
 
    /* Translators: If there are characters in your language that are not
    * supported by PT Sans, translate this to 'off'. Do not translate
    * into your own language.
    */
    $cantarell = _x( 'on', 'Dosis font: on or off', 'fcorpo' );

    if ( 'off' !== $cantarell ) {
        $font_families = array();
 
        $font_families[] = 'Dosis::400,300,200,500,700';
 
        $query_args = array(
            'family' => urlencode( implode( '|', $font_families ) ),
            //t 'subset' => urlencode( 'latin,cyrillic-ext,cyrillic,latin-ext' ),
        );
 
        $fonts_url = add_query_arg( $query_args, '//fonts.googleapis.com/css' );
    }
 
    return $fonts_url;
}

/**
 * Display website's logo image
 */
function fcorpo_show_website_logo_image_and_title() {

	if ( has_custom_logo() ) {

        the_custom_logo();
    }

    $header_text_color = get_header_textcolor();

    if ( 'blank' !== $header_text_color ) {
    
        echo '<div id="site-identity">';
        echo '<a href="' . esc_url( home_url('/') ) . '" title="' . esc_attr( get_bloginfo('name') ) . '">';
        echo '<h1 class="entry-title">' . esc_html( get_bloginfo('name') ) . '</h1>';
        echo '</a>';
        echo '<strong>' . esc_html( get_bloginfo('description') ) . '</strong>';
        echo '</div>';
    }
}

/**
 *	Displays the header phone.
 */
function fcorpo_show_header_phone() {

	$phone = get_theme_mod('fcorpo_header_phone', '1.555.555.555');

	if ( !empty( $phone ) ) {

		echo '<span id="header-phone">' . esc_html($phone) . '</span>';
	}
}

/**
 *	Displays the header email.
 */
function fcorpo_show_header_email() {

	$email = get_theme_mod('fcorpo_header_email', 'info@yoursite.com');

	if ( !empty( $email ) ) {

		echo '<span id="header-email"><a href="mailto:' . antispambot($email, 1) . '" title="' . esc_attr($email) . '">'
				. esc_html($email) . '</a></span>';
	}
}

/**
 *	Displays the copyright text.
 */
function fcorpo_show_copyright_text() {

	$footerText = get_theme_mod('fcorpo_footer_copyright', null);

	if ( !empty( $footerText ) ) {

		echo esc_html( $footerText ) . ' | ';		
	}
}

/**
 *	widgets-init action handler. Used to register widgets and register widget areas
 */
function fcorpo_widgets_init() {
	
	// Register Sidebar Widget.
	register_sidebar( array (
						'name'	 		 =>	 __( 'Sidebar Widget Area', 'fcorpo'),
						'id'		 	 =>	 'sidebar-widget-area',
						'description'	 =>  __( 'The sidebar widget area', 'fcorpo'),
						'before_widget'	 =>  '',
						'after_widget'	 =>  '',
						'before_title'	 =>  '<div class="sidebar-before-title"></div><h3 class="sidebar-title">',
						'after_title'	 =>  '</h3><div class="sidebar-after-title"></div>',
					) );

	/**
	 * Add Homepage Columns Widget areas
	 */
	register_sidebar( array (
							'name'			 =>  __( 'Homepage Column #1', 'fcorpo' ),
							'id' 			 =>  'homepage-column-1-widget-area',
							'description'	 =>  __( 'The Homepage Column #1 widget area', 'fcorpo' ),
							'before_widget'  =>  '',
							'after_widget'	 =>  '',
							'before_title'	 =>  '<h2 class="sidebar-title">',
							'after_title'	 =>  '</h2><div class="sidebar-after-title"></div>',
						) );
						
	register_sidebar( array (
							'name'			 =>  __( 'Homepage Column #2', 'fcorpo' ),
							'id' 			 =>  'homepage-column-2-widget-area',
							'description'	 =>  __( 'The Homepage Column #2 widget area', 'fcorpo' ),
							'before_widget'  =>  '',
							'after_widget'	 =>  '',
							'before_title'	 =>  '<h2 class="sidebar-title">',
							'after_title'	 =>  '</h2><div class="sidebar-after-title"></div>',
						) );

	register_sidebar( array (
							'name'			 =>  __( 'Homepage Column #3', 'fcorpo' ),
							'id' 			 =>  'homepage-column-3-widget-area',
							'description'	 =>  __( 'The Homepage Column #3 widget area', 'fcorpo' ),
							'before_widget'  =>  '',
							'after_widget'	 =>  '',
							'before_title'	 =>  '<h2 class="sidebar-title">',
							'after_title'	 =>  '</h2><div class="sidebar-after-title"></div>',
						) );

	// Register Footer Column #1
	register_sidebar( array (
							'name'			 =>  __( 'Footer Column #1', 'fcorpo' ),
							'id' 			 =>  'footer-column-1-widget-area',
							'description'	 =>  __( 'The Footer Column #1 widget area', 'fcorpo' ),
							'before_widget'  =>  '',
							'after_widget'	 =>  '',
							'before_title'	 =>  '<h2 class="footer-title">',
							'after_title'	 =>  '</h2><div class="footer-after-title"></div>',
						) );
	
	// Register Footer Column #2
	register_sidebar( array (
							'name'			 =>  __( 'Footer Column #2', 'fcorpo' ),
							'id' 			 =>  'footer-column-2-widget-area',
							'description'	 =>  __( 'The Footer Column #2 widget area', 'fcorpo' ),
							'before_widget'  =>  '',
							'after_widget'	 =>  '',
							'before_title'	 =>  '<h2 class="footer-title">',
							'after_title'	 =>  '</h2><div class="footer-after-title"></div>',
						) );
	
	// Register Footer Column #3
	register_sidebar( array (
							'name'			 =>  __( 'Footer Column #3', 'fcorpo' ),
							'id' 			 =>  'footer-column-3-widget-area',
							'description'	 =>  __( 'The Footer Column #3 widget area', 'fcorpo' ),
							'before_widget'  =>  '',
							'after_widget'	 =>  '',
							'before_title'	 =>  '<h2 class="footer-title">',
							'after_title'	 =>  '</h2><div class="footer-after-title"></div>',
						) );
}
add_action( 'widgets_init', 'fcorpo_widgets_init' );

/**
 * Displays the slider
 */
function fcorpo_display_slider() { ?>
	 
	<div class="camera_wrap camera_emboss" id="camera_wrap">
		<?php
			// display slides
			for ( $i = 1; $i <= 3; ++$i ) {

					$defaultSlideContent = __( '<h3>This is Default Slide Title</h3><p>You can completely customize Slide Background Image, Title, Text, Link URL and Text.</p><a class="btn" title="Read more" href="#">Read more</a>', 'fcorpo' );
					
					$defaultSlideImage = get_template_directory_uri().'/images/slider/' . $i .'.jpg';

					$slideContent = get_theme_mod( 'fcorpo_slide'.$i.'_content', html_entity_decode( $defaultSlideContent ) );
					$slideImage = get_theme_mod( 'fcorpo_slide'.$i.'_image', $defaultSlideImage );

				?>

					<div data-thumb="<?php echo esc_attr( $slideImage ); ?>" data-src="<?php echo esc_attr( $slideImage ); ?>">
						<div class="camera_caption fadeFromBottom">
							<?php echo $slideContent; ?>
						</div>
					</div>
<?php		} ?>
	</div><!-- #camera_wrap -->
<?php  
}

function fcorpo_display_social_sites() {

	echo '<ul class="header-social-widget">';

	$socialURL = get_theme_mod('fcorpo_social_facebook', '#');
	if ( !empty($socialURL) ) {

		echo '<li><a href="' . esc_url( $socialURL ) . '" title="' . __('Follow us on Facebook', 'fcorpo') . '" class="facebook16"></a>';
	}

	$socialURL = get_theme_mod('fcorpo_social_google', '#');
	if ( !empty($socialURL) ) {

		echo '<li><a href="' . esc_url( $socialURL ) . '" title="' . __('Follow us on Google+', 'fcorpo') . '" class="google16"></a>';
	}

	$socialURL = get_theme_mod('fcorpo_social_twitter', '#');
	if ( !empty($socialURL) ) {

		echo '<li><a href="' . esc_url( $socialURL ) . '" title="' . __('Follow us on Twitter', 'fcorpo') . '" class="twitter16"></a>';
	}

	$socialURL = get_theme_mod('fcorpo_social_linkedin', '#');
	if ( !empty($socialURL) ) {

		echo '<li><a href="' . esc_url( $socialURL ) . '" title="' . __('Follow us on LinkedIn', 'fcorpo') . '" class="linkedin16"></a>';
	}

	$socialURL = get_theme_mod('fcorpo_social_instagram', '#');
	if ( !empty($socialURL) ) {

		echo '<li><a href="' . esc_url( $socialURL ) . '" title="' . __('Follow us on Instagram', 'fcorpo') . '" class="instagram16"></a>';
	}

	$socialURL = get_theme_mod('fcorpo_social_rss', get_bloginfo( 'rss2_url' ));
	if ( !empty($socialURL) ) {

		echo '<li><a href="' . esc_url( $socialURL ) . '" title="' . __('Follow our RSS Feeds', 'fcorpo') . '" class="rss16"></a>';
	}

	$socialURL = get_theme_mod('fcorpo_social_tumblr', '#');
	if ( !empty($socialURL) ) {

		echo '<li><a href="' . esc_url( $socialURL ) . '" title="' . __('Follow us on Tumblr', 'fcorpo') . '" class="tumblr16"></a>';
	}

	$socialURL = get_theme_mod('fcorpo_social_youtube', '#');
	if ( !empty($socialURL) ) {

		echo '<li><a href="' . esc_url( $socialURL ) . '" title="' . __('Follow us on Youtube', 'fcorpo') . '" class="youtube16"></a>';
	}

	$socialURL = get_theme_mod('fcorpo_social_pinterest', '#');
	if ( !empty($socialURL) ) {

		echo '<li><a href="' . esc_url( $socialURL ) . '" title="' . __('Follow us on Pinterest', 'fcorpo') . '" class="pinterest16"></a>';
	}

	$socialURL = get_theme_mod('fcorpo_social_vk', '#');
	if ( !empty($socialURL) ) {

		echo '<li><a href="' . esc_url( $socialURL ) . '" title="' . __('Follow us on VK', 'fcorpo') . '" class="vk16"></a>';
	}

	$socialURL = get_theme_mod('fcorpo_social_flickr', '#');
	if ( !empty($socialURL) ) {

		echo '<li><a href="' . esc_url( $socialURL ) . '" title="' . __('Follow us on Flickr', 'fcorpo') . '" class="flickr16"></a>';
	}

	$socialURL = get_theme_mod('fcorpo_social_vine', '#');
	if ( !empty($socialURL) ) {

		echo '<li><a href="' . esc_url( $socialURL ) . '" title="' . __('Follow us on Vine', 'fcorpo') . '" class="vine16"></a>';
	}

	echo '</ul>';
}

/**
 * Register theme settings in the customizer
 */
function fcorpo_customize_register( $wp_customize ) {

	/**
	 * Add Slider Section
	 */
	$wp_customize->add_section(
		'fcorpo_slider_section',
		array(
			'title'       => __( 'Slider', 'fcorpo' ),
			'capability'  => 'edit_theme_options',
		)
	);
	
	for ($i = 1; $i <= 3; ++$i) {
	
		$slideContentId = 'fcorpo_slide'.$i.'_content';
		$slideImageId = 'fcorpo_slide'.$i.'_image';
		$defaultSliderImagePath = get_template_directory_uri().'/images/slider/'.$i.'.jpg';
	
		// Add Slide Content
		$wp_customize->add_setting(
			$slideContentId,
			array(
				'default'           => __( '<h2>This is Default Slide Title</h2><p>You can completely customize Slide Background Image, Title, Text, Link URL and Text.</p><a class="btn" title="Read more" href="#">Read more</a>', 'fcorpo' ),
				'sanitize_callback' => 'force_balance_tags',
			)
		);
		
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, $slideContentId,
									array(
										'label'          => sprintf( esc_html__( 'Slide #%s Content', 'fcorpo' ), $i ),
										'section'        => 'fcorpo_slider_section',
										'settings'       => $slideContentId,
										'type'           => 'textarea',
										)
									)
		);
		
		// Add Slide Background Image
		$wp_customize->add_setting( $slideImageId,
			array(
				'default' => $defaultSliderImagePath,
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $slideImageId,
				array(
					'label'   	 => sprintf( esc_html__( 'Slide #%s Image', 'fcorpo' ), $i ),
					'section' 	 => 'fcorpo_slider_section',
					'settings'   => $slideImageId,
				) 
			)
		);
	}

	/**
	 * Add Footer Section
	 */
	$wp_customize->add_section(
		'fcorpo_header_and_footer_section',
		array(
			'title'       => __( 'Header and Footer', 'fcorpo' ),
			'capability'  => 'edit_theme_options',
		)
	);

	// Add header phone
	$wp_customize->add_setting(
		'fcorpo_header_phone',
		array(
		    'default'           => '1.555.555.555',
		    'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'fcorpo_header_phone',
        array(
            'label'          => __( 'Your phone to appear in the website header', 'fcorpo' ),
            'section'        => 'fcorpo_header_and_footer_section',
            'settings'       => 'fcorpo_header_phone',
            'type'           => 'text',
            )
        )
	);

	// Add header email
	$wp_customize->add_setting(
		'fcorpo_header_email',
		array(
		    'default'           => 'info@yoursite.com',
		    'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'fcorpo_header_email',
        array(
            'label'          => __( 'Your e-mail to appear in the website header', 'fcorpo' ),
            'section'        => 'fcorpo_header_and_footer_section',
            'settings'       => 'fcorpo_header_email',
            'type'           => 'text',
            )
        )
	);
	
	// Add footer copyright text
	$wp_customize->add_setting(
		'fcorpo_footer_copyright',
		array(
		    'default'           => '',
		    'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'fcorpo_footer_copyright',
        array(
            'label'          => __( 'Copyright Text', 'fcorpo' ),
            'section'        => 'fcorpo_header_and_footer_section',
            'settings'       => 'fcorpo_footer_copyright',
            'type'           => 'text',
            )
        )
	);

	/**
	 * Add Social Sites Section
	 */
	$wp_customize->add_section(
		'fcorpo_social_section',
		array(
			'title'       => __( 'Social Sites', 'fcorpo' ),
			'capability'  => 'edit_theme_options',
		)
	);
	
	// Add facebook url
	$wp_customize->add_setting(
		'fcorpo_social_facebook',
		array(
		    'default'           => '#',
		    'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'fcorpo_social_facebook',
        array(
            'label'          => __( 'Facebook Page URL', 'fcorpo' ),
            'section'        => 'fcorpo_social_section',
            'settings'       => 'fcorpo_social_facebook',
            'type'           => 'text',
            )
        )
	);

	// Add google+ url
	$wp_customize->add_setting(
		'fcorpo_social_google',
		array(
		    'default'           => '#',
		    'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'fcorpo_social_google',
        array(
            'label'          => __( 'Google+ Page URL', 'fcorpo' ),
            'section'        => 'fcorpo_social_section',
            'settings'       => 'fcorpo_social_google',
            'type'           => 'text',
            )
        )
	);

	// Add Twitter url
	$wp_customize->add_setting(
		'fcorpo_social_twitter',
		array(
		    'default'           => '#',
		    'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'fcorpo_social_twitter',
        array(
            'label'          => __( 'Twitter URL', 'fcorpo' ),
            'section'        => 'fcorpo_social_section',
            'settings'       => 'fcorpo_social_twitter',
            'type'           => 'text',
            )
        )
	);

	// Add LinkedIn url
	$wp_customize->add_setting(
		'fcorpo_social_linkedin',
		array(
		    'default'           => '#',
		    'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'fcorpo_social_linkedin',
        array(
            'label'          => __( 'LinkedIn URL', 'fcorpo' ),
            'section'        => 'fcorpo_social_section',
            'settings'       => 'fcorpo_social_linkedin',
            'type'           => 'text',
            )
        )
	);

	// Add Instagram url
	$wp_customize->add_setting(
		'fcorpo_social_instagram',
		array(
		    'default'           => '#',
		    'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'fcorpo_social_instagram',
        array(
            'label'          => __( 'LinkedIn URL', 'fcorpo' ),
            'section'        => 'fcorpo_social_section',
            'settings'       => 'fcorpo_social_instagram',
            'type'           => 'text',
            )
        )
	);

	// Add RSS Feeds url
	$wp_customize->add_setting(
		'fcorpo_social_rss',
		array(
		    'default'           => get_bloginfo( 'rss2_url' ),
		    'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'fcorpo_social_rss',
        array(
            'label'          => __( 'RSS Feeds URL', 'fcorpo' ),
            'section'        => 'fcorpo_social_section',
            'settings'       => 'fcorpo_social_rss',
            'type'           => 'text',
            )
        )
	);

	// Add Tumblr url
	$wp_customize->add_setting(
		'fcorpo_social_tumblr',
		array(
		    'default'           => '#',
		    'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'fcorpo_social_tumblr',
        array(
            'label'          => __( 'Tumblr URL', 'fcorpo' ),
            'section'        => 'fcorpo_social_section',
            'settings'       => 'fcorpo_social_tumblr',
            'type'           => 'text',
            )
        )
	);

	// Add YouTube channel url
	$wp_customize->add_setting(
		'fcorpo_social_youtube',
		array(
		    'default'           => '#',
		    'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'fcorpo_social_youtube',
        array(
            'label'          => __( 'YouTube channel URL', 'fcorpo' ),
            'section'        => 'fcorpo_social_section',
            'settings'       => 'fcorpo_social_youtube',
            'type'           => 'text',
            )
        )
	);

	// Add Pinterest url
	$wp_customize->add_setting(
		'fcorpo_social_pinterest',
		array(
		    'default'           => '#',
		    'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'fcorpo_social_pinterest',
        array(
            'label'          => __( 'Pinterest URL', 'fcorpo' ),
            'section'        => 'fcorpo_social_section',
            'settings'       => 'fcorpo_social_pinterest',
            'type'           => 'text',
            )
        )
	);

	// Add VK url
	$wp_customize->add_setting(
		'fcorpo_social_vk',
		array(
		    'default'           => '#',
		    'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'fcorpo_social_vk',
        array(
            'label'          => __( 'VK URL', 'fcorpo' ),
            'section'        => 'fcorpo_social_section',
            'settings'       => 'fcorpo_social_vk',
            'type'           => 'text',
            )
        )
	);

	// Add Flickr url
	$wp_customize->add_setting(
		'fcorpo_social_flickr',
		array(
		    'default'           => '#',
		    'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'fcorpo_social_flickr',
        array(
            'label'          => __( 'Flickr URL', 'fcorpo' ),
            'section'        => 'fcorpo_social_section',
            'settings'       => 'fcorpo_social_flickr',
            'type'           => 'text',
            )
        )
	);

	// Add Vine url
	$wp_customize->add_setting(
		'fcorpo_social_vine',
		array(
		    'default'           => '#',
		    'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'fcorpo_social_vine',
        array(
            'label'          => __( 'Vine URL', 'fcorpo' ),
            'section'        => 'fcorpo_social_section',
            'settings'       => 'fcorpo_social_vine',
            'type'           => 'text',
            )
        )
	);

	/**
	 * Add Animations Section
	 */
	$wp_customize->add_section(
		'fcorpo_animations_display',
		array(
			'title'       => __( 'Animations', 'fcorpo' ),
			'capability'  => 'edit_theme_options',
		)
	);

	// Add display Animations option
	$wp_customize->add_setting(
			'fcorpo_animations_display',
			array(
					'default'           => 1,
					'sanitize_callback' => 'esc_attr',
			)
	);

	$wp_customize->add_control( new WP_Customize_Control( $wp_customize,
						'fcorpo_animations_display',
							array(
								'label'          => __( 'Enable Animations', 'fcorpo' ),
								'section'        => 'fcorpo_animations_display',
								'settings'       => 'fcorpo_animations_display',
								'type'           => 'checkbox',
							)
						)
	);
}
add_action('customize_register', 'fcorpo_customize_register');

function fcorpo_header_style() {

    $header_text_color = get_header_textcolor();

    if ( ! has_header_image()
        && ( get_theme_support( 'custom-header', 'default-text-color' ) === $header_text_color
             || 'blank' === $header_text_color ) ) {

        return;
    }

    $headerImage = get_header_image();
?>
    <style type="text/css">
        <?php if ( has_header_image() ) : ?>

                #header-main-fixed {background-image: url("<?php echo esc_url( $headerImage ); ?>");}

        <?php endif; ?>

        <?php if ( get_theme_support( 'custom-header', 'default-text-color' ) !== $header_text_color
                    && 'blank' !== $header_text_color ) : ?>

                #header-main-fixed, #header-main-fixed h1.entry-title {color: #<?php echo esc_attr( $header_text_color ); ?>;}

        <?php endif; ?>
    </style>
<?php

}

?>
