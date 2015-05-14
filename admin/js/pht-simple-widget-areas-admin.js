(function ( $ ) {
	"use strict";
	
	$( function () {
		
		var 
		$form = $( '#phtswa-form' ),
		
		registeredSidebars = $.map( phtswa_data.reserved_terms, function( value, key ) {
			return value.id;
		}), 
		
		errorMessages = $.map( phtswa_data.error_messages, function( value, key ) {
			return value;
		}),
		
		keyRegex = new  RegExp(phtswa_data.regex_pattern);

		$("#widgets-right").before($('#phtswa-container'));

		phtswa_data.simple_widget_areas.forEach( function( el, index ){
			var id = el.id, 
			$el = $("[id="+id+"]"),
			$remove_link = $("[id=remove-"+id+"]");

			$el.children('.sidebar-name')
				.addClass('phtswa-title')
				.children('h3').before($remove_link)
				.end().append($('<span class="phtswa-small phtswa-id">id=' + id +'</span>'));

		} );

		$(document).on( 'click', '.js-phtswa-confirm', function() {
			
			var confirmation = confirm( phtswa_data.confirmation );
			
			if ( ! confirmation ) {
				return false;
			}
			
		});
	
		
		$form.submit( function( event ) {		
			
			var key = $('#'+phtswa_data.field_id).val().trim(), 
				error = false;
			
			if ( ! keyRegex.test( key ) ) {
				if ( '' === key ) {
					error = errorMessages[0];
				} else {
					error = errorMessages[1];
				}
			
			} else {

				if ( -1 !== $.inArray( phtswa_data.prefix + key.replace( /\s/g, '' ).toLowerCase(), registeredSidebars ) ) {
					error = errorMessages[2];
				}
			
			}	
			
			if ( error ) {
				alert( error );
				event.preventDefault();
			}
			
		});

	});

}(jQuery));
