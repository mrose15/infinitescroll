jQuery(function($){
	$( document ).ready( function() {
		$('.home .site-main').append( '<button class="load-more btn">Click to load more</button>' );
		var button = $('.site-main .load-more');
		var page = 2;
		var loading = false;

		$('body').on('click', '.load-more', function(){
			if( ! loading ) {
					loading = true;
					var data = {
						action: 'be_ajax_load_more',
						page: page,
						query: beloadmore.query,
					};
					$.post(beloadmore.url, data, function(res) {
						if( res.success) {
							$('.site-main').append( res.data );
							$('.site-main').append( button );
							page = page + 1;
							loading = false;
						} else {
							 console.log(res);
						}
					}).fail(function(xhr, textStatus, e) {
						 console.log(xhr.responseText);
					});
			}
		});
	});	
});