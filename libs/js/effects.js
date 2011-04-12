$(document).ready(function() {
	function hideGrr() {
		$('.growl').animate({ opacity: 'hide' }, 800);
	}
	setTimeout( hideGrr,  8000 );
	
	$('.message').hover(
		function() {
			$(this).stop().animate({"opacity": "0.60"}, 500);
		},
		function() {
			$(this).stop().animate({"opacity": "1"}, 500);
		});
});
