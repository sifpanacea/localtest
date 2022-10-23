$(document).ready(function() {

// Email to all
$('input[name=emailall]').click(function(e) {
	if($('input[name=emailall]').is(':checked'))
	{
		$("input[name='email[]']").prop('checked', true);
		$("input[name='userlist[]']").prop('checked', true);
	}
	else
	{
	    $("input[name='email[]']").prop('checked', false);
		var sms_check = $('input[name=smsall]').is(':checked')
		if(sms_check == false)
		{
			$("input[name='userlist[]']").prop('checked', false);
		}
		
		
	}
});

// SMS to all
$('input[name=smsall]').click(function(e) {
	if($('input[name=smsall]').is(':checked'))
	{
		$("input[name='sms[]']").prop('checked', true);
		$("input[name='userlist[]']").prop('checked', true);
	}
	else
	{
	    $("input[name='sms[]']").prop('checked', false);
		var email_check = $('input[name=emailall]').is(':checked')
		if(email_check == false)
		{
			$("input[name='userlist[]']").prop('checked', false);
		}
		
	}
});

//Email to selected users
$("input[name='email[]']").click(function ()
{
   if($(this).is(':checked'))
	{
		$(this).parents("tr").find('input[name="userlist[]"]').prop('checked', true);
	}
	else
	{
		var check_individual_sms = $(this).parents("tr").find("input[name='sms[]']").is(':checked')
		if(check_individual_sms != true)
		{
			$(this).parents("tr").find('input[name="userlist[]"]').prop('checked', false);
		}
	}
});

//SMS to selected users
$("input[name='sms[]']").click(function ()
{
    if($(this).is(':checked'))
	{
		$(this).parents("tr").find('input[name="userlist[]"]').prop('checked', true);
	}
	else
	{
		var check_individual_email = $(this).parents("tr").find("input[name='email[]']").is(':checked')
		if(check_individual_email != true)
		{
			$(this).parents("tr").find('input[name="userlist[]"]').prop('checked', false);
		}
	}
});

//Select users
$("input[name='userlist[]']").click(function ()
{
    if($(this).is(':checked'))
	{
		$(this).parents("tr").find('input[name="email[]"]').prop('checked', true);
		$(this).parents("tr").find('input[name="sms[]"]').prop('checked', true);
	}
	else
	{
		$(this).parents("tr").find('input[name="email[]"]').prop('checked', false);
		$(this).parents("tr").find('input[name="sms[]"]').prop('checked', false);
	}
});


});

