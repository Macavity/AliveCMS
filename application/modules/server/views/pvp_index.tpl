<div class="content-header">
  <h2 class="header ">Spieler gegen Spieler</h2>
  <span class="clear"><!-- --></span>
</div>

<div class="pvp pvp-summary">
  <div class="pvp-right">
    <div class="top-title">
      <h3 class="category ">Top Arenateams</h3>
      <span class="clear"><!-- --></span>
    </div>

    <div class="top-teams">
      {foreach from=$pvpModes item=mode}
        <div class="column top-{$mode}{if $mode == "2v2"}first-child{/if}">
          <h2><a href="{$url}server/pvp/arena/{$mode}/{$activeRealmId}">{$mode}</a></h2>
          <ul>
            {foreach from=$teams[$mode] key=rank item=teamId}
              <li class="{$arenaTeams[$teamId].css_rank}">
                <span class="ranking">{$rank}</span>
                <div class="name">
                  <a href="{$url}server/arena/{$mode}/{$activeRealmId}/{$arenaTeams[$teamId].name}">{$arenaTeams[$teamId].name}</a>
                </div>
                <div class="rating-realm">
                  <span class="rating">{$arenaTeams[$teamId].rating}</span>
                  <span class="realm">{$arenaTeams[$teamId].factionLabel}</span>
                </div>
                <div class="members">
                  {foreach from=$arenaTeams[$teamId].members item=player}
                    <a href="/character/norganon/{$player.name}/">
                      {icon_class($player.class, false)}
                    </a>
                  {/foreach}
                </div>
              </li>
            {/foreach}
          </ul>

          <a href="{$url}server/pvp/arena/{$mode}/{$activeRealmId}" class="all">{$mode}-Ladder einsehen </a>
        </div>
      {/foreach}
      <span class="clear"><!-- --></span>
    </div>


    <div class="popular">
    <div class="column-right">
      <h3 class="category ">Ehrenhafte Kills - Horde</h3>

      <div class="top-bgs">
        <div class="table ">
          <table>
            <tbody>
            {foreach from=$hordeKillers key=rank item=player}
            <tr class="{$player.css}">
              <td class="align-center">{$rank}</td>
              <td>
                <a href="/character/norgannon/{$player.name}" class="color-c{$player.class}">
                  <span class="icon-frame frame-18" data-tooltip="{$player.classLabel}">
                    <img src="{$url}application/themes/shattered/images/icons/18/class_{$player.class}.jpg" height="18" width="18">
                  </span>
                  {$player.name}
                </a>
              </td>
              <td>Norganon</td>
              <td><span class="rating">{$player.totalKills}</span></td>
            </tr>
            {/foreach}
            </tbody>
          </table>
        </div>
        <div class="view-all">
          <a href="{$url}server/pvp/honor/{$activeRealmId}">Zeige volle Liste</a>
        </div>
      </div>
    </div>

    <div class="column-left">
      <h3 class="category ">Ehrenhafte Kills - Allianz</h3>

      <div class="top-bgs">
        <div class="table ">
          <table>
            <tbody>
            {foreach from=$allianceKillers key=rank item=player}
              <tr class="{$player.css}">
                <td class="align-center">{$rank}</td>
                <td>
                  <a href="/character/norgannon/{$player.name}" class="color-c{$player.class}">
                  <span class="icon-frame frame-18" data-tooltip="{$player.classLabel}">
                    <img src="{$url}application/themes/shattered/images/icons/18/class_{$player.class}.jpg" height="18" width="18">
                  </span>
                    {$player.name}
                  </a>
                </td>
                <td>Norganon</td>
                <td><span class="rating">{$player.totalKills}</span></td>
              </tr>
            {/foreach}
            </tbody>
          </table>
        </div>
        <div class="view-all">
          <a href="{$url}server/pvp/honor/{$activeRealmId}">Zeige volle Liste</a>
        </div>
      </div>
    </div>
    <span class="clear"><!-- --></span>

    <div class="column-right">
      <h3 class="category ">Beliebte PvP-Talentverteilungen</h3>
      <div class="class-specs">
        <ul>
          <li>
            <span class="percent">54%</span>
            <span class="class color-c6">
              {icon_class(6)}
              Todesritter </span>
            <span class="tree">
            <span class="icon-frame frame-14 ">
              <img src="/images/icons/18/spell_deathknight_frostpresence.jpg" alt="" width="14" height="14"/>
            </span>
            Frost </span>
            <span class="clear"><!-- -->	</span>
          </li>
          <li>
            <span class="percent">
              59% </span>
            <span class="class color-c11">
              {icon_class(11)}
              Druide </span>
            <span class="tree">
            <span class="icon-frame frame-14 ">
              <img src="http://eu.media.blizzard.com/wow/icons/18/ability_racial_bearform.jpg" alt="" width="14" height="14"/>
            </span>
            Wilder Kampf </span>
            <span class="clear"><!-- -->	</span>
          </li>
          <li>
            <span class="percent">
            69% </span>
            <span class="class color-c3">
            {icon_class(3)}
              Jäger </span>
            <span class="tree">
            <span class="icon-frame frame-14 ">
              <img src="http://eu.media.blizzard.com/wow/icons/18/ability_hunter_focusedaim.jpg" alt="" width="14" height="14"/>
            </span>
            Treffsicherheit </span>
            <span class="clear"><!-- -->	</span>
          </li>
          <li>
            <span class="percent">
            69% </span>
            <span class="class color-c8">
            {icon_class(8)}
              Magier </span>
            <span class="tree">
            <span class="icon-frame frame-14 ">
              <img src="http://eu.media.blizzard.com/wow/icons/18/spell_frost_frostbolt02.jpg" alt="" width="14" height="14"/>
            </span>
            Frost </span>
            <span class="clear"><!-- -->	</span>
          </li>
          <li>
            <span class="percent">
            57% </span>
            <span class="class color-c2">
            {icon_class(2)}
              Paladin </span>
            <span class="tree">
            <span class="icon-frame frame-14 ">
              <img src="http://eu.media.blizzard.com/wow/icons/18/spell_holy_holybolt.jpg" alt="" width="14" height="14"/>
            </span>
            Heilig </span>
            <span class="clear"><!-- -->	</span>
          </li>
          <li>
            <span class="percent">
            71% </span>
            <span class="class color-c5">
            {icon_class(5)}
              Priester </span>
            <span class="tree">
            <span class="icon-frame frame-14 ">
              <img src="http://eu.media.blizzard.com/wow/icons/18/spell_holy_powerwordshield.jpg" alt="" width="14" height="14"/>
            </span>
            Disziplin </span>
            <span class="clear"><!-- -->	</span>
          </li>
          <li>
            <span class="percent">
            85% </span>
            <span class="class color-c4">
            {icon_class(4)}
              Schurke </span>
            <span class="tree">
            <span class="icon-frame frame-14 ">
              <img src="http://eu.media.blizzard.com/wow/icons/18/ability_stealth.jpg" alt="" width="14" height="14"/>
            </span>
            Täuschung </span>
            <span class="clear"><!-- -->	</span>
          </li>
          <li>
            <span class="percent">
            65% </span>
            <span class="class color-c7">
            {icon_class(7)}
              Schamane </span>
            <span class="tree">
            <span class="icon-frame frame-14 ">
              <img src="http://eu.media.blizzard.com/wow/icons/18/spell_nature_magicimmunity.jpg" alt="" width="14" height="14"/>
            </span>
            Wiederherstellung </span>
            <span class="clear"><!-- -->	</span>
          </li>
          <li>
            <span class="percent">
            83% </span>
            <span class="class color-c9">
            {icon_class(9)}
              Hexenmeister </span>
            <span class="tree">
            <span class="icon-frame frame-14 ">
              <img src="http://eu.media.blizzard.com/wow/icons/18/spell_shadow_deathcoil.jpg" alt="" width="14" height="14"/>
            </span>
            Gebrechen </span>
            <span class="clear"><!-- -->	</span>
          </li>
          <li>
            <span class="percent">
            86% </span>
            <span class="class color-c1">
            {icon_class(1)}
              Krieger </span>
            <span class="tree">
            <span class="icon-frame frame-14 ">
              <img src="http://eu.media.blizzard.com/wow/icons/18/ability_warrior_savageblow.jpg" alt="" width="14" height="14"/>
            </span>
            Waffen </span>
            <span class="clear"><!-- -->	</span>
          </li>
        </ul>
      </div>
    </div>

    <div class="column-left">
      <h3 class="category ">Beliebte Teamzusammenstellungen</h3>
      <div class="team-comps">
        <div class="comp comp-2v2 first-child">
          <ul>
            <li>
              {icon_class(2)}
              {icon_class(6)}
              <span class="percent">7%</span>
            </li>
            <li>
              {icon_class(5)}
              {icon_class(8)}
              <span class="percent">6%</span>
            </li>
            <li>
              {icon_class(4)}
              {icon_class(8)}
              <span class="percent">5%</span>
            </li>
            <li>
              {icon_class(1)}
              {icon_class(2)}
              <span class="percent">4%</span>
            </li>
            <li>
              {icon_class(4)}
              {icon_class(5)}
              <span class="percent">4%</span>
            </li>
            <li>
              {icon_class(5)}
              {icon_class(11)}
              <span class="percent">4%</span>
            </li>
            <li>
              {icon_class(5)}
              {icon_class(6)}
              <span class="percent">3%</span>
            </li>
            <li>
              {icon_class(7)}
              {icon_class(9)}
              <span class="percent">3%</span>
            </li>
            <li>
              {icon_class(1)}
              {icon_class(7)}
              <span class="percent">2%</span>
            </li>
            <li>
              {icon_class(2)}
              {icon_class(11)}
              <span class="percent">2%</span>
            </li>
          </ul>
          <h3>2v2</h3>
        </div>

        <div class="comp comp-3v3">
          <ul>
            <li>
              {icon_class(4)}
              {icon_class(5)}
              {icon_class(8)}
              <span class="percent">4%</span>
            </li>
            <li>
              {icon_class(1)}
              {icon_class(2)}
              {icon_class(6)}
              <span class="percent">3%</span>
            </li>
            <li>
              {icon_class(4)}
              {icon_class(7)}
              {icon_class(9)}
              <span class="percent">2%</span>
            </li>
            <li>
              {icon_class(1)}
              {icon_class(2)}
              {icon_class(11)}
              <span class="percent">2%</span>
            </li>
            <li>
              {icon_class(2)}
              {icon_class(4)}
              {icon_class(6)}
              <span class="percent">2%</span>
            </li>
            <li>
              {icon_class(5)}
              {icon_class(8)}
              {icon_class(11)}
              <span class="percent">2%</span>
            </li>
            <li>
              {icon_class(2)}
              {icon_class(5)}
              {icon_class(6)}
              <span class="percent">1%</span>
            </li>
            <li>
              {icon_class(5)}
              {icon_class(7)}
              {icon_class(9)}
              <span class="percent">1%</span>
            </li>
            <li>
              {icon_class(7)}
              {icon_class(8)}
              {icon_class(9)}
              <span class="percent">1%</span>
            </li>
            <li>
              {icon_class(2)}
              {icon_class(3)}
              {icon_class(6)}
              <span class="percent">1%</span>
            </li>
          </ul>
          <h3>3v3</h3>
        </div>


        <div class="comp comp-3v3">
          <ul>
            <li>
              {icon_class(4)}
              {icon_class(5)}
              {icon_class(8)}
              <span class="percent">4%</span>
            </li>
            <li>
              {icon_class(1)}
              {icon_class(2)}
              {icon_class(6)}
              <span class="percent">3%</span>
            </li>
            <li>
              {icon_class(4)}
              {icon_class(7)}
              {icon_class(9)}
              <span class="percent">2%</span>
            </li>
            <li>
              {icon_class(1)}
              {icon_class(2)}
              {icon_class(11)}
              <span class="percent">2%</span>
            </li>
            <li>
              {icon_class(2)}
              {icon_class(4)}
              {icon_class(6)}
              <span class="percent">2%</span>
            </li>
            <li>
              {icon_class(5)}
              {icon_class(8)}
              {icon_class(11)}
              <span class="percent">2%</span>
            </li>
            <li>
              {icon_class(2)}
              {icon_class(5)}
              {icon_class(6)}
              <span class="percent">1%</span>
            </li>
            <li>
              {icon_class(5)}
              {icon_class(7)}
              {icon_class(9)}
              <span class="percent">1%</span>
            </li>
            <li>
              {icon_class(7)}
              {icon_class(8)}
              {icon_class(9)}
              <span class="percent">1%</span>
            </li>
            <li>
              {icon_class(2)}
              {icon_class(3)}
              {icon_class(6)}
              <span class="percent">1%</span>
            </li>
          </ul>
          <h3>3v3</h3>
        </div>

        <span class="clear"><!-- --></span>
      </div>
    </div>
  </div>
  </div> <!-- /.pvp-right -->

  <div class="pvp-left">
  <ul class="dynamic-menu" id="menu-pvp">
    <li class="root-item item-active">
      <a href="{site_url("server/pvp")}"><span class="arrow">Übersicht</span></a>
    </li>
    {foreach from=$pvpModes item=mode}
    <li class="has-submenu">
      <a href="{$url}server/pvp/arena/{$mode}">
        <span class="arrow">{$mode}</span>
      </a>
      <ul class="dynamic-menu" id="menu-pvp-{$mode}">
        {foreach from=$allRealms key=realmId item=realmName}
          <li>
            <a href="{$url}server/pvp/honor/{$realmId}">
              <span class="arrow">{$realmName}</span>
            </a>
          </li>
        {/foreach}
      </ul>
    </li>
    {/foreach}
    <li class="has-submenu">
      <a href="{$url}server/pvp/honor">
        <span class="arrow">Ehrenhafte Kills</span>
      </a>
      <ul class="dynamic-menu" id="menu-pvp-{$mode}">
        {foreach from=$allRealms key=realmId item=realmName}
          <li>
            <a href="{$url}server/pvp/honor/{$realmId}">
              <span class="arrow">{$realmName}</span>
            </a>
          </li>
        {/foreach}
      </ul>
    </li>
  </ul>
</div>
  <span class="clear"><!-- --></span>
</div>