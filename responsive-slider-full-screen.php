<?php
/*
Plugin Name: Responsive Slider Full Screen
Plugin URL: http://beautiful-module.com/demo/responsive-slider-full-screen/
Description: A simple Responsive Slider Full Screen
Version: 1.0
Author: Module Express
Author URI: http://beautiful-module.com
Contributors: Module Express
*/
/*
 * Register CPT sp_slider.fullscreen
 *
 */
if(!class_exists('Responsive_Slider_Full_Screen')) {
	class Responsive_Slider_Full_Screen {

		function __construct() {
		    if(!function_exists('add_shortcode')) {
		            return;
		    }
			add_action ( 'init' , array( $this , 'rsfs_responsive_gallery_setup_post_types' ));

			/* Include style and script */
			add_action ( 'wp_enqueue_scripts' , array( $this , 'rsfs_register_style_script' ));
			
			/* Register Taxonomy */
			add_action ( 'init' , array( $this , 'rsfs_responsive_gallery_taxonomies' ));
			add_action ( 'add_meta_boxes' , array( $this , 'rsfs_rsris_add_meta_box_gallery' ));
			add_action ( 'save_post' , array( $this , 'rsfs_rsris_save_meta_box_data_gallery' ));
			register_activation_hook( __FILE__, 'rsfs_responsive_gallery_rewrite_flush' );


			// Manage Category Shortcode Columns
			add_filter ( 'manage_responsive_rsfs_slider-category_custom_column' , array( $this , 'rsfs_responsive_gallery_category_columns' ), 10, 3);
			add_filter ( 'manage_edit-responsive_rsfs_slider-category_columns' , array( $this , 'rsfs_responsive_gallery_category_manage_columns' ));
			require_once( 'rsfs_gallery_admin_settings_center.php' );
			require_once( 'multiple-post-thumbnails.php' );

			if (class_exists('MultiPostThumbnails'))
			{
				new MultiPostThumbnails(array(
					'label' => '2nd Feature Image',
					'id' => 'secondary-image',
					'post_type' => 'sp_slider_fullscreen'
				));
				
				new MultiPostThumbnails(array(
					'label' => '3rd Feature Image',
					'id' => 'third-image',
					'post_type' => 'sp_slider_fullscreen'
				));
			}
			
			
		    add_shortcode ( 'sp_slider.fullscreen' , array( $this , 'rsfs_responsivegallery_shortcode' ));
		}


		function rsfs_responsive_gallery_setup_post_types() {

			$responsive_gallery_labels =  apply_filters( 'responsive_slider_full_screen_labels', array(
				'name'                => 'Responsive Slider Full Screen',
				'singular_name'       => 'Responsive Slider Full Screen',
				'add_new'             => __('Add New', 'sp_slider_fullscreen'),
				'add_new_item'        => __('Add New Image', 'sp_slider_fullscreen'),
				'edit_item'           => __('Edit Image', 'sp_slider_fullscreen'),
				'new_item'            => __('New Image', 'sp_slider_fullscreen'),
				'all_items'           => __('All Images', 'sp_slider_fullscreen'),
				'view_item'           => __('View Image', 'sp_slider_fullscreen'),
				'search_items'        => __('Search Image', 'sp_slider_fullscreen'),
				'not_found'           => __('No Image found', 'sp_slider_fullscreen'),
				'not_found_in_trash'  => __('No Image found in Trash', 'sp_slider_fullscreen'),
				'parent_item_colon'   => '',
				'menu_name'           => __('Responsive Slider Full Screen', 'sp_slider_fullscreen'),
				'exclude_from_search' => true
			) );


			$responsiveslider_args = array(
				'labels' 			=> $responsive_gallery_labels,
				'public' 			=> true,
				'publicly_queryable'		=> true,
				'show_ui' 			=> true,
				'show_in_menu' 		=> true,
				'query_var' 		=> true,
				'capability_type' 	=> 'post',
				'has_archive' 		=> true,
				'hierarchical' 		=> false,
				'menu_icon'   => 'dashicons-format-gallery',
				'supports' => array('title','editor','thumbnail')
				
			);
			register_post_type( 'sp_slider_fullscreen', apply_filters( 'sp_faq_post_type_args', $responsiveslider_args ) );

		}
		
		function rsfs_register_style_script() {
		    wp_enqueue_style( 'rsfs_responsiveimgslider',  plugin_dir_url( __FILE__ ). 'css/responsiveimgslider.css' );
			/*   REGISTER ALL CSS FOR SITE */
			wp_enqueue_style( 'rsfs_featurelist',  plugin_dir_url( __FILE__ ). 'css/sliderfullwidth.css' );

			/*   REGISTER ALL JS FOR SITE */			
			wp_enqueue_script( 'rsfs_jssor.core', plugin_dir_url( __FILE__ ) . 'js/jssor.core.js', array( 'jquery' ));
			wp_enqueue_script( 'rsfs_jssor.utils', plugin_dir_url( __FILE__ ) . 'js/jssor.utils.js', array( 'jquery' ));
			wp_enqueue_script( 'rsfs_jssor.slider', plugin_dir_url( __FILE__ ) . 'js/jssor.slider.js', array( 'jquery' ));
		}
		
		
		function rsfs_responsive_gallery_taxonomies() {
		    $labels = array(
		        'name'              => _x( 'Category', 'taxonomy general name' ),
		        'singular_name'     => _x( 'Category', 'taxonomy singular name' ),
		        'search_items'      => __( 'Search Category' ),
		        'all_items'         => __( 'All Category' ),
		        'parent_item'       => __( 'Parent Category' ),
		        'parent_item_colon' => __( 'Parent Category:' ),
		        'edit_item'         => __( 'Edit Category' ),
		        'update_item'       => __( 'Update Category' ),
		        'add_new_item'      => __( 'Add New Category' ),
		        'new_item_name'     => __( 'New Category Name' ),
		        'menu_name'         => __( 'Gallery Category' ),
		    );

		    $args = array(
		        'hierarchical'      => true,
		        'labels'            => $labels,
		        'show_ui'           => true,
		        'show_admin_column' => true,
		        'query_var'         => true,
		        'rewrite'           => array( 'slug' => 'responsive_rsfs_slider-category' ),
		    );

		    register_taxonomy( 'responsive_rsfs_slider-category', array( 'sp_slider_fullscreen' ), $args );
		}

		function rsfs_responsive_gallery_rewrite_flush() {  
				rsfs_responsive_gallery_setup_post_types();
		    flush_rewrite_rules();
		}


		function rsfs_responsive_gallery_category_manage_columns($theme_columns) {
		    $new_columns = array(
		            'cb' => '<input type="checkbox" />',
		            'name' => __('Name'),
		            'gallery_vertical_shortcode' => __( 'Gallery Category Shortcode', 'vertical_slick_slider' ),
		            'slug' => __('Slug'),
		            'posts' => __('Posts')
					);

		    return $new_columns;
		}

		function rsfs_responsive_gallery_category_columns($out, $column_name, $theme_id) {
		    $theme = get_term($theme_id, 'responsive_rsfs_slider-category');

		    switch ($column_name) {      
		        case 'title':
		            echo get_the_title();
		        break;
		        case 'gallery_vertical_shortcode':
					echo '[sp_slider.fullscreen cat_id="' . $theme_id. '"]';			  	  

		        break;
		        default:
		            break;
		    }
		    return $out;   

		}

		/* Custom meta box for slider link */
		function rsfs_rsris_add_meta_box_gallery() {
			add_meta_box('custom-metabox',__( 'LINK URL', 'link_textdomain' ),array( $this , 'rsfs_rsris_gallery_box_callback' ),'sp_slider_fullscreen');
		}
		
		function rsfs_rsris_gallery_box_callback( $post ) {
			wp_nonce_field( 'rsfs_rsris_save_meta_box_data_gallery', 'rsris_meta_box_nonce' );
			$value = get_post_meta( $post->ID, 'rsris_rsfs_link', true );
			echo '<input type="url" id="rsris_rsfs_link" name="rsris_rsfs_link" value="' . esc_attr( $value ) . '" size="25" /><br />';
			echo 'ie http://www.google.com';
		}
		
		function rsfs_rsris_save_meta_box_data_gallery( $post_id ) {
			if ( ! isset( $_POST['rsris_meta_box_nonce'] ) ) {
				return;
			}
			if ( ! wp_verify_nonce( $_POST['rsris_meta_box_nonce'], 'rsfs_rsris_save_meta_box_data_gallery' ) ) {
				return;
			}
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}
			if ( isset( $_POST['post_type'] ) && 'sp_slider_fullscreen' == $_POST['post_type'] ) {

				if ( ! current_user_can( 'edit_page', $post_id ) ) {
					return;
				}
			} else {

				if ( ! current_user_can( 'edit_post', $post_id ) ) {
					return;
				}
			}
			if ( ! isset( $_POST['rsris_rsfs_link'] ) ) {
				return;
			}
			$link_data = sanitize_text_field( $_POST['rsris_rsfs_link'] );
			update_post_meta( $post_id, 'rsris_rsfs_link', $link_data );
		}
		
		/*
		 * Add [sp_slider.fullscreen] shortcode
		 *
		 */
		function rsfs_responsivegallery_shortcode( $atts, $content = null ) {
			
			extract(shortcode_atts(array(
				"limit"  => '',
				"cat_id" => '',
				"autoplay" => '',
				"autoplay_interval" => ''
			), $atts));
			
			if( $limit ) { 
				$posts_per_page = $limit; 
			} else {
				$posts_per_page = '-1';
			}
			if( $cat_id ) { 
				$cat = $cat_id; 
			} else {
				$cat = '';
			}
			
			if( $autoplay ) { 
				$autoplay_slider = $autoplay; 
			} else {
				$autoplay_slider = 'true';
			}	 	
			
			if( $autoplay_interval ) { 
				$autoplay_intervalslider = $autoplay_interval; 
			} else {
				$autoplay_intervalslider = '4000';
			}
						

			ob_start();
			// Create the Query
			$post_type 		= 'sp_slider_fullscreen';
			$orderby 		= 'post_date';
			$order 			= 'DESC';
						
			 $args = array ( 
		            'post_type'      => $post_type, 
		            'orderby'        => $orderby, 
		            'order'          => $order,
		            'posts_per_page' => $posts_per_page,  
		           
		            );
			if($cat != ""){
		            	$args['tax_query'] = array( array( 'taxonomy' => 'responsive_rsfs_slider-category', 'field' => 'id', 'terms' => $cat) );
		            }        
		      $query = new WP_Query($args);

			$post_count = $query->post_count;
			$i = 1;

			if( $post_count > 0) :
			?>
				<div id="rsfs_slider1_container" style="position: relative; margin: 0 auto;
					top: 0px; left: 0px; width: 1300px; height: 500px; overflow: hidden;">
					<div u="loading" style="position: absolute; top: 0px; left: 0px;">
						<div style="filter: alpha(opacity=70); opacity: 0.7; position: absolute; display: block;
							top: 0px; left: 0px; width: 100%; height: 100%;">
						</div>
						<div class="rsfs_loading_screen">
						</div>
					</div>
					<div u="slides" style="cursor: move; position: absolute; left: 0px; top: 0px; width: 1300px;
						height: 500px; overflow: hidden;">
						<?php								
								while ($query->have_posts()) : $query->the_post();
									include('designs/template.php');
									
								$i++;
								endwhile;									
						?>
					</div>
							
					<div u="navigator" class="jssorn21" style="position: absolute; bottom: 26px; left: 6px;">
						<div u="prototype" style="POSITION: absolute; WIDTH: 19px; HEIGHT: 19px; text-align:center; line-height:19px; color:White; font-size:12px;"></div>
					</div>

					<span u="arrowleft" class="jssord21l" style="width: 55px; height: 55px; top: 123px; left: 8px;">
					</span>
					<span u="arrowright" class="jssord21r" style="width: 55px; height: 55px; top: 123px; right: 8px">
					</span>
				</div>
			
				<?php
				endif;
				// Reset query to prevent conflicts
				wp_reset_query();
			?>							
			<script type="text/javascript">
				jQuery(document).ready(function ($) {

				var _CaptionTransitions = [];
				_CaptionTransitions["L"] = { $Duration: 900, $FlyDirection: 1, $Easing: { $Left: $JssorEasing$.$EaseInOutSine }, $ScaleHorizontal: 0.6, $Opacity: 2 };
				_CaptionTransitions["R"] = { $Duration: 900, $FlyDirection: 2, $Easing: { $Left: $JssorEasing$.$EaseInOutSine }, $ScaleHorizontal: 0.6, $Opacity: 2 };
				_CaptionTransitions["T"] = { $Duration: 900, $FlyDirection: 4, $Easing: { $Top: $JssorEasing$.$EaseInOutSine }, $ScaleVertical: 0.6, $Opacity: 2 };
				_CaptionTransitions["B"] = { $Duration: 900, $FlyDirection: 8, $Easing: { $Top: $JssorEasing$.$EaseInOutSine }, $ScaleVertical: 0.6, $Opacity: 2 };
				_CaptionTransitions["ZMF|10"] = { $Duration: 900, $Zoom: 11, $Easing: { $Zoom: $JssorEasing$.$EaseOutQuad, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2 };
				_CaptionTransitions["RTT|10"] = { $Duration: 900, $Zoom: 11, $Rotate: 1, $Easing: { $Zoom: $JssorEasing$.$EaseOutQuad, $Opacity: $JssorEasing$.$EaseLinear, $Rotate: $JssorEasing$.$EaseInExpo }, $Opacity: 2, $Round: { $Rotate: 0.8} };
				_CaptionTransitions["RTT|2"] = { $Duration: 900, $Zoom: 3, $Rotate: 1, $Easing: { $Zoom: $JssorEasing$.$EaseInQuad, $Opacity: $JssorEasing$.$EaseLinear, $Rotate: $JssorEasing$.$EaseInQuad }, $Opacity: 2, $Round: { $Rotate: 0.5} };
				_CaptionTransitions["RTTL|BR"] = { $Duration: 900, $Zoom: 11, $Rotate: 1, $FlyDirection: 10, $Easing: { $Left: $JssorEasing$.$EaseInCubic, $Top: $JssorEasing$.$EaseInCubic, $Zoom: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear, $Rotate: $JssorEasing$.$EaseInCubic }, $ScaleHorizontal: 0.6, $ScaleVertical: 0.6, $Opacity: 2, $Round: { $Rotate: 0.8} };
				_CaptionTransitions["CLIP|LR"] = { $Duration: 900, $Clip: 15, $Easing: { $Clip: $JssorEasing$.$EaseInOutCubic }, $Opacity: 2 };
				_CaptionTransitions["MCLIP|L"] = { $Duration: 900, $Clip: 1, $Move: true, $Easing: { $Clip: $JssorEasing$.$EaseInOutCubic} };
				_CaptionTransitions["MCLIP|R"] = { $Duration: 900, $Clip: 2, $Move: true, $Easing: { $Clip: $JssorEasing$.$EaseInOutCubic} };

				var options = {
					$FillMode: 2,                                       //[Optional] The way to fill image in slide, 0 stretch, 1 contain (keep aspect ratio and put all inside slide), 2 cover (keep aspect ratio and cover whole slide), 4 actuall size, default value is 0
					$AutoPlay: <?php if($autoplay_slider == "false") { echo 'false';} else { echo 'true'; } ?>,                                    //[Optional] Whether to auto play, to enable slideshow, this option must be set to true, default value is false
					$AutoPlayInterval: <?php echo $autoplay_intervalslider; ?>,                            //[Optional] Interval (in milliseconds) to go for next slide since the previous stopped if the slider is auto playing, default value is 3000
					$PauseOnHover: 3,                                   //[Optional] Whether to pause when mouse over if a slider is auto playing, 0 no pause, 1 pause for desktop, 2 pause for touch device, 3 pause for desktop and touch device, default value is 3

					$ArrowKeyNavigation: true,   			            //[Optional] Allows keyboard (arrow key) navigation or not, default value is false
					$SlideEasing: $JssorEasing$.$EaseOutQuart,
					$SlideDuration: 1200,                                //[Optional] Specifies default duration (swipe) for slide in milliseconds, default value is 500
					$MinDragOffsetToSlide: 20,                          //[Optional] Minimum drag offset to trigger slide , default value is 20
					//$SlideWidth: 600,                                 //[Optional] Width of every slide in pixels, default value is width of 'slides' container
					//$SlideHeight: 300,                                //[Optional] Height of every slide in pixels, default value is height of 'slides' container
					$SlideSpacing: 0, 					                //[Optional] Space between each slide in pixels, default value is 0
					$DisplayPieces: 1,                                  //[Optional] Number of pieces to display (the slideshow would be disabled if the value is set to greater than 1), the default value is 1
					$ParkingPosition: 0,                                //[Optional] The offset position to park slide (this options applys only when slideshow disabled), default value is 0.
					$UISearchMode: 1,                                   //[Optional] The way (0 parellel, 1 recursive, default value is 1) to search UI components (slides container, loading screen, navigator container, direction navigator container, thumbnail navigator container etc).
					$PlayOrientation: 1,                                //[Optional] Orientation to play slide (for auto play, navigation), 1 horizental, 2 vertical, default value is 1
					$DragOrientation: 1,                                //[Optional] Orientation to drag slide, 0 no drag, 1 horizental, 2 vertical, 3 either, default value is 1 (Note that the $DragOrientation should be the same as $PlayOrientation when $DisplayPieces is greater than 1, or parking position is not 0)

					$CaptionSliderOptions: {                            //[Optional] Options which specifies how to animate caption
						$Class: $JssorCaptionSlider$,                   //[Required] Class to create instance to animate caption
						$CaptionTransitions: _CaptionTransitions,       //[Required] An array of caption transitions to play caption, see caption transition section at jssor slideshow transition builder
						$PlayInMode: 1,                                 //[Optional] 0 None (no play), 1 Chain (goes after main slide), 3 Chain Flatten (goes after main slide and flatten all caption animations), default value is 1
						$PlayOutMode: 3                                 //[Optional] 0 None (no play), 1 Chain (goes before main slide), 3 Chain Flatten (goes before main slide and flatten all caption animations), default value is 1
					},

					$NavigatorOptions: {                                //[Optional] Options to specify and enable navigator or not
						$Class: $JssorNavigator$,                       //[Required] Class to create navigator instance
						$ChanceToShow: 2,                               //[Required] 0 Never, 1 Mouse Over, 2 Always
						$AutoCenter: 1,                                 //[Optional] Auto center navigator in parent container, 0 None, 1 Horizontal, 2 Vertical, 3 Both, default value is 0
						$Steps: 1,                                      //[Optional] Steps to go for each navigation request, default value is 1
						$Lanes: 1,                                      //[Optional] Specify lanes to arrange items, default value is 1
						$SpacingX: 8,                                   //[Optional] Horizontal space between each item in pixel, default value is 0
						$SpacingY: 8,                                   //[Optional] Vertical space between each item in pixel, default value is 0
						$Orientation: 1                                 //[Optional] The orientation of the navigator, 1 horizontal, 2 vertical, default value is 1
					},

					$DirectionNavigatorOptions: {                       //[Optional] Options to specify and enable direction navigator or not
						$Class: $JssorDirectionNavigator$,              //[Requried] Class to create direction navigator instance
						$ChanceToShow: 1,                               //[Required] 0 Never, 1 Mouse Over, 2 Always
						$AutoCenter: 2,                                 //[Optional] Auto center arrows in parent container, 0 No, 1 Horizontal, 2 Vertical, 3 Both, default value is 0
						$Steps: 1                                       //[Optional] Steps to go for each navigation request, default value is 1
					}
				};

				var jssor_slider1 = new $JssorSlider$("rsfs_slider1_container", options);

				//responsive code begin
				//you can remove responsive code if you don't want the slider scales while window resizes
				function ScaleSlider() {
					var bodyWidth = document.body.clientWidth;
					if (bodyWidth)
						jssor_slider1.$SetScaleWidth(Math.min(bodyWidth, 1920));
					else
						window.setTimeout(ScaleSlider, 30);
				}

				ScaleSlider();

				if (!navigator.userAgent.match(/(iPhone|iPod|iPad|BlackBerry|IEMobile)/)) {
					$(window).bind('resize', ScaleSlider);
				}
				//responsive code end
			});
			</script>
			<?php
			return ob_get_clean();
		}		
	}
}
	
function rsfs_master_gallery_images_load() {
        global $mfpd;
        $mfpd = new Responsive_Slider_Full_Screen();
}
add_action( 'plugins_loaded', 'rsfs_master_gallery_images_load' );