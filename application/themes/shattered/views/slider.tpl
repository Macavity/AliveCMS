{if $show_slider}                    
<script type="text/javascript" src="http://forum.wow-alive.de/static-wow/local-common/js/slideshow.js"></script>

<div id="slideshow">
    <div class="container">
    
        {foreach from=$slider item=image name=slider}
        <div class="slide" id="slide-{$smarty.foreach.slider.index}" style="background-image: url('{$image.image}'); {if !$smarty.foreach.slider.first}display: none;{/if}"><!-- --></div>
        {/foreach}
     </div>
        
     <div class="paging">
        {foreach from=$slider item=image name=slider}
            <a href="javascript:;" id="paging-{$smarty.foreach.slider.index}" onclick="Slideshow.jump({$smarty.foreach.slider.index}, this);" onmouseover="Slideshow.preview({$smarty.foreach.slider.index});" class="current"></a>
        {/foreach}
     </div>
        
     <div class="caption">
        <h3><a href="#" class="link">{$slider[0].title}</a></h3>
        {$slider[0].text}
     </div>
        
    <div class="preview"></div>
    <div class="mask"></div>
    
</div>

<script type="text/javascript">
    $(function() {
        Slideshow.initialize('#slideshow', [
        {foreach from=$slider item=slide name=slideshow}
            {
                image: "{$slide.image}",
                desc: "{$slide.text}",
                title: "{$slide.title}",
                url: "{$slide.link}",
                id: "slide-{$smarty.foreach.slideshow.index}"
            }{if !$smarty.foreach.slideshow.last},{/if}
        {/foreach}
        ]);

    });
</script>
{/if}