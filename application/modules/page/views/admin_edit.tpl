{TinyMCE()}
<section class="box big">
	<h2>Edit page</h2>

	<form onSubmit="Pages.send({$page.id}); return false">
		<label for="headline">Headline</label>
		<input type="text" id="headline" value="{$page.name}"/>
		
		<label for="identifier">Unique link identifier (as in mywebsite.com/page/<b>mypage</b>)</label>
		<input type="text" id="identifier" placeholder="mypage" value="{$page.identifier}" />

		<label for="rank_needed">Minimum user rank</label>
		<select id="rank_needed">
			{foreach from=$ranks item=rank}
				<option value="{$rank.id}" {if $page.rank_needed == $rank.id}selected{/if}>{$rank.rank_name}</option>
			{/foreach}
		</select>
        
        <label>Page Category</label>
        <select id="top_category">
            <option value="0" {if $page.top_category == 0}selected="selected"{/if}>- None -</option>
            {foreach from=$existingCats item=topCat}
                <option value="{$topCat.id}" {if $page.top_category == $topCat.id}selected="selected"{/if}>{$topCat.title}</option>
                {foreach from=$topCat.subCats item=subCat}
                <option value="{$subCat.id}" {if $page.top_category == $subCat.id}selected="selected"{/if}>{$topCat.title} &rarr; {$subCat.title}</option>
                {/foreach}
            {/foreach}
        </select>
        
        
		<label for="Pages_content">
			Content
		</label>
	</form>
		<div style="padding:10px;">
			<textarea name="pages_content" class="tinymce" id="pages_content" cols="30" rows="10">{$page.content}</textarea>
		</div>
	<form onSubmit="Pages.send({$page.id}); return false">
		<input type="submit" value="Save page" />
	</form>
</section>