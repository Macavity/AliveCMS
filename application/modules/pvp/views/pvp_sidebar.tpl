<ul class="sidebar-menu" id="menu-pvp">
  {foreach $allRealms as $realmId => $realmName}
    <li class="{if $action == "summary" && $realmId == $shownRealmId} item-active{/if}">
      <a href="{site_url("pvp/summary/{$realmName}")}"><span class="arrow">{$realmName}:</span></a>
    </li>
    {foreach $pvpModes as $modeKey => $modeLabel}
      <li class="sidebar-sub{if $action == "arena-list" && $modeKey == $shownArenaSize && $shownRealmId == $realmId} item-active{/if}">
          <!-- {$shownArenaSize} == {$modeKey} -->
        <a href="{$url}pvp/arena-list/{$realmName}/{$modeLabel}">
          <span class="arrow">{$modeLabel}</span>
        </a>
      </li>
    {/foreach}
    <li class="sidebar-sub{if $action == "honor-list" && $shownRealmId == $realmId} item-active{/if}">
      <a href="{$url}pvp/honor/{$realmName}">
        <span class="arrow">Ehrenhafte Kills</span>
      </a>
    </li>
  {/foreach}
</ul>