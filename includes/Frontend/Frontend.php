<?php
namespace TBTEC\Frontend;

add_action( 'init', __NAMESPACE__.'\add_tickets_button_to_single_event' );
add_action( 'wp_enqueue_scripts', __NAMESPACE__.'\enqueue_scripts' );

function add_tickets_button_to_single_event() {
	
	$settings = \TBTEC\get_settings();

	add_action( 
		sprintf( 
			'tribe_events_%s',
			$settings[ 'single_event_location' ]
		),
		__NAMESPACE__.'\do_single_event_tickets_button'
	);
	
	add_action( 
		sprintf( 
			'tribe_events_%s',
			$settings[ 'single_event_blocks_location' ]
		),
		__NAMESPACE__.'\do_single_event_tickets_button'
	);
	
}

function do_single_event_tickets_button() {

	$settings = \TBTEC\get_settings();
	
	?><div class="tbtec-tickets tribe-common">
		
		<h2 class="tribe-common-h4 tribe-common-h--alt">Tickets</h2><?php
			
		$prices = \TBTEC\get_prices( get_the_id() );
		
		foreach( $prices as $price ) {
			?><div class="tbtec-ticket">
				<div class="tbtec-ticket-title tribe-common-h7 tribe-common-h6--min-medium"><?php
					if ( !empty( $price[ 'name' ] ) ) {
						echo esc_html( $price[ 'name' ] );
					}
				?></div>
				<div class="tbtec-ticket-price tribe-common-b2 tribe-common-b3--min-medium"><?php
					if ( isset( $price[ 'amount' ] ) ) {
						echo esc_html( tribe_format_currency( $price[ 'amount' ] ) );
					}
				?></div>
			</div><?
		}
		
		$tickets_button_url = \TBTEC\get_url( get_the_id() );
	
		if ( !empty( $tickets_button_url ) ) {
			?><div class="tbtec_ticket-button">
				<a class="tribe-common-c-btn-border" href="<?php
					echo esc_attr( $tickets_button_url );
				?>" sstyle="color: var( --tec-color-background ); background-color: var(--tec-color-accent-primary); width: auto;"><?php
					echo esc_html( $settings[ 'ticket_buttons_label' ] ); 
				?></a>
			</div><?php
		}

	?></div><?php
	
}

function enqueue_scripts() {
	
	wp_enqueue_style( 'tectb', \TBTEC\PLUGIN_URI.'/assets/css/style.css', array(), \TBTEC\VERSION );
	
}
