
/**
 * @package FusionCMS
 * @version 6.X
 * @author Jesper Lindström
 * @author Xavier Geernick
 * @link http://raxezdev.com/fusioncms
 */

var Settings = {

	wrongPassword: null,
	canSubmit: true,

	submit: function()
	{
		// Client-side validation of the passwords
		if($("#new_password").val() !== $("#new_password_confirm").val())
		{
			if(Settings.canSubmit)
			{
				UI.alert("Passwords doesn't match!");
				Settings.canSubmit = false;
			}
		}
		else if(Settings.wrongPassword != null && Settings.wrongPassword == $("#old_password").val())
		{
			return false;
		}
		else
		{
			Settings.canSubmit = true;

			// Show that we're loading something
			$("#settings_ajax").html('<img src="' + Config.image_path + 'ajax.gif" />');

			// Gather the values
			var values = {
				old_password: $("#old_password").val(),
				new_password: $("#new_password").val(),
				csrf_token_name: Config.CSRF
			};

			// Submit the request
			$.post(Config.URL + "ucp/settings/submit", values, function(data)
			{
				if(/yes/.test(data))
				{
					$("#settings_ajax").html("Changes have been saved!");
				}
				else if(/no/.test(data))
				{
					$("#settings_ajax").html('');

					UI.alert("Incorrect password!");

					Settings.wrongPassword = $("#old_password").val();
				}
				else
				{
					$("#settings_ajax").html(data);
				}
			});
		}
	},

	submitInfo: function()
	{
		var value = $("#nickname_field").val(),
			loc = $("#location_field").val();

		if(value.length < 4 || value.length > 14)
		{
			UI.alert("Nickname must be between 4 and 14 characters long")
		}
		else if(loc.length > 14)
		{
			UI.alert("Location may only be up to 14 characters long")
		}
		else
		{
			// Show that we're loading something
			$("#settings_info_ajax").html('<img src="' + Config.image_path + 'ajax.gif" />');

			// Submit the request
			$.post(Config.URL + "ucp/settings/submitInfo",
			{
				nickname: value,
				location: loc,
				csrf_token_name: Config.CSRF
			},
			function(data)
			{
				if(/1/.test(data))
				{
					$("#settings_info_ajax").html("Changes have been saved!");
				}
				else if(/2/.test(data))
				{
					$("#settings_info_ajax").html('');
					UI.alert("Nickname is already taken");
				}
				else
				{
					$("#settings_info_ajax").html(data);
				}
			});
		}
	}
}