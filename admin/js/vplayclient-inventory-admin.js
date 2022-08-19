(function( $ ) {
	'use strict';
	$('.delete_transaction').click(function(e){
		var con = confirm("Are you sure you want to delete this transaction?");
		if(!con)
			e.preventDefault();
	});
})( jQuery );
