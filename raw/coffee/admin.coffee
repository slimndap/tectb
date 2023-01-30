init_admin_form = () ->
	
	$admin_form = jQuery '#tectb-admin-form'
	
	return if 1 > $admin_form.length
	
	$add_price_button = $admin_form.find '#tectb-admin-add-price'
	$prices_table = $admin_form.find '#tectb-admin-prices-table'
	
	$prices_rows = $prices_table.find 'tbody tr'
	
	$prices_table.toggleClass 'has-prices', 0 < $prices_rows.length
	
	$prices_rows.each ->
	
		$row = jQuery @
		$trash_button = 	$row.find 'button'
		$trash_button.unbind().click ->
		
			$row.remove()
			init_admin_form()
	
	$add_price_button.unbind().click ->
	
		index = $prices_rows.length
		console.log index
	
		$prices_table.find( 'tbody' ).append "
			<tr>
				<td>
					<input type=\"number\" name=\"tickets_button_prices[#{ index }][amount]\"  />
				</td>
				<td>
					<input type=\"text\" name=\"tickets_button_prices[#{ index }][name]\" />
				</td>
				<td>
					<button><span class=\"dashicons dashicons-trash\"></span></button>
				</td>
			</tr>"
	
		init_admin_form()
	

jQuery ->

	init_admin_form()