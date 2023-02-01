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

function get_html_for_event( $event_id ) {
	
	$settings = \TBTEC\get_settings();
	
	$series = tec_event_series( get_the_id() );

	if ( $series ) {
		$tickets = \TBTEC\get_tickets_for_series( $series->ID );
	} else {
		$tickets = \TBTEC\get_tickets_for_event( get_the_id() );	
	}
		
	if ( empty( $tickets ) ) {
		return;
	}
	
	ob_start();

	?><div class="tbtec-tickets tribe-common">
		
		<h2 class="tribe-common-h4 tribe-common-h--alt"><?php
			echo esc_html( $settings[ 'ticket_buttons_heading' ] ); 
		?></h2><?php
			
		foreach( $tickets as $ticket ) {
			
			?><h3 class="tbtec-ticket-startdate tribe-common-h7"><?php echo $ticket[ 'startdate' ]; ?></h3><?php
			foreach( $ticket[ 'prices' ] as $price ) {
				?><div class="tbtec-ticket-price">
					<div class="tbtec-ticket-price-title tribe-common-b2 tribe-common-b3--min-medium"><?php
						if ( !empty( $price[ 'name' ] ) ) {
							echo esc_html( $price[ 'name' ] );
						}
					?></div>
					<div class="tbtec-ticket-price-amount tribe-common-b2 tribe-common-b3--min-medium"><?php
						if ( isset( $price[ 'amount' ] ) ) {
							echo esc_html( tribe_format_currency( $price[ 'amount' ] ) );
						}
					?></div>
				</div><?php
			}
			
			if ( !empty( $ticket[ 'url' ] ) ) {
	
				?><div class="tbtec_ticket-button">
					<a class="tribe-common-c-btn-border" href="<?php
						echo esc_attr( $ticket[ 'url' ] );
					?>"><?php
						echo esc_html( $settings[ 'ticket_buttons_label' ] ); 
					?></a>
				</div><?php
			}
			
		}
		

	?></div><?php
		
	return ob_get_clean();
}

function do_single_event_tickets_button() {

	echo get_html_for_event( get_the_id() );
	
}

function enqueue_scripts() {
	
	wp_enqueue_style( 'tectb', \TBTEC\PLUGIN_URI.'/assets/css/style.css', array(), \TBTEC\VERSION );
	
}
