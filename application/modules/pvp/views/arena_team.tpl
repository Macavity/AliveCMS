<style type="text/css">
    /* overrides */
    #content .content-top { background: url("http://portal.wow-alive.de/templates/Shattered-World/images/pvp/arena-bg.jpg") no-repeat; }
    .profile-sidebar-inner { background: none; }
    .profile-section { padding: 0; }

        /* layout */
    .summary { padding-top: 224px; }
    .summary-stats { padding-bottom: 40px; }
    .summary-roster .category { padding: 0 15px; }
    .summary-roster .ui-dropdown { float: right; margin-right: 15px; width: 115px; }
</style>
<script type="text/javascript" src="/application/js/modules/arena_flags.js"></script>

<div class="profile-wrapper profile-wrapper-{$arenaTeam.factionCss}">

    <div class="profile-sidebar-anchor">
        <div class="profile-sidebar-outer">
            <div class="profile-sidebar-inner">
                <div class="profile-sidebar-contents">
    
                    <div class="profile-info-anchor">
                        <div class="arenateam-flag">
                            <canvas id="arenateam-flag" width="240" height="240" style="display: inline; ">
                                <div class="arenateam-flag-default"></div>
                            </canvas>
                        </div>
    
                        <div class="profile-info profile-arenateam-info">
                            <div class="name">
                                <a href="{site_url("pvp", "arena-team", $shownRealmName, $shownArenaSizeLabel, $arenaTeam.name)}">{$arenaTeam.name}</a>
                            </div>
    
                            <div class="under-name">
                                <span class="teamsize">{$shownArenaSizeLabel}</span>
                                <span>{$arenaTeam.factionLabel}</span>
                                Arenateam<span class="comma">,</span>
                                <span class="realm tip" id="profile-info-realm">
                                    <a href="{site_url("pvp","arena-list", $shownRealmName, $shownArenaSizeLabel)}" class="realm tip" id="profile-info-realm">Norgannon</a>
                                </span>
                            </div>
    
                            <div class="rank">
                                {if $arenaTeam.rank > 0}
                                    <h3>
                                        <a href="{site_url("pvp","arena-list", $shownRealmName, $shownArenaSizeLabel)}#rank-{$arenaTeam.rank}">
                                            Rang #{$arenaTeam.rank}
                                        </a>
                                        {if $arenaTeam.lastweek_rank == $arenaTeam.rank}
                                            <span id="rank-tooltip-0" style="display: none">
                                                Aktuelle Platzierung in der Ladder
                                            </span>
                                            <span class="arrow-new" data-tooltip="#rank-tooltip-0"></span>
                                        {elseif $arenaTeam.lastweek_rank == 0}
                                            <span id="rank-tooltip-0" style="display: none">
                                                Neuplatzierung in der Ladder
                                            </span>
                                            <span class="arrow-up" data-tooltip="#rank-tooltip-0"></span>
                                        {elseif $arenaTeam.rank > $arenaTeam.lastweek_rank}
                                            <span id="rank-tooltip-0" style="display: none">
                                                Letzter Rang: <strong>{$arenaTeam.lastweek_rank}</strong><br />
                                                Abgestiegen um {$arenaTeam.rank-$arenaTeam.lastweek_rank} Ränge
                                            </span>
                                            <span class="arrow-down" data-tooltip="#rank-tooltip-0"></span>
                                        {elseif $arenaTeam.rank < $arenaTeam.lastweek_rank}
                                            <span id="rank-tooltip-0" style="display: none">
                                                Letzter Rang: <strong>{$arenaTeam.lastweek_rank}</strong><br />
                                                Aufgestiegen um {$arenaTeam.lastweek_rank-$arenaTeam.rank} Ränge
                                            </span>
                                            <span class="arrow-up" data-tooltip="#rank-tooltip-0"></span>
                                        {/if}
                                    </h3>
                                    Wertung der letzten Woche:
                                    {if $arenaTeam.lastweek_rank == 0}
                                        <span class="unranked">--</span>
                                    {else}
                                        <span class="value">#{$arenaTeam.lastweek_rank}</span>
                                    {/if}
                                {/if}
                            </div>
                        </div>
                    </div> <!-- /profile-info-anchor -->
    
                    <ul class="profile-sidebar-menu" id="profile-sidebar-menu">
                        <li>
                            <a href="{site_url("pvp","arena-list", $shownRealmName, $shownArenaSizeLabel)}" class="back-to" rel="np">
                                <span class="arrow"><span class="icon">Rangliste</span></span>
                            </a>
                        </li>
                        <li class="active">
                            <a href="{site_url("pvp", "arena-team", $shownRealmName, $shownArenaSizeLabel, $arenaTeam.name)}" rel="np">
                                <span class="arrow"><span class="icon">Übersicht</span></span>
                            </a>
                        </li>
                    </ul>
                </div> <!-- /profile-sidebar-contents -->
            </div>
        </div>
    </div>
    
    <div class="profile-contents">
        <div class="summary">
            <div class="profile-section">
                <div class="summary-stats">
    
                    <div class="arenateam-stats table">
                        <table>
                            <thead>
                                <tr>
                                    <th class="align-left">	</th>
                                    <th width="23%" class="align-center">Spiele</th>
                                    <th width="23%" class="align-center">Siege - Niederlagen</th>
                                    <th width="23%" class="align-center">Teamwertung</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="row2">
                                    <td class="align-left">
                                        <strong class="week">Diese Woche</strong>
                                    </td>
                                    <td class="align-center">{$arenaTeam.weekGames}</td>
                                    <td class="align-center arenateam-gameswonlost">
                                        <span class="win">{$arenaTeam.weekWins}</span> – <span class="loss">{$arenaTeam.weekLosses}</span>
                                        <span class="arenateam-percent">({$arenaTeam.weekPercentage}%)</span>
                                    </td>
                                    <td class="align-center">
                                        <span class="arenateam-rating">{$arenaTeam.rating}</span>
                                    </td>
                                </tr>
                                <tr class="row1">
                                    <td class="align-left">
                                        <strong class="season">Saison</strong>
                                    </td>
                                    <td class="align-center">{$arenaTeam.seasonGames}</td>
                                    <td class="align-center arenateam-gameswonlost">
                                        <span class="win">{$arenaTeam.seasonWins}</span> – <span class="loss">{$arenaTeam.seasonLosses}</span>
                                        <span class="arenateam-percent">({$arenaTeam.seasonPercentage}%)</span>
                                    </td>
                                    <td class="align-center">
                                        <span class="arenateam-rating">{$arenaTeam.rating}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
    
                <div class="summary-roster">
                    <h3 class="category ">Mitglieder - Nach Woche</h3>
    
                    <div class="arenateam-roster table" id="arena-roster">
                        <table>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th class="align-center weekly">Gespielt</th>
                                    <th class="align-center weekly">Siege - Niederlagen</th>
                                    <th class="align-center">Wertung</th>
                                </tr>
                            </thead>
                            <tbody>
                            {foreach $arenaTeam.members as $member}
                                <tr class="{cycle values='row1,row2'}" style="display: table-row; ">
                                    <td data-raw="{$member.name}" style="width: 40%">
                                    {if false}
                                        <a href="/wow/de/character/zenedar/Blobba/talent/" rel="np">
                                            <span class="character-talents">
                                                <span class="icon">
                                                    <span class="icon-frame frame-12 ">
                                                        <img src="http://eu.media.blizzard.com/wow/icons/18/inv_misc_questionmark.jpg" alt="" width="12" height="12" />
                                                    </span>
                                                </span>
                                                <span class="points">0<ins>/</ins>0<ins>/</ins>0</span>
                                                <span class="clear"><!-- --></span>
                                            </span>
                                        </a>
                                    {/if}
                                        <a href="{site_url('character', $shownRealmName, $member.name)}" class="color-c{$member.class}" rel="allow">
                                            {icon_race($member.race, $member.gender)}
                                            {icon_class($member.class, false)}
                                            {$member.name}
                                            {if $member.guid == $arenaTeam.captainGuid}<span class="leader" data-tooltip="Teamkapitän"></span>{/if}
                                        </a>
                                    </td>
                                    <td class="align-center weekly">
                                        {$member.weekGames} <span class="arenateam-percent">({$member.weekAttendance}%)</span>
                                    </td>
                                    <td class="align-center weekly arenateam-gameswonlost" data-raw="{$member.weekWins}">
                                        <span class="win">{$member.weekWins}</span> –
                                        <span class="loss">{$member.weekLosses}</span>
                                        <span class="arenateam-percent">({$member.weekPercentage}%)</span>
                                    </td>
                                    <td class="align-center"><span class="arenateam-rating">{$member.personalRating}</span></td>
                                </tr>
                            {/foreach}
                            </tbody>
                        </table>
                    </div>
                    <br /><br />
    
                    <h3 class="category ">Mitglieder - Nach Season</h3>
    
                    <div class="arenateam-roster table" id="arena-roster">
                        <table>
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th class="align-center season">Saison gespielt</th>
                                <th class="align-center season">Saison: Siege - Niederlagen</th>
                                <th class="align-center">Wertung</th>
                            </tr>
                            </thead>
                            <tbody>
                            {foreach $arenaTeam.members as $member}
                                <tr class="{cycle values='row1,row2'}" style="display: table-row; ">
                                    <td data-raw="{$member.name}" style="width: 40%">
                                        <a href="/character/Norgannon/{$member.name}/" class="color-c{$member.class}" rel="allow">
                                            {icon_race($member.race, $member.gender)}
                                            {icon_class($member.class, false)}
                                            {$member.name}
                                            {if $member.guid == $arenaTeam.captainGuid}
                                                <span class="leader" data-tooltip="Teamkapitän"></span>
                                            {/if}
                                        </a>
                                    </td>
                                    <td class="align-center season" style="display: table-cell; ">
                                        {$member.seasonGames} <span class="arenateam-percent">({$member.seasonAttendance}%)</span>
                                    </td>
                                    <td class="align-center season arenateam-gameswonlost" data-raw="{$member.seasonWins}" style="display: table-cell; ">
                                        <span class="win">{$member.seasonWins}</span> –
                                        <span class="loss">{$member.seasonLosses}</span>
                                        <span class="arenateam-percent">({$member.seasonPercentage}%)</span>
                                    </td>
                                    <td class="align-center"><span class="arenateam-rating">{$member.personalRating}</span></td>
                                </tr>
                            {/foreach}
                            </tbody>
                        </table>
                    </div>
                </div>
    
            </div>
        </div>
    </div>
    <span class="clear"><!-- --></span>
</div>
<script type="text/javascript" language="javascript">
    require([
        'static',
        'modules/Core'
    ],
            function (static, Core) {
                $(document).ready(function() {
                    var flagConfig = {
                        'bg': [ 2, '{$arenaTeam.backgroundColor}' ],
                        'border': [ 22, '{$arenaTeam.borderColor}' ],
                        'emblem': [ {$arenaTeam.emblemStyle}, '{$arenaTeam.emblemColor}' ]
                    };
                    var flag = new ArenaFlag('arenateam-flag', flagConfig, false, Core);
                });
            });


</script>