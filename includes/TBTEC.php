<?php
namespace TBTEC;

include_once PLUGIN_PATH.'includes/Admin/Admin.php';
include_once PLUGIN_PATH.'includes/Frontend/Frontend.php';

function is_tec_activated() {
	return class_exists( '\Tribe__Events__Main' );
}

function get_settings() {
	
	$defaults = array(
		'ticket_buttons_label' => __('Tickets', 'ticket-buttons-for-the-events-calendar' ),
		'single_event_location' => 'single_event_after_the_meta',
		'single_event_blocks_location' => 'single_event_meta_secondary_section_end',
	);

	$settings = \wp_parse_args( \get_option( 'TBTEC', array() ), $defaults );

	return $settings;
	
}