<div class="content-header">
    <h2 class="header ">PvP: {$shownRealmName}</h2>
    <span class="clear"><!-- --></span>
</div>

<div class="pvp pvp-ladder">
  <div class="pvp-right">
      <div class="top-title">
          <h3 class="category ">{$shownArenaSizeLabel} Arena Teams</h3>
          <span class="clear"><!-- --></span>
      </div>

    <div id="ladders">
      <div class="table-options data-options ">
        <div class="option">
          <ul class="ui-pagination"></ul>
        </div>
        Zeige <strong class="results-start">{$arenaTeamFirst}</strong>–<strong class="results-end">{$arenaTeamLast}</strong>
        von <strong class="results-total">{$arenaTeamCount}</strong> Ergebnissen
        <span class="clear"><!-- --></span>
      </div>

      <div class="table">
        <table>
          <thead>
          <tr>
            <th><a class="sort-link numeric"><span class="arrow">#</span></a></th>
            <th><a class="sort-link"><span class="arrow"> Team </span></a></th>
            <th><a class="sort-link"><span class="arrow"> Realm </span></a></th>
            <th><a class="sort-link"><span class="arrow"> Fraktion </span></a></th>
            <th><a class="sort-link numeric"><span class="arrow"> Siege </span></a></th>
            <th><a class="sort-link numeric"><span class="arrow"> Niederlagen </span></a></th>
            <th><a class="sort-link numeric"><span class="arrow"> Wertung </span></a></th>
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
          Zeige <strong class="results-start">{$arenaTeamFirst}</strong>–<strong class="results-end">{$arenaTeamLast}</strong>
          von <strong class="results-total">{$arenaTeamCount}</strong> Ergebnissen
          <span class="clear"><!-- --></span>
      </div>
    </div> <!-- /.ladders -->
  </div>

  <div class="pvp-left">
    {$pvpSidebar}
  </div>

  <span class="clear"><!-- --></span>
</div>
<script type="text/javascript" language="javascript">
    require([
        'static',
        'modules/wiki',
        'modules/wiki_related',
        'modules/table',
        'modules/filter',
        'modules/zone'
    ],
            function (static, Wiki, WikiRelated, Table, Filter, Zone) {

                $(function () {
                    debug.debug("/pvp/arena-list");

                    new Table($("#ladders"), {
                        totalResults: {$arenaTeamCount},
                        paging: true,
                        perPage: {$shownPerPage},
                        column: 0,
                        method: 'numeric',
                        type: 'asc'

                    });

                });
            });


</script>