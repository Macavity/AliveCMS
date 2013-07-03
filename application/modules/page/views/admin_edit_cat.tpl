<section class="box big">
    <h2>Edit page category</h2>

    <form onSubmit="Pages.sendCat({$pageCat.id}); return false">
        <label for="headline">Title</label>
        <input type="text" id="title" value="{$pageCat.title}" />
        
        <label for="path">Path (as in <b>/server/index/</b>)</label>
        <input type="text" id="path" value="{$pageCat.path}" />
        
        <label for="top_cat">Top Category</label>
        <select id="top_cat">
            <option value="0">- None -</option>
            {foreach from=$existingCats item=topCat}
                <option value="{$topCat.id}" {if $topCat.top_category == $pageCat.id}selected="selected"{/if}>{$topCat.path}</option>
            {/foreach}
        </select>
     
        <input type="submit" value="Save page category" />
    </form>
</section>