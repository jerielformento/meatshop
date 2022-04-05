
function vldUser() {	
	var user = maxLength($("input[name='u-user']").val(), 20);
	var pass = maxLength($("input[name='u-pass']").val(), 30);

	if(user === "" || pass === "") {
		alertBox('', 'msg-alert', 'danger', 'Login failed, please try again!', '', '');
		return false;
	} else {
		return true;
	}
}

$('input[name="sub-log"]').on('click', function() {
	$(this).val("Connecting ...").attr('disabled','disabled');
	$('#log-form').submit();
}); 