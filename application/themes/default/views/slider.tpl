<section id="slider_bg" {if !$show_slider}style="display:none;"{/if}>
    <div id="slider">
    {foreach from=$slider item=image}
        <a href="{$image.link}"><img src="{$image.image}" title="{$image.text}"/></a>
    {/foreach}
    </div>
</section>