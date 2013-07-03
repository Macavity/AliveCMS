<div style="width:70%;margin-left:auto;margin-right:auto;margin-top:20px;margin-bottom:20px;font-size:14px;">

<script type="text/javascript">
	setTimeout(function()
	{
		window.location.reload(true);
	}, 1000);
</script>

{if $type == "offline"}
	We're sorry but the realm we were trying to contact appears to be offline. Your points have been restored. Please try again later.
{elseif $type == "character"}
	We can't send you the items as you don't have a character. Your points have been restored.
{elseif $type == "character_exists"}
	The entered character does not exist.
{elseif $type == "character_not_mine"}
	The entered character does not belong to you.
{/if}

<a href="javascript:window.location.reload(true)">Go back</a>

</div>