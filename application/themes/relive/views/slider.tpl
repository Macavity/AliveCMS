<div id="carousel-slider" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
        {foreach $slider as $image}
            <li data-target="#carousel-example-generic" data-slide-to="{$image@index}" {if $image@index == 0}class="active"{/if}></li>
        {/foreach}
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner">
        {foreach $slider as $image}
            <div {if $image@index == 0}class="item active"{else}class="item"{/if}>
                {if $image.link}<a href="{$image.link}">{/if}
                    <img src="{$image.image}" alt="">
                    <div class="carousel-caption">
                        <h3>{$image.title}</h3>
                        {$image.text}
                    </div>
                {if $image.link}</a>{/if}
            </div>
        {/foreach}
    </div>

    <!-- Controls -->
    <!--<a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left"></span>
    </a>
    <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right"></span>
    </a>-->
</div>