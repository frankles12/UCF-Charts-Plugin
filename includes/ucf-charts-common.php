<?php
/**
 * Handles common tasks for UCF Charts
 **/
if ( ! class_exists( 'UCF_Chart_Common' ) ) {
	class UCF_Chart_Common {
		/**
		 * Returns an array of chart types
		 * Chart.js types are listed here ({@link http://www.chartjs.org/docs/latest/charts/})
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @return array An array of chart types.
		 **/
		public static function get_chart_types() {
			$defaults = array(
				'bar'        => 'Bar Chart',
				'line'       => 'Line Chart',
				'radar'      => 'Radar Chart',
				'polar-area' => 'Polar Area Chart',
				'pie'        => 'Pie Chart',
				'doughnut'   => 'Doughnut Chart'
			);

			$chart_types = apply_filters( 'ucf_chart_types', $defaults );

			return $chart_types;
		}

		/**
		 * Handles enqueuing frontend assets
		 * @author Jim Barnes
		 * @since 1.0.0
		 **/
		public static function enqueue_frontend_assets() {
			if ( UCF_Chart_Config::get_option_or_default( 'include_js' ) ) {
				wp_enqueue_script(
					'chart-js',
					UCF_CHARTS__VENDOR_JS_URL,
					array( 'jquery' ),
					null,
					True
				);
			}

			wp_enqueue_script(
				'ucf-chart',
				UCF_CHARTS__JS_URL . '/ucf-chart.min.js',
				array( 'jquery', 'chart-js' ),
				null,
				True
			);
		}

		/**
		* Allow extra file types to be uploaded to the media library.
		* @author Jim Barnes
		* @since 1.0.0
		* @param array $mimes The array of mime types allowed to be uploaded to wp media
		* @return array The modified array of mime types
		**/
		public static function custom_mimes( $mimes ) {
			if ( ! key_exists( $mimes['json'] ) ) {
				$mimes['json'] = 'application/json';
			}

			return $mimes;
		}
	}
}
