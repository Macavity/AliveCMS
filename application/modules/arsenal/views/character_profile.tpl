<style type="text/css">
/*
    #content .content-top { background: url("http://forum.wow-alive.de/static-wow/images/character/summary/backgrounds/race/{$race}.jpg") left top no-repeat; }
    .profile-wrapper { background-image: url("http://forum.wow-alive.de/static-wow/images/2d/profilemain/race/{$race}-{$gender}.jpg"); }
*/
</style>

<div id="profile-wrapper" class="profile-wrapper profile-wrapper-{$faction}">
    <div class="profile-sidebar-anchor">
        <div class="profile-sidebar-outer">
            <div class="profile-sidebar-inner">
                {$sidebar}
            </div>
        </div>
    </div>
    <div class="profile-contents">
        <div class="summary-top">
            <div class="summary-top-right">
                <ul class="profile-view-options" id="profile-view-options-summary">
                    <li{if $char_mode == "advanced"}class="current"{/if}>
                        <a href="{$charUrl}/advanced" rel="np" class="advanced">Erweitert</a>
                    </li>
                    <li{if $char_mode == "simple"}class="current"{/if}>
                        <a href="{$charUrl}/simple" rel="np" class="simple">Einfach</a>
                    </li>
                </ul>
                <div class="summary-averageilvl">
                    <div class="rest"> durchschnittliche Gegenstandsstufe<br />
                        (<span class="equipped">{$itemLevelEquipped}</span> ausgerüstet) </div>
                    <div id="summary-averageilvl-best" class="best" data-id="averageilvl"> {$itemLevel} </div>
                </div>
            </div>
            <!-- Model Viewer -->
            <div id="model-wrapper">
                <div id="model-view">
                    <div class="no-flash">
                        <a href="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" target="_blank" class="noflash"><img src="/images/getflash.gif" class="p" align="right"/></a>
                        Es ist kein Adobe Flash Player verfügbar oder es ist ein Flash Player installiert, der älter als Version 9 ist. Um alle Features des Arsenals nutzen zu können, wird Adobe Flash Player 9 (oder besser) benötigt. Klickt auf den obenstehenden Button, um die neuste Version des Flash Players zu installieren.
                    </div>
                </div>

                <script type="text/javascript" src="/js/swfobject/swfobject.js"></script>
                <script type="text/javascript">
                    $(document).ready(function() {
                        var lang = "de";
                        var modelserver = "";
                        var params = {
                            menu: "false",
                            quality: "high",
                            scale: "noScale",
                            allowFullscreen: "true",
                            allowScriptAccess: "always",
                            bgcolor:"#E3C96A",
                            wmode:"transparent"
                        };
                        var attributes = { id:"wowhead" };

                        var flashvars = {
                            model: "<?=$char->getCharacterModel()?>",
                            modelType: 16,
                            ha: <?=$char->getHairStyle()?>,
                        hc: <?=$char->getHairColor()?>,
                        sk: <?=$char->getSkinColor()?>,
                        fh: <?=$char->getFacialHair()?>,
                        fa: <?=$char->getFaceStyle()?>,
                        fc: <?=$char->getHairColor()?>,

                        contentPath: "http://static.wowhead.com/modelviewer/",
                                blur: 0,
                                equipList: "<?=$char->getEquipmentListString($equipped_items)?>"
                    };
                    swfobject.embedSWF("http://static.wowhead.com/modelviewer/ModelView.swf", "model-view", "321", "444", "10.0.0", "models/flash/expressInstall.swf", flashvars, params, attributes);
                    });
                </script>
            </div>
            <!-- /Model Viewer -->

            <div class="summary-top-inventory">
                <div id="summary-inventory"
                     class="summary-inventory <?php echo ($char_mode == "advanced")? 'summary-inventory-advanced' : 'summary-inventory-simple';?>">
                <?
				foreach($equipped_items as $item){
					if($item["empty"]){
						?>

                <div data-id="<?=$item["slot"]?>" data-type="<?=$item["inventoryType"]?>" class="slot <?=$item["css"]?>" style=" <?=$item["slot_style"]?>">
                <div class="slot-inner">
                    <div class="slot-contents">
                        <a href="javascript:;" class="empty"> <span class="frame"></span>
                        </a>
                    </div>
                </div>
            </div>
            <?
					}
					else{
						debug($item);
						?>

            <div data-id="<?=$item["slot"]?>" data-type="<?=$item["inventoryType"]?>" class="slot <?=$item["css"]?>" style=" <?=$item["slot_style"]?>">
            <div class="slot-inner">
                <div class="slot-contents">
                    <a href="http://portal.wow-alive.de/item/<?=$item["id"]?>/"
                    class="item" data-item="<?=$item["params"]?>"> <img
                            src="<?=$item["icon"]?>" alt="" /> <span class="frame"></span>
                    </a>
                    {if $char_mode == "advanced"}
                    <div class="details">
                    <span class="name-shadow"><?php echo $item["name"]?></span>
                    <span class="name color-q<?=$item["rarity"]?>">
                    <? if($item["auditLeft"]){?><a href="javascript:;" class="audit-warning"></a><? } ?>
                    <a href="http://portal.wow-alive.de/item/<?=$item["id"]?>/" data-item="<?=$item["params"]?>">
                    <?php echo $item["name"]?></a>
                    <? if($item["auditRight"]){?><a href="javascript:;" class="audit-warning"></a><? } ?>
                    </span>
                    <span class="enchant-shadow"><?php echo $item["enchant"]?></span>
                    <div class="enchant color-q2">
                    <a href="http://portal.wow-alive.de/item/<?=$item["permanentEnchantItemId"]?>"><?=$item["permanentEnchantSpellName"]?></a>
                    </div>
                    <span class="level"><?=$item["level"]?></span>
                    <span class="sockets">
                    <?
                    for($i = 0; $i < 3; $i++) {
										if( (isset($item["socket".$i."Color"]) && $item["socket".$i."Color"] > 0) ||
                    (isset($item["gem".$i."Color"]) && $item["gem".$i."Color"] > 0)) {?>
                    <span class="icon-socket socket-<?=(isset($item["socket".$i."Color"]) ? $item["socket".$i."Color"]: $item["gem".$i."SocketColor"])?>">
                    <?php if(isset($item["gem".$i."Id"]) && $item["gem".$i."Id"] > 0) {?>
                    <a href="http://portal.wow-alive.de/item/<?=$item["gem".$i."Id"]?>" class="gem">
                    <img src="/images/icons/18/<?=$item["gem".$i."Icon"]?>.jpg" alt="" />
                    <span class="frame"></span>
                    </a>
                    <?php }
                    else{
                    ?>
                    <span class="empty"></span>
                    <span class="frame"></span>
                    <?php
										} ?>
                    </span>
                    <?
										}
									}
									?></span>
                </div>
                <? }?>
            </div>
        </div>
    </div>
    <?
					}
				}

				?>
</div>
<script type="text/javascript">
    //<![CDATA[
    $(document).ready(function() {
        var summaryInventory = new Summary.Inventory( { view: "<?=$char_mode?>" }, {
                <?
                foreach($equipped_items as $item){
            if($item["empty"])
                continue;
            echo "\n\t\t".$item["slot"].': { name: "'.$item["name"].'", quality: '.$item["rarity"].', icon: "'.$item["icon_raw"].'" },';
        }
        echo "\n\t\t".'19: { name: "",	quality: 0,	icon: ""}';
        ?>
    });
    });

    //]]>
</script>
</div>
</div>

<?php if($char_mode == "advanced"){ ?>
<div class="summary-middle">
    <div class="summary-middle-inner">
        <div class="summary-middle-right">
            <div class="summary-audit" id="summary-audit">
                <div class="category-right"><span class="tip" id="summary-audit-whatisthis">Was ist die Charakteransicht?</span></div>
                <h3 class="category ">Charakteransicht</h3>

                <div class="profile-box-simple">
                    <ul class="summary-audit-list">
                        <?php
							if(count($missingEnchSlot) > 0){
                        echo '<li data-slots="'.implode(",", array_keys($missingEnchSlot)).'">';
                        echo '<span class="tip"><span class="number">'.count($missingEnchSlot).'</span> ';
								if(count($missingEnchSlot) > 1)
									echo 'Nicht verzauberte Gegenst&auml;nde';
								else
									echo 'Nicht verzauberter Gegenstand';
								echo '</span></li>';
                        }
                        if(count($missingGemSlot) > 0){
                        echo '<li data-slots="'.implode(",", array_keys($missingGemSlot)).'">';
                        echo '<span class="number">'.$missingGemSum.'</span> ';
                        echo (count($missingEnchSlot) > 1) ? 'Leere Sockel in ' : 'Leerer Sockel in ';
                        echo '<span class="tip">'.count($missingGemSlot).' ';
								echo (count($missingEnchSlot) > 1) ? 'Gegenst&auml;nde' : 'Gegenstand';
								echo '</span></li>';
                        }
                        if(count($missingBeltBucket) > 0){
                        echo '<li data-slots="5">Es fehlt <a href="http://portal.wow-alive.de/item/41611" class="color-q3">Ewige G&uuml;rtelschnalle</a></li>';
                        }
                        if((count($missingBeltBucket) + count($missingGemSlot) + count($missingEnchSlot)) == 0){
                        echo 'Dieser Charakter hat die &Uuml;berpr&uuml;fung bestanden.';
                        }

                        ?>

                    </ul>

                    <script type="text/javascript">
                        //<![CDATA[
                        $(document).ready(function() {
                            new Summary.Audit({
                                    <?php
                            if(count($missingEnchSlot) > 0){
                                echo '"unenchantedItems":{';
									echo implode(", ",$missingEnchSlot);
									echo '},';
								}
								?>
								<?php
								if(count($missingGemSlots) > 0){
									echo '"itemsWithEmptySockets":{';
									echo implode(", ",$missingGemSlots);
									echo '},';
								}
								?>
									"foo": true
								});
							});
        					//]]>
					        </script>
						</div>
					</div>
				</div>

				<div class="summary-middle-left">
					<div class="summary-gems">
						<h3 class="category ">Edelsteine</h3>
						<div class="profile-box-simple">
							<div class="summary-gems">
							<ul>
							<?php foreach($gemCounts as $gem_id => $count){ ?>
								<li>
									<span class="value"><?=$count?></span> <span class="times">x</span>
									<span class="icon"> <span class="icon-socket socket-<?=$gemData[$gem_id]["color"]?>">
										<a href="http://portal.wow-alive.de/item/<?=$gem_id?>" class="gem">
											<img src="/images/icons/18/<?=$gemData[$gem_id]["icon"]?>.jpg" alt="" />
											<span class="frame"></span>
										</a> </span> </span>
									<a href="http://portal.wow-alive.de/item/<?=$gem_id?>" class="name color-q<?=$gemData[$gem_id]["quality"]?>"><?=$gemData[$gem_id]["name"]?></a>
									<span class="clear"> <!-- --> </span>
								</li>
							<?php } ?>
							</ul>
							</div>
						</div>
					</div>
					<span class="clear"><!-- --></span>
				</div>
				<span class="clear"><!-- --></span>
			</div>
		</div>
		<?php } ?>
		<div class="summary-bottom">
			<div class="profile-recentactivity">
				<h3 class="category "> Letzte Aktivitäten </h3>
				<div class="profile-box-simple">
					<ul class="activity-feed">
						<? /*<li class="bosskill ">
							<dl>
								<dd> <span class="icon"></span> 3x <a href="/wow/de/zone/the-slave-pens/quagmirran" data-npc="17942">Quagmirran</a> bezwungen (<a href="/wow/de/zone/the-slave-pens/" data-zone="3717">Die Sklavenunterkünfte</a>) </dd>
								<dt>vor 7 Stunden</dt>
							</dl>
						</li>
						<li class="bosskill ">
							<dl>
								<dd> <span class="icon"></span> 3x <a href="/wow/de/zone/the-underbog/the-black-stalker" data-npc="17882">Die Schattenmutter</a> bezwungen (<a href="/wow/de/zone/the-underbog/" data-zone="3716">Der Tiefensumpf</a>) </dd>
								<dt>vor 7 Stunden</dt>
							</dl>
						</li>
						<li>
							<dl>
								<dd> <a href="/wow/de/item/77026" class="color-q4" data-item="e=4440&amp;g0=52258&amp;g1=52258&amp;g2=52211&amp;re=146&amp;set=78833,77024,77026&amp;d=114"> <span class="icon-frame frame-18 " style="background-image: url(&quot;http://eu.media.blizzard.com/wow/icons/18/inv_pants_leather_raidrogue_k_01.jpg&quot;);"> </span> </a> Erhalten <a href="/wow/de/item/77026" class="color-q4" data-item="e=4440&amp;g0=52258&amp;g1=52258&amp;g2=52211&amp;re=146&amp;set=78833,77024,77026&amp;d=114">Netzrüstungbeinschützer des Schwarzfangs</a>. </dd>
								<dt>vor 1 Tag</dt>
							</dl>
						</li>
						<li>
							<dl>
								<dd> <a href="/wow/de/item/77254" class="color-q4" data-item="g0=52212&amp;g1=52212&amp;re=140&amp;s=792768544&amp;d=79"> <span class="icon-frame frame-18 " style="background-image: url(&quot;http://eu.media.blizzard.com/wow/icons/18/inv_boots_leather_raidrogue_k_01.jpg&quot;);"> </span> </a> Erhalten <a href="/wow/de/item/77254" class="color-q4" data-item="g0=52212&amp;g1=52212&amp;re=140&amp;s=792768544&amp;d=79">Blutige Fußpolster des Vernehmers</a>. </dd>
								<dt>vor 1 Tag</dt>
							</dl>
						</li>
						<li>
							<dl>
								<dd> <a href="/wow/de/item/77091" class="color-q4" data-item=""> <span class="icon-frame frame-18 " style="background-image: url(&quot;http://eu.media.blizzard.com/wow/icons/18/inv_misc_necklace14.jpg&quot;);"> </span> </a> Erhalten <a href="/wow/de/item/77091" class="color-q4" data-item="">Gemme der schrecklichen Erinnerungen</a>. </dd>
								<dt>vor 3 Tagen</dt>
							</dl>
						</li> */ ?>
					</ul>
					<!--
					<div class="profile-linktomore"> <a href="<?=$char->GetCharacterLink()?>/feed" rel="np">Frühere Aktivitäten anzeigen</a> </div>
					 -->
					 <span class="clear"><!-- --></span> </div>
			</div>
			<div class="summary-bottom-left">
				<div class="summary-talents" id="summary-talents">
					<ul>
					<? foreach($talent_spec as $n => $spec){?>
						<li class="summary-talents-<?=$n?>">
							<a href="<?=$char->GetCharacterLink()?>/talent/<?=(($n == 0) ? "primary" : "secondary")?>"<?=($spec["active"]) ? 'class="active"' : ''?>>
								<span class="inner">
									<? if($spec["active"]){
										?><span class="checkmark"> </span><?
									}?>
									<span class="icon"><img src="<?=$spec["icon"]?>" alt="" width="36" height="36" /><span class="frame"></span></span>
									<span class="roles"> <span class="icon-<?=$spec["role"]?>"></span> </span>
									<span class="name-build">
										<span class="name"><?=$spec["prim"]?></span>
										<span class="build"><?=$spec["treeOne"]?><ins>/</ins><?=$spec["treeTwo"]?><ins>/</ins><?=$spec["treeThree"]?></span>
									</span>
								</span>
							</a>
						</li>
					<? } ?>
					</ul>
				</div>
				<div class="summary-health-resource">
					<ul>
						<li class="health" id="summary-health" data-id="health"><span class="name">Gesundheit</span><span class="value"><?=$char->GetMaxHealth()?></span></li>
						<? if($char->IsManaUser()){?>
						<li class="resource-0" id="summary-power" data-id="power-0"><span class="name">Mana</span><span class="value"><?=$char->GetMaxMana();?></span></li>
						<? } else if($char->GetClass() == CLASS_WARRIOR){?>
						<li class="resource-1" id="summary-power" data-id="power-1"><span class="name">Wut</span><span class="value"><?=$char->GetMaxRage();?></span></li>
						<? } else if($char->GetClass() == CLASS_ROGUE){?>
						<li class="resource-3" id="summary-power" data-id="power-3"><span class="name">Energie</span><span class="value"><?=$char->GetMaxEnergy();?></span></li>
						<? } else if($char->GetClass() == CLASS_DK){?>
						<li class="resource-6" id="summary-power" data-id="power-6"><span class="name">Runenmacht</span><span class="value"><?=$char->GetMaxEnergy();?></span></li>
						<? } ?>
					</ul>
				</div>

				<div class="summary-stats-profs-bgs">
					<div class="summary-stats" id="summary-stats">
						<div id="summary-stats-advanced" class="summary-stats-advanced">
							<div class="summary-stats-end"></div>
						</div>
						<div id="summary-stats-simple" class="summary-stats-simple" style=" display: none">
							<div class="summary-stats-end"></div>
						</div>
					</div>
					<div class="summary-stats-bottom">
						<div class="summary-battlegrounds">
							<ul>
								<li class="kills"><span class="name">Ehrenhafte Siege</span><span class="value"><?=$char->totalKills?></span> <span class="clear"><!-- --></span> </li>
							</ul>
						</div>
						<div class="summary-professions">
							<ul>
							<? foreach($character_professions as $prof){ ?>
								<li>
									<div class="profile-progress border-3 <? if($prof["value"] >= $prof["max"]){ echo "completed"; }?>">
										<div class="bar border-3 hover" style="width: <?=$prof["percent"]?>%"></div>
										<div class="bar-contents">
											<span class="profession-details">
											<span class="icon">
												<span class="icon-frame frame-12 "> <img src="<?=$prof["icon"]?>" alt="" width="12" height="12" /> </span>
											</span>
											<span class="name"><?=htmlentities($prof["name"])?></span> <span class="value"><?=$prof["max"]?></span>
											</span>
										</div>
									</div>
								</li>
							<? } ?>
							</ul>
						</div>
						<span class="clear"><!-- --></span>
					</div>
				</div>
			</div>
			<span class="clear"><!-- --></span>
			<div id="summary-raid" class="summary-raid">
				<h3 class="category">Schlachtszugsfortschritt</h3>
				<div class="profile-box-full">
					<div class="prestige">
						<!--
						<div>Höchster Schlachtzugstitel: <strong> <a href="<?=$char->GetCharacterLink()?>/achievement#168:15068:a6177" data-achievement="6177"> der Tod des Zerstörers</a> </strong> </div>
						-->
					</div>
				</div>
			</div>
			<span class="clear"><!-- --></span>
			<div class="summary-lastupdate">
			<?
			if($char->cacheRefreshed){ echo "Aktuell"; }
			else{ echo "Letzte Aktualisierung am ".$char->GetCachedDate(); }
			?>
			</div>
		</div>
	</div>
	<span class="clear"><!-- --></span>
</div>
<script type="text/javascript">
//<![CDATA[

	$(function() {
		Profile.url = '<?=$char->GetCharacterLink()?>/summary';
	});

	var MsgProfile = {
		tooltip: {
			feature: {
				notYetAvailable: "Diese Funktion ist derzeit noch nicht verfügbar."
			},
			vault: {
				character: "Diese Sektion ist nur verfügbar, wenn du mit diesem Charakter eingeloggt bist.",
				guild: "Diese Sektion ist nur verfügbar, wenn du mit einem Charakter aus dieser Gilde eingeloggt bist."
			}
		}
	};

//]]>
</script>
<script type="text/javascript">
//<![CDATA[
var MsgSummary = {
	inventory: {
		slots: {
			1: "Kopf",
			2: "Hals",
			3: "Schultern",
			4: "Hemd",
			5: "Brust",
			6: "Gürtel",
			7: "Füße",
			8: "Beine",
			9: "Handgelenke",
			10: "Hände",
			11: "Finger",
			12: "Schmuck",
			15: "Distanzwaffe",
			16: "Rücken",
			19: "Wappenrock",
			21: "Waffenhand",
			22: "Schildhand",
			28: "Relikt",
			empty: "Dieser Platz ist leer."
		}
	}
	,
	audit: {
		whatIsThis: "Diese Übersicht macht Empfehlungen, wie dieser Charakter verbessert werden könnte. Dazu wird Folgendes überprüft:<br /\><br /\>- Leere Glyphensockel<br /\>- Unverbrauchte Talentpunkte<br /\>- Unverzauberte Gegenstände<br /\>- Leere Sockel<br /\>- Nicht optimale Rüstungsteile<br /\>- Fehlende Gürtelschnalle<br /\>- Unbenutzte Berufsboni",
		missing: "Es fehlt {0}",
		enchants: {
			tooltip: "Unverzaubert"
		}
		,
		sockets: {
			singular: "Leerer Sockel",
			plural: "Leere Sockel"
		}
		,
		armor: {
			tooltip: "Keine {0}",
			1: "Stoff",
			2: "Leder",
			3: "Kettenrüstung",
			4: "Platte"
		}
		,
		lowLevel: {
			tooltip: "Niedrigstufig"
		}
		,
		blacksmithing: {
			name: "Schmieden",
			tooltip: "Fehlende Sockel"
		}
		,
		enchanting: {
			name: "Verzauberkunst",
			tooltip: "Unverzaubert"
		}
		,
		engineering: {
			name: "Ingenieurskunst",
			tooltip: "Fehlende Verbesserung"
		}
		,
		inscription: {
			name: "Inschriftenkunde",
			tooltip: "Fehlende Verzauberung"
		}
		,
		leatherworking: {
			name: "Lederverarbeitung",
			tooltip: "Fehlende Verzauberung"
		}
	}
	,
	talents: {
		specTooltip: {
			title: "Talentspezialisierungen",
			primary: "Primär:",
			secondary: "Sekundär:",
			active: "Aktiv"
		}
	}
	,
	stats: {
		toggle: {
			all: "Alle Statistiken anzeigen",
			core: "Nur Hauptstatistiken anzeigen"
		}
		,
		increases: {
			attackPower: "Erhöht Angriffskraft um {0}.",
			critChance: "Erhöht kritische Trefferchance um {0}%.",
			spellCritChance: "Erhöht kritische Zauberchance um {0}%.",
			health: "Erhöht Gesundheit um {0}.",
			mana: "Erhöht Mana um {0}.",
			manaRegen: "Erhöht Manaregeneration um {0} alle 5 Sekunden, solange nicht gezaubert wird.",
			meleeDps: "Erhöht den Schaden mit Nahkampfwaffen um {0} Schaden pro Sekunde.",
			rangedDps: "Erhöht den Schaden mit Fernkampfwaffen um {0} Schaden pro Sekunde.",
			petArmor: "Erhöht die Rüstunf deines Begleiters um {0}.",
			petAttackPower: "Erhöht die Angriffskraft deines Begleiters um {0}.",
			petSpellDamage: "Erhöht des Zauberschaden deines Begleiters um {0}.",
			petAttackPowerSpellDamage: "Erhöht die Angriffskraft deines Begleiters um {0} und dessen Zauberschaden um {1}."
		}
		,
		decreases: {
			damageTaken: "Reduziert erhaltenen körperlichen Schaden um {0}%.",
			enemyRes: "Reduziert gegnerischen Widerstände um {0}.",
			dodgeParry: "Reduziert die Chance, dass eigene Angriffe pariert oder ihnen ausgewichen wird um {0}%."
		}
		,
		noBenefits: "Beinhaltet keine Vorteile für deine Klasse.",
		beforeReturns: "(Bevor der Nutzen sinkt)",
		damage: {
			speed: "Angriffsgeschwindigkeit (Sekunden):",
			damage: "Schaden:",
			dps: "Schaden pro Sekunde:"
		}
		,
		averageItemLevel: {
			title: "Gegenstandsstufe {0}",
			description: "Die durschschnittliche Gegenstandsstufe deiner besten Ausrüstungsgegenstände. Durch das Erhöhen dieses Wertes erhälst du Zugang zu schwierigeren Dungeons über den Dungeonfinder."
		}
		,
		health: {
			title: "Gesundheit {0}",
			description: "Dein maximaler Gesundheitswert. Wenn deine Gesundheit null erreicht, stirbst du."
		}
		,
		mana: {
			title: "Mana {0}",
			description: "Dein maximaler Manawert. Mana erlaubt es dir, Zauber zu wirken."
		}
		,
		rage: {
			title: "Wut {0}",
			description: "Dein maximaler Wutwert. Wut wird mit dem Verwenden von Fähigkeiten verbraucht und wird wiederhergestellt, indem man Feinde angreift oder im Kampf Schaden erleidet."
		}
		,
		focus: {
			title: "Fokus {0}",
			description: "Dein maximaler Fokuswert. Fokus wird durch das Verwenden von Fähigkeiten verbraucht und regeneriert sich automatisch im Verlauf der Zeit."
		}
		,
		energy: {
			title: "Energie {0}",
			description: "Energie wird durch das Verwenden von Fähigkeiten verbraucht und regeneriert sich automatisch im Verlauf der Zeit."
		}
		,
		runic: {
			title: "Runenmacht {0}",
			description: "Dein maximaler Runenmachtwert."
		}
		,
		strength: {
			title: "Stärke {0}"
		}
		,
		agility: {
			title: "Beweglichkeit {0}"
		}
		,
		stamina: {
			title: "Ausdauer {0}"
		}
		,
		intellect: {
			title: "Intelligenz {0}"
		}
		,
		spirit: {
			title: "Willenskraft {0}"
		}
		,
		mastery: {
			title: "Meisterschaft {0}",
			description: "Meisterschaftswertung von {0} erhöht Meisterschaft um {1}.",
			unknown: "Du musst Meisterschaft bei deinem Lehrer erlernen, bevor dieser Wert einen Effekt hat.",
			unspecced: "Du musst eine Talentspezialisierung auswählen, um Meisterschaft zu aktivieren. "
		}
		,
		meleeDps: {
			title: "Schaden pro Sekunde"
		}
		,
		meleeAttackPower: {
			title: "Nahkampfangriffskraft {0}"
		}
		,
		meleeSpeed: {
			title: "Nahkampfangriffsgeschwindigkeit {0}"
		}
		,
		meleeHaste: {
			title: "Nahkampftempowertung {0}%",
			description: "Nahkampftempowertung von {0} erhöht das Nahkampftempo um {1}%.",
			description2: "Erhöht Nahkampfangriffsgeschwindigkeit."
		}
		,
		meleeHit: {
			title: "Nahkampftrefferchance {0}%",
			description: "Nahkampftrefferwertung von {0} erhöht die Nahkampftrefferchance um {1}%."
		}
		,
		meleeCrit: {
			title: "Kritische Nahkampftrefferchance {0}%",
			description: "Kritische Nahkampftrefferwertung {0} erhöht die kritische Nahkampftrefferchance um {1}%.",
			description2: "Es besteht die Chance, dass Nahkampfangriffe Zusatzschaden verursachen."
		}
		,
		expertise: {
			title: "Waffenkunde {0}",
			description: "Waffenkundewert von {0} erhöht die Waffenkunde um {1}."
		}
		,
		rangedDps: {
			title: "Schaden pro Sekunde"
		}
		,
		rangedAttackPower: {
			title: "Fernkampfangriffskraft {0}"
		}
		,
		rangedSpeed: {
			title: "Fernkampfangriffsgeschwindigkeit {0}"
		}
		,
		rangedHaste: {
			title: "Fernkampftempo {0}%",
			description: "Tempowertung von {0} erhöht Tempo um {1}%.",
			description2: "Erhöht die Fernkampfgeschwindigkeit."
		}
		,
		rangedHit: {
			title: "Fernkampftrefferchance {0}%",
			description: "Trefferchancewertung von {0} erhöht Trefferchance um {1}%."
		}
		,
		rangedCrit: {
			title: "Kritische Fernkampfftrefferchance {0}%",
			description: "Kritische Fernkampfftrefferwertung von {0} erhöht Fernkampfftrefferchance um {1}%.",
			description2: "Es besteht die Chance, dass Fernkampfangriffe Zusatzschaden verursachen."
		}
		,
		spellPower: {
			title: "Zaubermacht {0}",
			description: "Erhöht den Schaden und die Heilung von Zaubern."
		}
		,
		spellHaste: {
			title: "Zaubertempo {0}%",
			description: "Zaubertempowertung von {0} erhöht das Zaubertempo um {1}%.",
			description2: "Erhöht die Wirkungsgeschwindigkeit von Zaubern."
		}
		,
		spellHit: {
			title: "Zaubertrefferchance {0}%",
			description: "Zaubertrefferwertung von {0} erhöht die Zaubertrefferchance um {1}%."
		}
		,
		spellCrit: {
			title: "Kritische Zaubertrefferchance {0}%",
			description: "Kritische Zaubertrefferwertung von {0} erhöht kritische Zaubertrefferchance um {1}%.",
			description2: "Es besteht die Chance, dass Zauber zusätzlichen Schaden oder Heilung verursachen."
		}
		,
		spellPenetration: {
			title: "Zauberdurchschlagskraft {0}"
		}
		,
		manaRegen: {
			title: "Manaregeneration",
			description: "{0} Mana alle 5 Sekunden regeneriert, während ihr euch außerhalb eines Kampfes befindet."
		}
		,
		combatRegen: {
			title: "Kampfregeneration",
			description: "{0} Mana alle 5 Sekunden regeneriert, während ihr euch im Kampf befindet."
		}
		,
		armor: {
			title: "Rüstung {0}"
		}
		,
		dodge: {
			title: "Ausweichchance {0}%",
			description: "Ausweichwertung von {0} erhöht die Ausweichchance um {1}%."
		}
		,
		parry: {
			title: "Parierchance {0}%",
			description: "Parierwertung von {0} erhöht die Parierchance um {1}%."
		}
		,
		block: {
			title: "Blockchance {0}%",
			description: "Blockwertung von {0} erhöht Blockchance um {1}%.",
			description2: "Dein Blocken verhindert {0}% des erlittenen Schadens."
		}
		,
		resilience: {
			title: "Abhärtung {0}",
			description: "Gewährt {0}% Schadensreduzierung, die einem durch andere Spieler oder deren Tiere/Diener zugefügt wird."
		}
		,
		arcaneRes: {
			title: "Arkanwiderstand {0}",
			description: "Reduziert erlittenen Arkanschaden um durchnittlich {0}%."
		}
		,
		fireRes: {
			title: "Feuerwiderstand {0}",
			description: "Reduziert erlittenen Feuerschaden um durchnittlich {0}%."
		}
		,
		frostRes: {
			title: "Frostwiderstand {0}",
			description: "Reduziert erlittenen Frostschaden um durchnittlich {0}%."
		}
		,
		natureRes: {
			title: "Naturwiderstand {0}",
			description: "Reduziert erlittenen Naturschaden um durchnittlich {0}%."
		}
		,
		shadowRes: {
			title: "Schattenwiderstand {0}",
			description: "Reduziert erlittenen Schattenschaden um durchnittlich {0}%."
		}
	}
	,
	recentActivity: {
		subscribe: "Diesen Feed abonnieren"
	}
	,
	raid: {
		tooltip: {
			lfr: "(LFR)",
			normal: "(Normal)",
			heroic: "(Heroisch)",
			complete: "{0}% abgeschlossen ({1}/{2})",
			optional: "(optional)"
		}
	}
	,
	links: {
		tools: "Einstellungen",
		saveImage: "Charakterbild speichern",
		saveimageTitle: "Export your character image for use with the World of Warcraft Rewards Visa credit card."
	}
};
//]]>
</script>
<script type="text/javascript" src="/<?=$currtmp?>/js/profile.js?v1"></script>
<script type="text/javascript" src="/<?=$currtmp?>/js/summary.js?v1"></script>