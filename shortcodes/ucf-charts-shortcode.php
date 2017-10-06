<?php
/**
 * Handles registering the ucf-chart shortcode
 **/
if ( ! class_exists( 'UCF_Chart_Shortcode' ) ) {
	class UCF_Chart_Shortcode {
		/**
		 * Registers the ucf-chart shortcode
		 * @author Jim Barnes
		 * @since 1.0.0
		 **/
		public static function register() {
			add_shortcode( 'ucf-chart', array( 'UCF_Chart_Shortcode', 'handler' ) );
		}

		/**
		 * The handler for the ucf-chart shortcode
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @param array $atts The array of shortcode attributes
		 * @return string The shortcode markup
		 **/
		public static function handler( $atts ) {
			$atts = shortcode_atts(
				array(
					'id'    => null,
					'class' => ''
				),
				$atts
			);

			$id    = $atts['id'];
			$class = $atts['class'];

			if ( $id ) {
				$chart = get_post( $id );

				if ( ! $chart ) return;

				if ( 'ucf_chart' !== $chart->post_type ) return '';

				$name         = $chart->post_name;
				$class        = ! empty( $class ) ? 'custom-chart ' . $class : 'custom-chart';
				$chart_type   = get_post_meta( $chart->ID, 'ucf_chart_type', TRUE );
				$data_json    = get_post_meta( $chart->ID, 'ucf_chart_data_json', TRUE );
				$data_file    = wp_get_attachment_url( $data_json );
				$options_json = get_post_meta( $chart->ID, 'ucf_chart_options_json', TRUE );
				$options_file = wp_get_attachment_url( $options_json );

				if ( ! $data_file ) return '';

				$args = array(
					'id'              => $name,
					'class'           => $class,
					'data-chart-type' => $chart_type,
					'data-chart-data' => $data_file
				);

				// Add options file if provided (not required)
				if ( $options_file ) {
					$args['data-chart-options'] = $options_file;
				}

				$flattened = $args;

				array_walk( $flattened, function( &$value, $key ) {
					$value = "{$key}=\"{$value}\"";
				});

				$args_string = implode( $flattened );

				ob_start();
			?>
				<div <?php echo $args_string; ?>></div>
			<?php
				return ob_get_clean();
			} else {
				return '';
			}
		}
	}
}
