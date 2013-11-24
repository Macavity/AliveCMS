<div class="pvp pvp-ladder">
  <div class="pvp-right">
    <div class="ladder-title">
      <h3 class="category">Arena Teams {$shownArenaSize}</h3>
    </div>

    <div id="ladders">
      <div class="table-options data-options ">
        <div class="option">
          <ul class="ui-pagination"></ul>
        </div>
        Zeige <strong class="results-start">{$firstTeamNumber}</strong>–<strong class="results-end">{$lastTeamNumber}</strong>
        von <strong class="results-total">{$sumTeams}</strong> Ergebnissen
        <span class="clear"><!-- --></span>
      </div>

      <div class="table ">
        <table>
          <thead>
          <tr>
            <th><span class="sort-tab"><span> # </span></span></th>
            <th><span class="sort-tab"><span> Team </span></span></th>
            <th><span class="sort-tab"><span> Realm </span></span></th>
            <th><span class="sort-tab"><span> Fraktion </span></span></th>
            <th><span class="sort-tab"><span> Siege </span></span></th>
            <th><span class="sort-tab"><span> Niederlagen </span></span></th>
            <th><span class="sort-tab"><span> Wertung </span></span></th>
          </tr>
          </thead>
          <tbody>
          {foreach $arenaTeams as $n => $team}
            <tr class="{cycle values='row1,row2'}" id="rank-{$team.rank}">
              <td class="ranking">
                {$team.rank}
                {if $team.lastweek_rank == $team.rank}
                  <span id="rank-tooltip-{$team.rank}" style="display: none">Aktuelle Platzierung in der Ladder</span>
                  <span class="arrow-new" data-tooltip="#rank-tooltip-{$team.rank}"></span>
                {elseif $team.lastweek_rank == 0}
                  <span id="rank-tooltip-{$team.rank}" style="display: none">Neuplatzierung in der Ladder</span>
                  <span class="arrow-up" data-tooltip="#rank-tooltip-{$team.rank}"></span>
                {elseif $team.rank < $team.lastweek_rank}
                  <span id="rank-tooltip-{$team.rank}" style="display: none">
                    Letzter Rang: <strong>{$team.lastweek_rank}</strong><br />
                    Aufgestiegen um {$team.lastweek_rank-$team.rank} Ränge
                  </span>
                  <span class="arrow-up" data-tooltip="#rank-tooltip-{$team.rank}"></span>
                {elseif $team.rank > $team.lastweek_rank}
                  <span id="rank-tooltip-{$team.rank}" style="display: none">
                    Letzter Rang: <strong>{$team.lastweek_rank}</strong><br />
                    Abgestiegen um {$team.rank-$team.lastweek_rank} Ränge
                  </span>
                  <span class="arrow-down" data-tooltip="#rank-tooltip-{$team.rank}"></span>
                {/if}
              </td>
              <td>
                <div class="player-icons">
                  {foreach $team.members as $member}
                    <a href="{site_url(array('character', $shownRealmName, $member.name))}">
                      {icon_class($member.class, false)}
                    </a>
                  {/foreach}
                </div>
                <a href="{site_url(array('pvp', 'arena-team', $shownRealmName, $shownArenaSizeLabel, $team.name))}">{$team.name}</a>
              </td>
              <td>{$shownRealmName}</td>
              <td class="align-center">
                {icon_faction($team.faction)}
              </td>
              <td class="align-center"><span class="win">{$team.seasonWins}</span></td>
              <td class="align-center"><span class="loss">{($team.seasonGames-$team.seasonWins)}</span></td>
              <td class="align-center"><span class="rating">{$team.rating}</span></td>
            </tr>
          {/foreach}
          </tbody>
        </table>
      </div>

      <div class="table-options data-options ">
        <div class="option">
          <ul class="ui-pagination"></ul>
        </div>
        Zeige <strong class="results-start">{$firstTeamNumber}</strong>–<strong class="results-end">{$lastTeamNumber}</strong>
        von <strong class="results-total">{$sumTeams}</strong> Ergebnissen
        <span class="clear"><!-- --></span>
      </div>
    </div> <!-- /.ladders -->
  </div>

  <div class="pvp-left">
    {$pvpSidebar}
  </div>

  <span class="clear"><!-- --></span>
</div>