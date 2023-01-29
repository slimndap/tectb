<?php
namespace TBTEC\Frontend;

add_action( 'init', __NAMESPACE__.'\add_tickets_button_to_single_event' );

function add_tickets_button_to_single_event() {
	
	$settings = \TBTEC\get_settings();

	add_action( 
		sprintf( 
			'tribe_events_%s',
			$settings[ 'single_event_location' ]
		),
		__NAMESPACE__.'\do_single_event_tickets_button'
	);
	
}

function do_single_event_tickets_button() {

	$settings = \TBTEC\get_settings();
	
	$tickets_button_url = get_post_meta( get_the_id(), '_tickets_button_url', true );

	if ( empty( $tickets_button_url ) ) {
		return;
	}
	
	?><div class="tribe-common event-tickets">
		<a class="tribe-common-c-btn-border" href="<?php
			echo esc_attr( $tickets_button_url );
		?>" style="color: var( --tec-color-background ); background-color: var(--tec-color-accent-primary); width: auto;"><?php
			echo esc_html( $settings[ 'ticket_buttons_label' ] ); 
		?></a>
	</div><?php
}
