{if $show_slider}                    
<div id="slideshow">
    <div class="container">
      {foreach from=$slider item=slide name=slider}
        <div class="slide" id="slide-{$smarty.foreach.slider.index}" style="background-image: url('{$slide.image}'); {if !$smarty.foreach.slider.first}display: none;{/if}" data-image="{$slide.image}" data-desc="{$slide.text}" data-title="{$slide.title}" data-url="{$slide.link}">
        </div>
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
{/if}