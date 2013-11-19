<div class="top-banner">
    <div class="section-title">
        <span>Changelog</span>
        <p>Hier findest du die aktuellen und früher behobenen Bugs aufgelistet.</p>
    </div>
    <span class="clear"><!-- --></span>
</div>

<div class="bg-body">
    <div class="body-wrapper">
        <div class="contents-wrapper">
            <div class="left-col">
                <div class="services-content">
                    <p>Hier seht ihr alle gelösten Bug Reports und andere Fehler die wir korrigiert haben ohne dass es einen Bug Report dazu gab,
                        um zu sehen ob ein bestimmter Fix bereits live ist müsst ihr auf die Report-Seite gehen und das Änderungsdatum vergleichen mit der aktuellen Serverlaufzeit auf der Startseite.</p>

                    <div class="related">
                        <div class="tabs">
                            <ul id="tabs">
                                <?
			$first = true;
			foreach($years as $year => $rows){ ?>
                                <li><a href="/server/roadmap/#<?=$year?>" id="tab-<?=$year?>" class="<? if($first){ echo 'tab-active';}?>"><span><span> <?=$year?> </span></span></a></li>
                                <?
				$first = false;
			} ?>
                            </ul>
                            <span class="clear"><!-- --></span>
                        </div>
                        <?
	$first = true;
	foreach($years as $year => $weeks){ ?>
                        <div id="tab-content-<?=$year?>" class="tab-content" style="<? if($first){ echo 'display:block';} else { echo 'display:none';}?>">
                            <? foreach($weeks as $week => $rows){ ?>
                            <h3 class="sub-title"><span>KW #<?=$week?></span></h3>
                            <ul>
                                <? foreach($rows as $row){ ?>
                                <li><?=$row["text"]?></li>
                                <? } ?>
                            </ul>
                            <span class="clear"><!-- --></span>
                            <? } ?>
                        </div>
                        <span class="clear"><!-- --></span>
                        <?
		$first = false;
	} ?>
                    </div>
                    <span class="clear"><!-- --></span>

                </div> <!-- /services-content -->
                <span class="clear"><!-- --></span>
            </div>
            <div class="right-col">
                <? echo $server_sidebar; ?>
                <span class="clear"><!-- --></span>
            </div>
            <span class="clear"><!-- --></span>
        </div>
        <span class="clear"><!-- --></span>
    </div>
</div>
<span class="clear"><!-- --></span>

<script type="text/javascript">
    <? foreach($years as $year => $rows){ ?>
        $("#tab-<?=$year?>").click(function(){
            $(".tab-content").hide();
            $("#tab-content-<?=$year?>").show();
            $("#tabs li a").removeClass("tab-active");
            $("#tab-<?=$year?>").addClass("tab-active");
        });
    <? } ?>
</script>