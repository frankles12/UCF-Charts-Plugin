<?php
/**
 * Register UCF Charts options
 **/
if ( ! class_exists( 'UCF_Chart_Config' ) ) {
	class UCF_Chart_Config {
		public static
			$option_prefix    = 'ucf_charts_',
			$options_defaults = array(
				'include_js'  => True,
				'include_css' => True
			);

		/**
		 * Creates options via the WP Options API that are utilized by the
		 * plugin. Intended to be run on plugin activation.
		 * @author Jim Barnes
		 * @since 1.0.0
		 **/
		public static function add_options() {
			$defaults = self::$options_defaults;

			add_option( self::$option_prefix . 'include_js',  $defaults['include_js'] );
			add_option( self::$option_prefix . 'include_css', $defaults['include_css'] );
		}

		/**
		 * Deletes options via the WP Options API that are utilized by the
		 * plugin.  Intended to be run on plugin uninstallation.
		 * @author Jim Barnes
		 * @since 1.0.0
		 **/
		public static function delete_options() {
			delete_option( self::$option_prefix . 'include_js' );
			delete_option( self::$option_prefix . 'include_css' );
		}

		/**
		 * Returns a list of default plugin options. Applies any overridden
		 * default values set within the option page.
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @return array
		 **/
		public static function get_option_defaults() {
			$defaults = self::$options_defaults;

			$configurable_defaults = array(
				'include_js'  => get_option( self::$option_prefix . 'include_css' ),
				'include_css' => get_option( self::$option_prefix . 'include_js' )
			);

			$configurable_defaults = self::format_options( $configurable_defaults );

			$defaults = array_merge( $defaults, $configurable_defaults );

			return $defaults;
		}

		/**
		 * Returns an array with plugin defaults applied.
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @param array $list The list to which defaults will be applied
		 * @param boolean $list_keys_only Modifies results to only return array key values present in $list
		 * @return array
		 **/
		public static function apply_option_defaults( $list, $list_keys_only=False ) {
			$defaults = self::get_option_defaults();
			$options = array();

			if ( $list_keys_only ) {
				foreach( $list as $key => $val ) {
					$options[$key] = ! empty( $val ) ? $val : $defaults[$key];
				}
			} else {
				$options = array_merge( $defaults, $list );
			}

			$options = self::format_options( $options );

			return $options;
		}

		/**
		 * Performs typecasting and sanitization on an array of plugin options
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @param array $list The array of options to format
		 * @return array The formatted options array
		 **/
		public static function format_options( $list ) {
			foreach( $list as $key => $val ) {
				switch( $key ) {
					case 'include_css':
					case 'include_js' :
						$list[$key] = filter_val( $val, FILTER_VALIDATE_BOOLEAN );
						break;
					default:
						break;
				}
			}
		}

		/**
		 * Convenience method for returning an option from the WP Options API
		 * or a plugin option default.
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @param string $option_name The option name
		 * @return mixed The value of the option
		 **/
		public static function get_option_or_default( $option_name ) {
			$option_name_no_prefix = str_replace( self::$option_prefix, '', $option_name );
			$option_name = self::$option_prefix . $option_name_no_prefix;

			$option = get_option( $option_name );
			$option_formatted = self::apply_option_defaults( array(
				$option_name_no_prefix => $option
			), true );

			return $option_formatted[$option_name_no_prefix];
		}

		/**
		 * Initializes setting registration with the Settings API
		 * @author Jim Barnes
		 * @since 1.0.0
		 **/
		public static function settings_init() {
			// Register settings
			add_settings_section(
				'ucf_charts_section_assets',
				'Included Assets',
				null,
				'ucf_chart'
			);

			register_setting(
				'ucf_chart',
				self::$option_prefix . 'include_css'
			);

			add_settings_field(
				self::$option_prefix . 'include_css',
				'Include default CSS',
				array( 'UCF_Chart_Config', 'display_settings_field' ),
				'ucf_chart',
				'ucf_chart_section_assets',
				array(
					'label_for'   => self::$option_prefix . 'include_css',
					'description' => 'When checked, the included default CSS will be enqueued on all pages.',
					'type'        => 'checkbox'
				)
			);

			register_setting(
				'ucf_chart',
				self::$option_prefix . 'include_js'
			);

			add_settings_field(
				self::$option_prefix . 'include_js',
				'Include default JS',
				array( 'UCF_Chart_Config', 'display_settings_field' ),
				'ucf_chart',
				'ucf_chart_section_assets',
				array(
					'label_for'   => self::$option_prefix . 'include_js',
					'description' => 'When checked the included default JS will be enqueued on all pages. (Note: You must initiate all charts manually in your theme javascript file if this is unchecked).',
					'type'        => 'checkbox'
				)
			);
		}

		/**
		 * Display an individual setting's field markup
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @param array $args An array of field arguments
		 * @return string The formatted html of the field
		 **/
		public static function display_settings_field( $args ) {
			$option_name   = $args['label_for'];
			$description   = $args['description'];
			$field_type    = $args['type'];
			$options       = isset( $args['options'] ) ? $args['options'] : null;
			$current_value = self::get_option_or_default( $option_name );
			$markup        = '';

			switch( $field_type ) {
				case 'checkbox':
					ob_start();
				?>
					<input type="checkbox" id="<?php echo $option_name; ?>" name="<?php echo $option_name; ?>" <?php echo ( $current_value == true ) ? 'checked' : ''; ?>>
					<p class="description">
						<?php echo $description; ?>
					</p>
				<?php
					break;
				case 'number':
					ob_start();
				?>
					<input type="number" id="<?php echo $option_name; ?>" name="<?php echo $option_name; ?>" value="<?php echo $current_value; ?>">
					<p class="description">
						<?php echo $description; ?>
					</p>
				<?php
					$markup = ob_get_clean();
					break;
				case 'select':
					ob_start();
				?>
					<select id="<?php echo $option_name; ?>" name="<?php echo $option_name; ?>">
					<?php foreach( $options as $key => $val ) : ?>
						<option value="<?php echo $key; ?>"<?php echo ( $current_value === $key ) ? ' selected' : ''; ?>><?php echo $val; ?></option>
					<?php endforeach; ?>
					</select>
				<?php
					$markup = ob_get_clean();
					break;
				case 'text':
				default:
					ob_start();
				?>
					<input type="text" id="<?php echo $option_name; ?>" name="<?php echo $option_name; ?>" value="<?php echo $current_value; ?>">
					<p class="description">
						<?php echo $description; ?>
					</p>
				<?php
					$markup = ob_get_clean();
					break;
			}

			echo $markup;
		}

		/**
		 * Registers the settings page to display in the WordPress admin
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @return string The resulting page's hook_suffix
		 **/
		public static function add_options_page() {
			$page_title = 'UCF Charts Settings';
			$menu_title = 'UCF Charts';
			$capability = 'manage_options';
			$menu_slug  = 'ucf_chart',
			$callback   = array( 'UCF_Chart_Config', 'options_page_html' );

			return add_options_page(
				$page_title,
				$menu_title,
				$capability,
				$menu_slug,
				$callback
			);
		}

		/**
		 * Displays the plugin's settings page form
		 * @author Jim Barnes
		 * @since 1.0.0
		 **/
		public static function options_page_html() {
			ob_start();
		?>
			<div class="wrap">
				<h1><?php echo get_admin_page_title(); ?></h1>
				<form method="post" action="options.php">
				<?php
					settings_fields( 'ucf_chart' );
					do_settings_sections( 'ucf_chart' );
					submit_button();
				?>
				</form>
			</div>
		<?php
			echo ob_get_clean();
		}
	}
}
