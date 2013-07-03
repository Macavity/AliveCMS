{TinyMCE()}
<section class="box big">
	<h2>Edit article</h2>

	<form onSubmit="News.send({$article.id}); return false">
		<label for="headline">Titel</label>
		<input type="text" id="headline" value="{$article.headline}"/>
		
		<label for="type">Art</label>
		<select id="newstype" name="newstype">
            <option>Bitte wählen</option>
            <option value="news"{if $article.page == 'news'}selected="selected"{/if}>News</option>
            <option value="article"{if $article.page == 'article'}selected="selected"{/if}>Artikel</option>
        </select>

	    <label for="news_content">
            Content
        </label>
	</form>
    	<div style="padding:10px;">
			<textarea name="news_content" class="tinymce" id="news_content" cols="30" rows="10">{$article.content}</textarea>
		</div>
	<form onSubmit="News.send({$article.id}); return false">
		<label>Article settings</label>

		<input type="checkbox" id="avatar" {if $article.avatar}checked="yes"{/if} value="1"/>
		<label for="avatar" class="inline_label">Show your avatar</label>

		<input type="checkbox" id="comments" {if $article.comments != -1}checked="yes"{/if} value="1"/>
		<label for="comments" class="inline_label">Allow comments</label>

		<input type="submit" value="Save article" />
	</form>
</section>