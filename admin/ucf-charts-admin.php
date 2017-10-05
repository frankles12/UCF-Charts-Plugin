<?php
/**
 * Handles various admin updates for displaying UCF Charts
 **/
if ( ! class_exists( 'UCF_Chart_Admin' ) ) {
	class UCF_Chart_Admin {
		/**
		 * Outputs the shortcode for a ucf_chart
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @param string $column The column name
		 * @param int $post_id The id of the post
		 **/
		public static function ucf_chart_shortcode_column( $column, $post_id ) {
			switch( $column ) {
				case 'shortcode':
					$shortcode = '[ucf-chart id="' . $post_id . '"]';
					echo '<pre><code>' . $shortcode . '</code></pre>';
					break;
			}
		}

		public static function ucf_chart_custom_columns( $columns ) {
			return array_merge(
				$columns,
				array(
					'shortcode' => __( 'Shortcode' )
				)
			);
		}
	}
}
