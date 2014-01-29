<p>Hier seht ihr alle gelösten Bug Reports und andere Fehler die wir korrigiert haben ohne dass es einen Bug Report dazu gab,
    um zu sehen ob ein bestimmter Fix bereits live ist müsst ihr auf die Report-Seite gehen und das Änderungsdatum vergleichen mit der aktuellen Serverlaufzeit auf der Startseite.</p>
<br/>
<div class="related">
    <ul id="tabs" class="nav nav-tabs">
      {foreach $years as $year => $weeks}
        <li class="{if $weeks@first}active{/if}"><a href="#tab-{$year}" class="" data-toggle="tab">{$year}</a></li>
      {/foreach}
    </ul>

    <div class="tab-content">
        {foreach $years as $year => $weeks}
            <div id="tab-{$year}" class="tab-pane fade {if $weeks@first}in active{/if}">
                {foreach $weeks as $week => $rows}

                    <h3 class="sub-title"><span>Kalenderwoche #{$week}</span></h3>
                    <ul>
                        {foreach $rows as $row}
                            <li><span><a href="/bugtracker/bug/{$row.id}/">Bug {$row.num}</a>&nbsp;{$row.class}</span> - {$row.title} - <span class="{$row.stateCss}">{$row.stateLabel}</span></li>
                        {/foreach}
                    </ul>
                    <span class="clear"><!-- --></span>
                {/foreach}
            </div>
            <span class="clear"><!-- --></span>
        {/foreach}
    </div>


</div>
<span class="clear"><!-- --></span>

<script type="text/javascript">
  {foreach $years as $year => $weeks}
        $("#tab-"+{$year}).click(function(){
            $(".tab-content").hide();
            $("#tab-content-"+{$year}).show();
            $("#tabs li a").removeClass("tab-active");
            $("#tab-"+{$year}).addClass("tab-active");
        });
  {/foreach}
</script>