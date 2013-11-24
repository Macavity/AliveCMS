<div class="content-header">
    <h2 class="header ">PvP: {$shownRealmName}</h2>
    <span class="clear"><!-- --></span>
</div>

<div class="pvp pvp-summary">
    <div class="pvp-right">
        <div class="top-title">
            <h3 class="category ">Ehrenhafte Tötungen</h3>
            <span class="clear"><!-- --></span>
        </div>

        <div class="popular">
            <div class="column-right">
                <h3 class="category ">Horde</h3>

                <div class="top-bgs">
                    <div class="table ">
                        <table>
                            <tbody>
                            {foreach $hordeKillers as $rank => $player}
                                <tr class="{cycle values='row1,row2'}">
                                    <td class="align-center">{$rank}</td>
                                    <td>
                                        <a href="{site_url(array('character', $shownRealmName, $player.name))}" class="color-c{$player.class}">
                                        {icon_class($player.class)} {$player.name}
                                        </a>
                                    </td>
                                    <td>{$shownRealmName}</td>
                                    <td><span class="rating">{$player.totalKills}</span></td>
                                </tr>
                            {/foreach}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="column-left">
                <h3 class="category ">Allianz</h3>

                <div class="top-bgs">
                    <div class="table ">
                        <table>
                            <tbody>
                            {foreach $allianceKillers as $rank => $player}
                                <tr class="{cycle values='row1,row2'}">
                                    <td class="align-center">{$rank}</td>
                                    <td>
                                        <a href="{site_url(array('character', $shownRealmName, $player.name))}" class="color-c{$player.class}">
                                        {icon_class($player.class)} {$player.name}
                                        </a>
                                    </td>
                                    <td>{$shownRealmName}</td>
                                    <td><span class="rating">{$player.totalKills}</span></td>
                                </tr>
                            {/foreach}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <span class="clear"><!-- --></span>


        </div>
    </div> <!-- /.pvp-right -->

    <div class="pvp-left">
        {$pvpSidebar}
    </div>
    <span class="clear"><!-- --></span>
</div>
	