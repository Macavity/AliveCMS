<ul class="sidebar-menu" id="menu-pvp">
  {foreach $allRealms as $realmId => $realmName}
    <li class="{if $action == "summary" && $realmId == $shownRealmId} item-active{/if}">
      <a href="{site_url("pvp/summary/{$realmName}")}"><span class="arrow">{$realmName}:</span></a>
    </li>
    {foreach $pvpModes as $mode}
      <li class="sidebar-sub">
        <a href="{$url}pvp/arena-list/{$realmName}/{$mode}" class="{if $action == "arena-list" && $mode == $shownArenaSize && $shownRealmId == $realmId}item-active{/if}">
          <span class="arrow">{$mode}</span>
        </a>
      </li>
    {/foreach}
    <li class="sidebar-sub">
      <a href="{$url}pvp/honor/{$realmName}" class="{if $action == "honor" && $shownRealmId == $realmId}item-active{/if}">
        <span class="arrow">Ehrenhafte Kills</span>
      </a>
    </li>
  {/foreach}
</ul>