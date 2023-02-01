<?php
namespace TBTEC;

include_once PLUGIN_PATH.'includes/Admin/Admin.php';
include_once PLUGIN_PATH.'includes/Frontend/Frontend.php';

register_post_meta( 'tribe_events', '_tickets_button_price', array(
	'type' => 'array',
	'description' => '',
	'single' => false,
	'sanitize_callback' => __NAMESPACE__.'\sanitize_button_price',
) );

function is_tec_activated() {
	return class_exists( '\Tribe__Events__Main' );
}

function get_settings() {
	
	$defaults = array(
		'ticket_buttons_heading' => __('Tickets', 'ticket-buttons-for-the-events-calendar' ),
		'ticket_buttons_label' => __('Get tickets', 'ticket-buttons-for-the-events-calendar' ),
		'single_event_location' => 'single_event_after_the_meta',
		'single_event_blocks_location' => 'single_event_meta_secondary_section_end',
	);

	$settings = \wp_parse_args( \get_option( 'TBTEC', array() ), $defaults );

	return $settings;
	
}

function get_tickets_button_html( $event_id ) {
	
	return \TBTEC\Frontend\get_html_for_event( $event_id );
	
}

function get_events_for_series( $series_id ) {
	
	return tribe_get_events(
		array(
			'starts_after' => 'now',
			'series' => $series_id,
		)
	);
	
}

function get_tickets_for_series( $series_id ) {

	$series_tickets = array();

	$events = tribe_get_events(
		array(
			'starts_after' => 'now',
			'series' => $series_id,
		)
	);

	foreach( $events as $event ) {
		
		$event_tickets = get_tickets_for_event( $event->ID );
		
		if ( empty( $event_tickets ) ) {
			continue;
		}
		
		$series_tickets = array_merge( $series_tickets, $event_tickets );
		
	}

	return $series_tickets;
	
}

function get_tickets_for_event( $event_id ) {
	
	$tickets = array();

	$prices = \TBTEC\get_prices( $event_id );
	$url = \TBTEC\get_url( $event_id );
	
	if ( !empty( $prices ) || !empty( $url ) ) {
		
		$tickets[] = array(
			'prices' => $prices,
			'url' => $url,
			'startdate' => tribe_get_start_date( $event_id ),
		);
		
	}
	
	return $tickets;
}

function sanitize_button_price( $meta_value, $meta_key, $object_type, $object_subtype ) {
	
	if ( empty( $meta_value[ 'amount' ] ) ) {
		$meta_value[ 'amount' ] = 0;
	}
	
	$meta_value[ 'amount' ] = intval( $meta_value[ 'amount' ] );
	$meta_value[ 'name' ] = trim( $meta_value[ 'name' ] );
	
	return $meta_value;
}

function delete_prices( $event_id ) {
	return \delete_post_meta( $event_id, '_tickets_button_price' );	
}

function add_price( $amount, $name, $event_id )  {
	return \add_post_meta( 
		$event_id, 
		'_tickets_button_price', 
		array(
			'amount' => $amount,
			'name' => $name,
		),
		false 
	);	
}

function get_prices( $event_id ) {
	return \get_post_meta( $event_id, '_tickets_button_price', false );	
}

function update_url( $url, $event_id ) {
	return \update_post_meta( $event_id, '_tickets_button_url', $url );	
}

function get_url( $event_id ) {
	return \get_post_meta( $event_id, '_tickets_button_url', true );
}