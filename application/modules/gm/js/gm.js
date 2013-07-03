var Gm = {
	
	view: function(field)
	{
		var ticket = $(field).parents(".gm_ticket");

		ticket.children(".gm_ticket_info").slideUp(300, function()
		{
			ticket.children(".gm_ticket_info_full").slideDown(300, function()
			{
				ticket.children(".gm_tools").fadeIn(300);
			});
		});
	},

	hide: function(field)
	{
		var ticket = $(field).parents(".gm_ticket");

		ticket.children(".gm_tools").fadeOut(300, function()
		{
			ticket.children(".gm_ticket_info_full").slideUp(300, function()
			{
				ticket.children(".gm_ticket_info").slideDown(300);
			});
		});
	},

	ban: function()
	{
		var html = '<input type="text" id="ban_account" placeholder="Account name" value=""/><br /><input type="text" id="reason" placeholder="Ban reason" value=""/>';

		UI.confirm(html, "Ban", function()
		{
			var account = $("#ban_account").val();
			var reason = $("#reason").val();

			$.post(Config.URL + "gm/ban/" + account, {reason: reason, csrf_token_name: Config.CSRF}, function(data)
			{
				console.log(data);
				UI.alert("Account " + account + " has been banned");
			});
		});
	},

	kick: function(realm)
	{
		var html = '<input type="text" id="kick_character" placeholder="Character name" value=""/>';

		UI.confirm(html, "Kick", function()
		{
			var character = $("#kick_character").val();
			
			$.get(Config.URL + "gm/kick/" + realm + "/" + character, function(data)
			{
				console.log(data);
				UI.alert("Character has been kicked");
			});
		});
	},

	close: function(realm, id, field)
	{
		UI.confirm("Are you sure you want to close this ticket?", "Close", function()
		{
			$(field).parents(".gm_ticket").slideUp(300, function()
			{
				$(this).remove();
			});

			$.get(Config.URL + "gm/close/" + realm + "/" + id, function(data)
			{
				console.log(data);
			});
		});
	},

	answer: function(realm, id, field)
	{
		var html = '<textarea id="answer_message" style="width:90%"></textarea>';

		UI.confirm(html, "Send", function()
		{
			var message = $("#answer_message").val();

			$.post(Config.URL + "gm/answer/" + realm + "/" + id, {csrf_token_name: Config.CSRF, message:message}, function(data)
			{
				console.log(data);
				UI.alert("Mail has been sent");
			});
		});
	},

	unstuck: function(realm, id, field)
	{
		$.post(Config.URL + "gm/unstuck/" + realm + "/" + id, {csrf_token_name: Config.CSRF}, function(data)
		{
			console.log(data);

			if(data == '1')
			{
				UI.alert("The character has been teleported");
			}
			else
			{
				UI.alert("The character must be offline");
			}
		});
	},

	sendItem: function(realm, id, field)
	{
		var html = '<input type="text" id="item_id" placeholder="Item ID" value=""/>';

		UI.confirm(html, "Send", function()
		{
			var item = $("#item_id").val();

			$.post(Config.URL + "gm/sendItem/" + realm + "/" + id, {csrf_token_name: Config.CSRF, item:item}, function(data)
			{
				console.log(data);
				UI.alert("Item has been sent");
			});
		});
	}
}