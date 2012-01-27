<div>
	<label for="{hash}">{label}</label>
	<input type="text" name="range[{name}]" id="{hash}" value="{default}" /> (da {min} a {max})
</div>

<script type="text/javascript">
	$(function() {
		$( "#{hash}" ).closest('div').append('<div id="{hash}_slider_range"></div>');
		$( "#{hash}_slider_range" ).slider({
			range: true,
			min: {min},
			max: {max},
			values: [ {min}, {max} ], // TODO: inserire valori attuali
			slide: function( event, ui ) {
				$( "#{hash}" ).val( ui.values[ 0 ] + " - " + ui.values[ 1 ] );
			}
		});
		
		$( "#{hash}" ).val( $( "#{hash}_slider_range" ).slider( "values", 0 ) +
			" - " + $( "#{hash}_slider_range" ).slider( "values", 1 ) );
	});
</script>