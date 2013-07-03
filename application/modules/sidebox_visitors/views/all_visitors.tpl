{if $visitors}
	{foreach from=$visitors item=visitor}
		<a href="{$smarty.const.pageURL}profile/{$visitor.user_id}">{$visitor.nickname}</a>,
	{/foreach}
{/if}

{$guests} {if $guests == 1}guest{else}guests{/if}.