<style media="screen" type="text/css">
img.feature {
    margin: 0px;
    background-color: #fff;
    padding: 2px;
    border: 1px solid #ccc;
    -moz-border-radius: 4px;
    -webkit-border-radius: 4px;
    -webkit-box-shadow: 0px 5px 8px 0px #4a4a4a;
    -moz-box-shadow: 0px 5px 8px 0px #4a4a4a;
    box-shadow: 0px 5px 8px 0px #4a4a4a;
    position: relative;
    left: -4px;
    top: -7px;
}
</style>

<div id="news-updates">
    {foreach from=$articles item=article}
    <div class="news-article first-child">
        <h3 align="center">{$article.headline}</h3>
        {$article.content}
    </div>
    {/foreach}
    
    {$external_news_string}
    
    {if $show_external_more}
    <div class="blog-paging">
        <a class="ui-button button1 button1-next float-right"
            href="{$external_more_url}"><span><span>Mehr News</span></span></a>
        <span class="clear"> </span>
    </div>
    {/if}
    <span class="clear"> <!-- --></span>
</div>
<!-- /news-updates -->
{* $pagination *}
