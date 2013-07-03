var Orders = {

	refund: function(id, element)
	{
		$(element).parents("li").slideUp(300, function()
		{
			$(this).remove();
		});

		$.get(Config.URL + "store/admin_orders/refund/" + id);
	}
}