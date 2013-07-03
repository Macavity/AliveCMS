var Admin = {

	enableModule: function(moduleName, element)
	{
		$.post(Config.URL + 'admin/enable/' + moduleName, {csrf_token_name: Config.CSRF}, function(data)
		{
			if(data == 'SUCCESS')
			{
				$(element).attr("onClick", "Admin.disableModule('" + moduleName + "', this)").html("Disable");
				
				var parent = $(element).parent();

				$("#enabled_modules").append(parent[0]);
				$("#disabled_count").html(parseInt($("#disabled_count").html()) - 1);
				$("#enabled_count").html(parseInt($("#enabled_count").html()) + 1);
			}
		});
	},
	
	disableModule: function(moduleName, element)
	{
		UI.confirm("Are you sure you want to disable " + moduleName + "?", "Yes", function()
		{
			$.post(Config.URL + 'admin/disable/' + moduleName, {csrf_token_name: Config.CSRF}, function(data)
			{
				if(data == 'SUCCESS')
				{
					$(element).attr("onClick", "Admin.enableModule('" + moduleName + "', this)").html("Enable");
					
					var parent = $(element).parent();

					$("#disabled_modules").append(parent[0]);
					$("#enabled_count").html(parseInt($("#enabled_count").html()) - 1);
					$("#disabled_count").html(parseInt($("#disabled_count").html()) + 1);
				}
				else
				{
					UI.alert(moduleName + " is a core module that can not be disabled!");
				}
			});
		});
	},

	currentHeader: false,

	changeHeader: function(current, blank, theme)
	{
		if(this.currentHeader)
		{
			current = this.currentHeader;
		}

		var changeHTML = '<a style="display:inline;float:none;color:#1D6D9C;font-weight:normal;margin:0px;padding:0px;width:auto;" target="_blank" href="' + Config.URL + 'application/themes/' + theme + '/' + blank + '">Click here</a> for an empty copy of the header' + 
						'<input type="text" id="theme_header" value="' + current + '" placeholder="http://"/>';

		UI.confirm(changeHTML, "Save", function()
		{
			var values = {
				header_url:$("#theme_header").val(),
				csrf_token_name:Config.CSRF
			};

			Admin.currentHeader = values.header_url;

			$.post(Config.URL + "admin/saveHeader", values);

			if(values.header_url.length > 0)
			{
				$("#header_field").html("Custom");
			}
			else
			{
				$("#header_field").html("Default");
			}
		});
	},

	remoteCheck: function()
	{
		$.get(Config.URL + "admin/remote", function(data)
		{
			switch(data)
			{
				case '1':
					$("#system_box").addClass("alert");
					$("#update").show();
				break;

				case '2':
					UI.alert('This copy of FusionCMS has been terminated due to illegal usage. If you actually own a legit copy, please contact us at fusion.raxezdev.com', 6000);

					setTimeout(function()
					{
						window.location = Config.URL;
					}, 6000);
				break;
			}
		});
	}
}


$(document).ready(function()
{
	Admin.remoteCheck();
	/*Router.initialize();*/
});