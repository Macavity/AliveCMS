<script type="text/javascript">
	if(typeof Search != "undefined")
	{
		Search.current = null;
	}
</script>
<div id="search_box">
	<form onSubmit="Search.submit();return false;">
		<table width="100%">
			<tr>
				<td><input type="text" placeholder="Search characters, items and guilds..." id="search_field" /></td>
				<td width="70px"><input type="submit" value="Search" /></td>
			<tr>
		</table>
		<div class="clear"></div>
	</form>

	<div id="search_results"></div>
</div>