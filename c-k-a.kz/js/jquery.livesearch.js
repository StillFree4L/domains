/***
jQuery('#jquery-live-search-example input[name="q"]').liveSearch({url: Router.urlForModule('SearchResults') + '&q='});
***/
jQuery.fn.liveSearch = function (conf) {
	var config = jQuery.extend({
		url:		'/search-results.php?q=', 
		callback:   function(data){window.console && console.log(data);}, 
		typeDelay:	200,
		id:       	'jquery-live-search', 
	}, conf);

	var liveSearch	= jQuery('#' + config.id);

	return this.each(function () {
		var input = jQuery(this).attr('autocomplete', 'off');

		input
			// On focus, if the live-search is empty, perform an new search
			// If not, just slide it down. Only do this if there's something in the input
			.focus(function () {
				if (this.value !== '') {
					// Perform a new search if there are no search results
					if (liveSearch.html() == '') {
						this.lastValue = '';
						input.keyup();
					}
				}
			})
			// Auto update live-search onkeyup
			.keyup(function () {
				// Don't update live-search if it's got the same value as last time
				if (this.value != this.lastValue) {

					var q = this.value;

					// Stop previous ajax-request
					if (this.timer) {
						clearTimeout(this.timer);
					}

					// Start a new ajax-request in X ms
					this.timer = setTimeout(function () {
						jQuery.post(config.url, {q: q},  function (data) {
							config.callback(data);
						});
					}, config.typeDelay);

					this.lastValue = this.value;
				}
			});
	});
};
