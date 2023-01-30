<?php
namespace TBTEC\Admin;

/*
add_action( 'init', __NAMESPACE__.'\add_tec_settings_tab' );
add_action( 'admin_init', __NAMESPACE__.'\save_settings' );
*/

add_action( 'add_meta_boxes', __NAMESPACE__.'\add_meta_box' );
add_action( 'save_post', __NAMESPACE__.'\save_meta_box' );
add_action( 'admin_enqueue_scripts', __NAMESPACE__.'\enqueue_scripts' );

function add_meta_box( $post_type ) {

	if ( 'tribe_events' != $post_type ) {
		return;
	}
	
	\add_meta_box(
		'TBTEC',
		__( 'Tickets button', 'ticket-buttons-for-the-events-calendar' ),
		__NAMESPACE__.'\do_meta_box_html',
		$post_type,
		'normal',
		'high'
	);
	
}

function do_meta_box_html( $post ) {

	wp_nonce_field( 'TBTEC', 'TBTEC_nonce' );

	$tickets_button_url = get_post_meta( $post->ID, '_tickets_button_url', true );
	$tickets_button_prices = get_post_meta( $post->ID, '_tickets_button_price', false );

	?><div id="tectb-admin-form">
		<table>
			<tbody>
				<tr>
					<th>
						<label for="tickets_button_url"><?php _e( 'Prices', 'ticket-buttons-for-the-events-calendar' ); ?>:	</label>
					</th>
					<td>
						<table id="tectb-admin-prices-table" class="widefat">
							<thead>
								<tr>
									<th>Amount</th>
									<th>Name</th>
									<th></th>
								</tr>
							</thead>							
							<tbody><?php
								for( $p = 0; $p < count( $tickets_button_prices ); $p++ ) {
									
									$tickets_button_price = $tickets_button_prices[ $p ];
									
									if ( empty( $tickets_button_price ) ) {
										continue;
									}

									?><tr>
										<td>
											<input type="number" name="tickets_button_prices[<?php echo $p; ?>][amount]" value="<?php echo esc_attr( $tickets_button_price[ 'amount' ] ); ?>" />
										</td>
										<td>
											<input type="text" name="tickets_button_prices[<?php echo $p; ?>][name]" value="<?php echo esc_attr( $tickets_button_price[ 'name' ] ); ?>" />
										</td>
										<td>
											<button><span class="dashicons dashicons-trash"></span></button>
										</td>
									</tr><?php
								}
							?></tbody>
						</table>
						<div class="tectb-admin-actions">
							<button id="tectb-admin-add-price" class="button-secondary tribe-button-icon tribe-button-icon-plus" type="button">New price</button>
						</div>
					</td>
				</tr>
				<tr>
					<th>
						<label for="tickets_button_url"><?php _e( 'Tickets link', 'ticket-buttons-for-the-events-calendar' ); ?>:	</label>
					</th>
					<td>
						<input type="url" id="tickets_button_url" name="tickets_button_url" value="<?php echo esc_attr( $tickets_button_url ); ?>" size="25" style="width: 70%" />
					</td>
				</tr>
			</tbody>
		</table>
	</div><?php
		
}

function enqueue_scripts() {
	
	wp_enqueue_style( 'tectb-admin', \TBTEC\PLUGIN_URI.'/assets/css/admin.css', array(), \TBTEC\VERSION );
	wp_enqueue_script( 'tectb-admin', \TBTEC\PLUGIN_URI.'/assets/js/admin.js', array( 'jquery' ), \TBTEC\VERSION );
}

function save_meta_box( $post_id ) {

		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST[ 'TBTEC_nonce' ] ) ) {
			return $post_id;
		}

		$nonce = $_POST[ 'TBTEC_nonce' ];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'TBTEC' ) ) {
			return $post_id;
		}

		/*
		 * If this is an autosave, our form has not been submitted,
		 * so we don't want to do anything.
		 */
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// Sanitize the user input.
		$tickets_button_url = sanitize_text_field( $_POST[ 'tickets_button_url' ] );

		if ( empty( $_POST[ 'tickets_button_prices' ] ) ) {
			$tickets_button_prices = array();
		} else {
			$tickets_button_prices = $_POST[ 'tickets_button_prices' ];
		}

		// Update the meta field.
		\TBTEC\update_url($tickets_button_url, $post_id );

		\TBTEC\delete_prices( $post_id );

		if ( empty( $tickets_button_prices ) || !is_array( $tickets_button_prices ) ) {
			// Do nothing.
		} else {
			foreach( $tickets_button_prices as $tickets_button_price ) {
				\TBTEC\add_price( 
					$tickets_button_price[ 'amount' ],
					$tickets_button_price[ 'name' ],
					$post_id
				);
			}
		}
		
		
}

function add_tec_settings_tab() {
	
	if ( !\TBTEC\is_tec_activated() ) {
		return;
	}
	
	$args = array(
		'fields' => array(
			'ticket_buttons_heading' => array(
				'type' => 'heading',
				'label' => __( 'Tickets button settings', 'ticket-buttons-for-the-events-calendar' ),				
			),
			'ticket_buttons_label' => array(
				'type' => 'text',
				'label' => __( 'Default tickets button label', 'ticket-buttons-for-the-events-calendar' ),
				'default' => __( 'Tickets', 'TBTEC' ),
				'parent_option' => 'TBTEC',
			),
			'single_event_location' => array(
				'type' => 'dropdown',
				'label' => __( 'Position of tickets button', 'ticket-buttons-for-the-events-calendar' ),
				'options' => array(
					'single_event_after_the_meta' => 'Below the event details [default]',	
					'single_event_before_the_meta' => 'Above the event details',	
					'single_event_after_the_content' => 'Below the event description',	
					'single_event_before_the_content' => 'Above the event description',	
				),
				'parent_option' => 'TBTEC',
			),
		),
	);
	
	$tab = new \Tribe__Settings_Tab( 'TBTEC', __( 'Ticket buttons', 'ticket-buttons-for-the-events-calendar' ), $args  );

}

function save_settings() {
	
	if ( empty( $_POST['tribe-save-settings'] ) ) {
		return;
	}

	// Check the nonce.
	if ( ! wp_verify_nonce( $_POST['tribe-save-settings'], 'saving' ) ) {
		wp_die();
	}
	
	if ( 'TBTEC' != $_POST[ 'current-settings-tab' ] ) {
		return;
	}
	
	$settings = array( 
		'ticket_buttons_label' => sanitize_text_field( $_POST[ 'ticket_buttons_label' ] ),
		'single_event_location' => sanitize_text_field( $_POST[ 'single_event_location' ] ),
	);
	
	update_option( 'TBTEC', $settings );
	
}

