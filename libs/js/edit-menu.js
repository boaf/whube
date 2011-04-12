$(window).load( function() {
	$("#edit-bug").hide();
	$("#edit-bug-control").click( function() {
		var height = ( $(window).height() - 100 );
		$(".prompt").height( height );
		$("#edit-interface").hide();
		$("#edit-bug").animate({
			opacity:   'show'
		}, 200, function() {
			$("#edit-interface").animate({
				height:   'show'
			}, 400, function() {
				// Animation complete.
			});
		});
	});
	$("#edit-close").click( function() {
		$("#edit-interface").animate({
			height:   'hide'
		}, 200, function() {
			$("#edit-bug").animate({
				opacity:   'hide'
			}, 400, function() {
				// Animation complete.
			});
		});
	});
});
