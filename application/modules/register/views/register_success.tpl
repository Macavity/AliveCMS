{if $email_activation}
	<span id="success">
		The account <b>{$account}</b> has been created. Please check your email to activate your account.
	</span>
{else}
	<script type="text/javascript">
		{if $bridge}

			var data = {
				username: "{$username}",
				password: "{$password}",
				email: "{$email}"
			};

			$.post(Config.URL + "application/modules/register/bridges/{$bridgeName}.php", data, function(res)
			{
				if(res.length)
				{
					alert(res);
				}
				
				$("#bridge").hide();
				$("#success").show();
				setTimeout(function(){
					window.location = "{$url}ucp";
				}, 1000);
			});
		{else}
			setTimeout(function(){
				window.location = "{$url}ucp";
			}, 1000);
		{/if}
	</script>

	{if $bridge}
		<span id="bridge">Creating accounts on the forum, please wait...</span>
	{/if}

	<span id="success" {if $bridge}style="display:none;"{/if}>
		The account <b>{$account}</b> has been created. You are being redirected to the {anchor("ucp", "user control panel")}...
	</span>
{/if}