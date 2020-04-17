<?php
/**
 * Custom Sidebars
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start Class
if ( ! class_exists( 'TJ_Extras_Sidebars' ) ) {

	/**
	 * Custom sidebar functions
	 *
	 * @since  1.0.0
	 * @access public
	 */
	final class TJ_Extras_Sidebars {

		private $widget_areas = array();
		private $orig         = array();

		/**
		 * Start things up
		 *
		 * @since 1.0.0
		 */
		private function setup_actions( $widget_areas = array() ) {
			add_action( 'init', array( $this, 'register_custom_widget_areas' ) , 1000 );
			add_action( 'admin_print_scripts-widgets.php', array( $this, 'add_widget_box' ) );
			add_action( 'load-widgets.php', array( $this, 'add_widget_area' ), 100 );
			add_action( 'load-widgets.php', array( $this, 'scripts' ), 100 );
			add_action( 'admin_print_styles-widgets.php', array( $this, 'inline_css' ) );
			add_action( 'wp_ajax_tj_extras_delete_widget_area', array( $this, 'tj_extras_delete_widget_area' ) );
			add_action( 'wp_ajax_nopriv_tj_extras_delete_widget_area', array( $this, 'tj_extras_delete_widget_area' ) );
		}

		/**
		 * Add the widget box inside a script
		 *
		 * @since 1.0.0
		 */
		public function add_widget_box() {
			$nonce = wp_create_nonce ( 'delete-tj-extras-widget_area-nonce' ); ?>
			<script type="text/html" id="tj-extras-add-widget-template">
				<div id="tj-extras-add-widget" class="widgets-holder-wrap">
					<div class="">
						<input type="hidden" name="tj-extras-nonce" value="<?php echo $nonce ?>" />
						<div class="sidebar-name">
							<h3><?php echo __( 'Create Widget Area', 'tj-extras' ); ?> <span class="spinner"></span></h3>
						</div>
						<div class="sidebar-description">
							<form id="addWidgetAreaForm" action="" method="post">
								<div class="widget-content">
									<input id="tj-extras-add-widget-input" name="tj-extras-add-widget-input" type="text" class="regular-text" title="<?php echo __( 'Name', 'tj-extras' ); ?>" placeholder="<?php echo __( 'Name', 'tj-extras' ); ?>" />
								</div>
								<div class="widget-control-actions">
									<div class="aligncenter">
										<input class="addWidgetArea-button button-primary" type="submit" value="<?php echo __( 'Create Widget Area', 'tj-extras' ); ?>" />
									</div>
									<br class="clear">
								</div>
							</form>
						</div>
					</div>
				</div>
			</script>
			<?php
		}

		/**
		 * Create new Widget Area
		 *
		 * @since 1.0.0
		 */
		public function add_widget_area() {
			if ( ! empty( $_POST['tj-extras-add-widget-input'] ) ) {
				$this->widget_areas = $this->get_widget_areas();
				array_push( $this->widget_areas, $this->check_widget_area_name( $_POST['tj-extras-add-widget-input'] ) );
				$this->save_widget_areas();
				wp_redirect( admin_url( 'widgets.php' ) );
				die();
			}
		}

		/**
		 * Before we create a new widget_area, verify it doesn't already exist. If it does, append a number to the name.
		 *
		 * @since 1.0.0
		 */
		public function check_widget_area_name( $name ) {
			if ( empty( $GLOBALS['wp_registered_widget_areas'] ) ) {
				return $name;
			}

			$taken = array();
			foreach ( $GLOBALS['wp_registered_widget_areas'] as $widget_area ) {
				$taken[] = $widget_area['name'];
			}

			$taken = array_merge( $taken, $this->widget_areas );

			if ( in_array( $name, $taken ) ) {
				$counter  = substr( $name, -1 );
				$new_name = '';

				if ( ! is_numeric( $counter ) ) {
					$new_name = $name . ' 1';
				} else {
					$new_name = substr( $name, 0, -1 ) . ((int) $counter + 1);
				}

				$name = $this->check_widget_area_name( $new_name );
			}
			echo $name;
			exit();
			return $name;
		}

		public function save_widget_areas() {
			set_theme_mod( 'tj-extras-sidebars', array_unique( $this->widget_areas ) );
		}

		/**
		 * Register and display the custom widget_area areas we have set.
		 *
		 * @since 1.0.0
		 */
		public function register_custom_widget_areas() {

			// Get widget areas
			if ( empty( $this->widget_areas ) ) {
				$this->widget_areas = $this->get_widget_areas();
			}

			// Original widget areas is empty
			$this->orig = array();

			// Save widget areas
			if ( ! empty( $this->orig ) && $this->orig != $this->widget_areas ) {
				$this->widget_areas = array_unique( array_merge( $this->widget_areas, $this->orig ) );
				$this->save_widget_areas();
			}

			// If widget areas are defined add a sidebar area for each
			if ( is_array( $this->widget_areas ) ) {
				foreach ( array_unique( $this->widget_areas ) as $widget_area ) {
					$args = array(
						'id'			=> sanitize_key( $widget_area ),
						'name'			=> $widget_area,
						'class'			=> 'tj-extras-custom-sidebar',
						'before_widget' => '<aside id="%1$s" class="widget %2$s">',
						'after_widget'  => '</aside>',
						'before_title'  => '<h3 class="widget-title">',
						'after_title'   => '</h3>',
					);
					register_sidebar( $args );
				}
			}
		}

		/**
		 * Return the widget_areas array.
		 *
		 * @since 1.0.0
		 */
		public function get_widget_areas() {

			// If the single instance hasn't been set, set it now.
			if ( ! empty( $this->widget_areas ) ) {
				return $this->widget_areas;
			}

			$db = get_theme_mod( 'tj-extras-sidebars' );

			if ( !empty( $db ) ) {
				$this->widget_areas = array_unique( array_merge( $this->widget_areas, $db ) );
			}

			// Return widget areas
			return $this->widget_areas;
		}

		/**
		 * Before we create a new widget_area, verify it doesn't already exist. If it does, append a number to the name.
		 *
		 * @since 1.0.0
		 */
		public function tj_extras_delete_widget_area() {
			check_ajax_referer( 'delete-tj-extras-widget_area-nonce', 'tjextrasNonce' );
			if ( ! empty( $_REQUEST['name'] ) ) {
				$name = strip_tags( ( stripslashes( $_REQUEST['name'] ) ) );
				$this->widget_areas = $this->get_widget_areas();
				$key = array_search($name, $this->widget_areas );
				if ( $key >= 0 ) {
					unset( $this->widget_areas[$key] );
					$this->save_widget_areas();
				}
				echo 'widget_area-deleted';
			}
			die();
		}

		/**
		 * Enqueue JS for the customizer controls
		 *
		 * @since 1.0.0
		 */
		public function scripts() {

			// Load scripts
			wp_enqueue_script( 'tj-extras-sidebars', TE_URL . 'assets/js/sidebars.js', array( 'jquery' ), true );

			// Get widgets
			$widgets = array();
			if ( ! empty( $this->widget_areas ) ) {
				foreach ( $this->widget_areas as $widget ) {
					$widgets[$widget] = 1;
				}
			}

			// Localize script
			wp_localize_script(
				'tj-extras-sidebars',
				'tjExtrasWidgetAreasLocalize',
				array(
					'count'   => count( $this->orig ),
					'delete'  => __( 'Delete', 'tj-extras' ),
					'confirm' => __( 'Confirm', 'tj-extras' ),
					'cancel'  => __( 'Cancel', 'tj-extras' )
				)
			);
		}

		/**
		 * Adds inline CSS to style the widget form
		 *
		 * @since 1.0.0
		 */
		public function inline_css() { ?>

			<style type="text/css">
				body #tj-extras-add-widget h3 { text-align: center !important; padding: 15px 7px; font-size: 1.3em; margin-top: 5px; }
				body div#widgets-right .sidebar-tj-extras-custom .widgets-sortables { padding-bottom: 45px }
				body div#widgets-right .sidebar-tj-extras-custom.closed .widgets-sortables { padding-bottom: 0 }
				body .tj-extras-widget-area-footer { display: block; position: absolute; bottom: 0; left: 0; height: 40px; line-height: 40px; width: 100%; border-top: 1px solid #eee; }
				body .tj-extras-widget-area-footer > div { padding: 8px 8px 0 }
				body .tj-extras-widget-area-footer .tj-extras-widget-area-id { display: block; float: left; max-width: 48%; overflow: hidden; position: relative; top: -6px; }
				body .tj-extras-widget-area-footer .tj-extras-widget-area-buttons { float: right }
				body .tj-extras-widget-area-footer .description { padding: 0 !important; margin: 0 !important; }
				body div#widgets-right .sidebar-tj-extras-custom.closed .widgets-sortables .tj-extras-widget-area-footer { display: none }
				body .tj-extras-widget-area-footer .tj-extras-widget-area-delete { display: block; float: right; margin: 0; }
				body .tj-extras-widget-area-footer .tj-extras-widget-area-delete-confirm { display: none; float: right; margin: 0 5px 0 0; }
				body .tj-extras-widget-area-footer .tj-extras-widget-area-delete-cancel { display: none; float: right; margin: 0; }
				body .tj-extras-widget-area-delete-confirm:hover:before { color: red }
				body .tj-extras-widget-area-delete-confirm:hover { color: #000 }
				body .tj-extras-widget-area-delete:hover:before { color: #888 }
				body .activate_spinner { display: block !important; position: absolute; top: 10px; right: 4px; background-color: #ECECEC; }
				body #tj-extras-add-widget form { text-align: center }
				body #widget_area-tj-extras-custom,
				body #widget_area-tj-extras-custom h3 { position: relative }
				body #tj-extras-add-widget p { margin-top: 0 }
				body #tj-extras-add-widget { margin: 10px 0 0; position: relative; }
				body #tj-extras-add-widget-input { max-width: 95%; padding: 8px; margin-bottom: 14px; margin-top: 3px; text-align: center; }
			</style>

		<?php }

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return object
		 */
		public static function get_instance() {
			static $instance = null;
			if ( is_null( $instance ) ) {
				$instance = new self;
				$instance->setup_actions();
			}
			return $instance;
		}

		/**
		 * Constructor method.
		 *
		 * @since  1.0.0
		 * @access private
		 * @return void
		 */
		private function __construct() {}

	}

	TJ_Extras_Sidebars::get_instance();

}
