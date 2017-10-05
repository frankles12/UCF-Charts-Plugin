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

		public static function enqueue_frontend_assets() {

		}
	}
}
