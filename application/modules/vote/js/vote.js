var Vote = {
	
	/**
	 * Opens the link and changes the vote now button
	 */
	open: function(id, time)
	{
		// Open the link
		window.open(Config.URL + "vote/site/" + id);

		// Change the "vote now" button
		$("#vote_field_" + id).html(time + " hours remaining");
	}
}