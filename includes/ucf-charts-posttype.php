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

			if ( UCF_Chart_Config::get_option_or_default( 'include_fields' ) ) {
				add_action( 'add_meta_boxes', array( 'UCF_Chart_PostType', 'register_metaboxes' ) );
				add_action( 'save_post', array( 'UCF_Chart_PostType', 'save_metaboxes' ) );
			}
		}

		/**
		 * Registers the ucf_chart metaboxes
		 * @author Jim Barnes
		 * @since 1.0.0
		 **/
		public static function register_metaboxes() {
			add_meta_box(
				'ucf_chart_metabox',
				'UCF Chart Fields',
				array( 'UCF_Chart_PostType', 'register_fields' ),
				'ucf_chart',
				'normal',
				'low'
			);
		}

		/**
		 * Prints out the fields for the ucf_chart metabox
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @param WP_POST $post The WordPress post object for the current post being edited.
		 * @return string The html output for the ucf_chart_metabox
		 **/
		public static function register_fields( $post ) {
			wp_nonce_field( 'ucf_chart_nonce_save', 'ucf_chart_nonce' );
			$upload_link = esc_url( get_upload_iframe_src( 'media', $post->ID ) );

			$chart_types = UCF_Chart_Common::get_chart_types();

			$chart_type       = get_post_meta( $post->ID, 'ucf_chart_type', TRUE );
			$data_json        = get_post_meta( $post->ID, 'ucf_chart_data_json', TRUE );
			$data_json_url    = wp_get_attachment_url( $data_json );
			$options_json     = get_post_meta( $post->ID, 'ucf_chart_options_json', TRUE );
			$options_json_url = wp_get_attachment_url( $options_json );

			// Existing asset IDs are invalid if the attachment URL can't be retrieved
			// (i.e. if the attachment was deleted).
			if ( ! $data_json_url ) {
				$data_json = null;
			}

			if ( ! $options_json_url ) {
				$options_json = null;
			}
?>
			<table class="form-table">
				<tbody>
					<tr>
						<th><strong>Chart Type</strong></th>
						<td>
							<select class="meta-select-field" id="ucf_chart_type" name="ucf_chart_type">
							<?php foreach( $chart_types as $key => $val ) : ?>
								<option value="<?php echo $key; ?>"<?php echo ( $chart_type === $key ) ? ' selected' : ''; ?>><?php echo $val; ?></option>
							<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<th><strong>Chart Data JSON</strong></th>
						<td>
							<div class="data-json-preview meta-file-wrap <?php echo empty( $data_json ) ? ' hidden' : ''?>">
								<span class="dashicons dashicons-media-code"></span>
								<span class="ucf_chart_data_json_filename"><?php echo ! empty( $data_json_url ) ? basename( $data_json_url ) : ''; ?></span>
							</div>
							<p class="hide-if-no-js">
								<a class="data-json-upload meta-file-upload <?php echo ! empty ( $data_json ) ? ' hidden' : ''; ?>" href="<?php echo $upload_link; ?>">
									Add File
								</a>
								<a class="data-json-remove meta-file-upload <?php echo empty( $data_json ) ? ' hidden' : ''; ?>" href="#">
									Remove File
								</a>
							</p>
							<input class="meta-file-field" id="ucf_chart_data_json" name="ucf_chart_data_json" type="hidden" value="<?php echo ! empty( $data_json ) ? htmlentities( $data_json ) : ''; ?>">
						</td>
					</tr>
					<tr>
						<th><strong>Chart Options JSON</strong></th>
						<td>
							<div class="options-json-preview meta-file-wrap <?php echo empty( $options_json ) ? ' hidden' : ''?>">
								<span class="dashicons dashicons-media-code"></span>
								<span class="ucf_chart_options_json_filename"><?php echo ! empty( $options_json_url ) ? basename( $options_json_url ) : ''; ?></span>
							</div>
							<p class="hide-if-no-js">
								<a class="options-json-upload meta-file-upload <?php echo ! empty ( $options_json ) ? ' hidden' : ''; ?>" href="<?php echo $upload_link; ?>">
									Add File
								</a>
								<a class="options-json-remove meta-file-upload <?php echo empty( $options_json ) ? ' hidden' : ''; ?>" href="#">
									Remove File
								</a>
							</p>
							<input class="meta-file-field" id="ucf_chart_options_json" name="ucf_chart_options_json" type="hidden" value="<?php echo ! empty( $options_json ) ? htmlentities( $options_json ) : ''; ?>">
						</td>
					</tr>
				</tbody>
			</table>
<?php
		}

		/**
		 * Handles saving meta_data from the ucf_chart_metabox
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @param int $post_id The id of the post being saved
		 **/
		public static function save_metaboxes( $post_id ) {
			$post_type = get_post_type( $post_id );

			// If not a ucf_chart, return;
			if ( $post_type !== 'ucf_chart' ) return;

			// If the nonce is not present or is invalid, return.
			if (
				! isset( $_POST['ucf_chart_nonce'] )
				|| ! wp_verify_nonce( $_POST['ucf_chart_nonce'], 'ucf_chart_nonce_save' )
			) return;

			$chart_type   = isset( $_POST['ucf_chart_type'] ) ? $_POST['ucf_chart_type'] : null;
			$data_json    = isset( $_POST['ucf_chart_data_json'] ) ? $_POST['ucf_chart_data_json'] : null;
			$options_json = isset( $_POST['ucf_chart_options_json'] ) ? $_POST['ucf_chart_options_json'] : null;

			if ( ! add_post_meta( $post_id, 'ucf_chart_type', $chart_type, true ) ) {
				update_post_meta( $post_id, 'ucf_chart_type', $chart_type );
			}

			if ( ! add_post_meta( $post_id, 'ucf_chart_data_json', $data_json, true ) ) {
				update_post_meta( $post_id, 'ucf_chart_data_json', $data_json );
			}

			if ( ! add_post_meta( $post_id, 'ucf_chart_options_json', $options_json, true ) ) {
				update_post_meta( $post_id, 'ucf_chart_options_json', $options_json );
			}
		}

		public static function admin_enqueue_scripts( $hook ) {
			global $post;

			if (
				( $hook === 'post-new.php' || $hook === 'post.php' )
				&& 'ucf_chart' === $post->post_type ) {

				wp_enqueue_media();

				wp_enqueue_script(
					'ucf_chart_admin_script',
					UCF_CHARTS__JS_URL . '/ucf-chart-admin.min.js',
					array( 'jquery' ),
					null,
					true
				);
			}
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

		/**
		 * Registers the taxonomies assigned to ucf_chart
		 * @author Jim Barnes
		 * @since 1.0.0
		 * @return array The array of taxonomies
		 **/
		public static function taxonomies() {
			$taxonomies = array();

			$taxonomies = apply_filters( 'ucf_chart_taxonomies', $taxonomies );

			return $taxonomies;
		}
	}
}
