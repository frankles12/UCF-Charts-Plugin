<?php
/**
 * Handles registration of the ucf-chart custom post type
 **/
if ( ! class_exists( 'UCF_Chart_PostType' ) ) {
	class UCF_Chart_PostType {
		/**
		 * Registers the ucf-chart custom post type
		 * @author Jim Barnes
		 * @since 1.0.0
		 **/
		public static function register() {
			$labels = apply_filters(
				'ucf_chart_labels',
				array(
					'singular'    => 'Chart',
					'plural'      => 'Charts',
					'text_domain' => 'ucf_chart'
				)
			);

			register_post_type( 'ucf_chart', self::args( $labels ) );
			//add_action( 'add_meta_boxes', array( 'UCF_Chart_PostType', 'register_metaboxes' ) );
			//add_action( 'save_post', array( 'UCF_Chart_PostType', 'save_metaboxes' ) );
		}

		/**
		 * Registers the ucf_chart metaboxes
		 * @author Jim Barnes
		 * @since 1.0.0
		 **/
		public static function register_metaboxes() {

		}

		/**
		 * Prints out the fields for the ucf_chart metabox
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @param WP_POST $post The WordPress post object for the current post being edited.
		 * @return string The html output for the ucf_chart_metabox
		 **/
		public static function register_fields( $post ) {

		}

		/**
		 * Handles saving meta_data from the ucf_chart_metabox
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @param int $post_id The id of the post being saved
		 **/
		public static function save_metaboxes( $post_id ) {

		}

		/**
		 * Returns the labels used for registering the ucf_chart post type
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @param string $singular The singular name of the post type
		 * @param string $plural The plural name of the post type
		 * @param string $text_domain The text_domain for translation functions
		 * @return array The array of labels
		 **/
		public static function labels( $singular, $plural, $text_domain ) {
			return array(
				'name'                  => _x( $plural, 'Post Type General Name', $text_domain ),
				'singular_name'         => _x( $singular, 'Post Type Singular Name', $text_domain ),
				'menu_name'             => __( $plural, $text_domain ),
				'name_admin_bar'        => __( $singular, $text_domain ),
				'archives'              => __( $plural . ' Archives', $text_domain ),
				'parent_item_colon'     => __( 'Parent ' . $singular . ':', $text_domain ),
				'all_items'             => __( 'All ' . $plural, $text_domain ),
				'add_new_item'          => __( 'Add New ' . $singular, $text_domain ),
				'add_new'               => __( 'Add New', $text_domain ),
				'new_item'              => __( 'New ' . $singular, $text_domain ),
				'edit_item'             => __( 'Edit ' . $singular, $text_domain ),
				'update_item'           => __( 'Update ' . $singular, $text_domain ),
				'view_item'             => __( 'View ' . $singular, $text_domain ),
				'search_items'          => __( 'Search ' . $plural, $text_domain ),
				'not_found'             => __( 'Not found', $text_domain ),
				'not_found_in_trash'    => __( 'Not found in Trash', $text_domain ),
				'featured_image'        => __( 'Featured Image', $text_domain ),
				'set_featured_image'    => __( 'Set featured image', $text_domain ),
				'remove_featured_image' => __( 'Remove featured image', $text_domain ),
				'use_featured_image'    => __( 'Use as featured image', $text_domain ),
				'insert_into_item'      => __( 'Insert into ' . $singular, $text_domain ),
				'uploaded_to_this_item' => __( 'Uploaded to this ' . $singular, $text_domain ),
				'items_list'            => __( $plural . ' list', $text_domain ),
				'items_list_navigation' => __( $plural . ' list navigation', $text_domain ),
				'filter_items_list'     => __( 'Filter ' . $plural . ' list', $text_domain ),
			);
		}

		/**
		 * Returns the args used to register the ucf_chart post type
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @param array $labels The labels array
		 * @return array The argument array
		 **/
		public static function args( $labels ) {
			$singular = $labels['singular'];
			$plural = $labels['plural'];
			$text_domain = $labels['text_domain'];

			$args = array(
				'label'                 => __( $singular, $text_domain ),
				'description'           => __( 'Defines a graph or chart', $text_domain ),
				'labels'                => self::labels( $singular, $plural, $text_domain ),
				'supports'              => array( 'title', 'revisions', ),
				'taxonomies'            => self::taxonomies(),
				'hierarchical'          => false,
				'public'                => true,
				'show_ui'               => true,
				'show_in_menu'          => true,
				'menu_position'         => 5,
				'menu_icon'             => 'dashicons-chart-pie',
				'show_in_admin_bar'     => true,
				'show_in_nav_menus'     => true,
				'can_export'            => true,
				'has_archive'           => true,
				'exclude_from_search'   => false,
				'publicly_queryable'    => true,
				'capability_type'       => 'post',
			);

			$args = apply_filters( 'ucf_chart_post_type_args', $args );

			return $args;
		}
	}
}
