function preSubmit(form) {
	var inputPass     = form.pw.value;
	form.pw.value     = "";
	var inputHash     = form.token.value;

	var inputPassHash = hex_md5(inputPass);
	var passwordHash  = hex_md5(inputHash + inputPassHash);
/*
	var inputPassHash = SHA1(inputPass);
	var passwordHash  = SHA1(inputHash + inputPassHash);
*/
	form.pass.value   = passwordHash;
}
