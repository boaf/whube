$(window).load( function() {
	$('#user').keyup(function(event) {
		if ((this.value.length==0) || (this.value==null)) {
			$('#noassign').attr('checked', true);
		} else {
			$('#noassign').attr('checked', false);
		}
	});
});
