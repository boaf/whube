function preSubmit(form) {

	var inputPass     = form.password0.value;
	var inputPassHash = hex_md5(inputPass);
	form.password0.value   = inputPassHash;
	
	var inputPass     = form.password1.value;
	var inputPassHash = hex_md5(inputPass);
/*
	var inputPass     = form.password0.value;
	var inputPassHash = SHA1(inputPass);
	form.password0.value   = inputPassHash;
	
	var inputPass     = form.password1.value;
	var inputPassHash = SHA1(inputPass);
	form.password1.value   = inputPassHash;
*/
}
