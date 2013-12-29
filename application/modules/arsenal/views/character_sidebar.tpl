<div class="profile-sidebar-contents">
    <div class="profile-info-anchor">
        <div class="profile-info">
            <div class="name"><a href="{$charUrl}/" rel="np">{$name}</a></div>
            <div class="title-guild">
                <div class="title">{$char->GetTitle()} </div>
                <div class="guild"> <a href="{$char->GetGuildLink()}">{$char->GetGuildName()}</a> </div>
            </div>
            <span class="clear"><!-- --></span>
            <div class="under-name {$char->GetCssClass()}">
                <span class="level"><strong>{$char->GetRaw("level")}</strong></span>,
                <span class="race">{$char->GetRace()}</span>,
                <a id="profile-info-spec" href="{$charUrl}/talent/" class="spec tip">Kampf</a>, <span class="class">{$char->GetClass()}</span><span class="comma">,</span> <span class="realm tip" id="profile-info-realm" data-battlegroup="Norgannon"> Norgannon </span> </div>
            <div class="achievements"><a href="{$charUrl}/achievement">{$char->GetAchievementPoints()}</a></div>
        </div>
    </div>
    <ul class="profile-sidebar-menu" id="profile-sidebar-menu">
        <li class=" active"> <a href="{$charUrl}/" class="" rel="np"> <span class="arrow"><span class="icon"> Übersicht </span></span> </a> </li>
        <li class=" disabled"> <a href="{$charUrl}/talent/" class="" rel="np"> <span class="arrow"><span class="icon"> Talente &amp; Glyphen </span></span> </a> </li>
        <li class=" disabled"> <a href="javascript:;" class=" has-submenu vault" rel="np"> <span class="arrow"><span class="icon"> Auktionen </span></span> </a> </li>
        <li class=" disabled"> <a href="javascript:;" class=" vault" rel="np"> <span class="arrow"><span class="icon"> Ereignisse </span></span> </a> </li>
        <li class=" disabled"> <a href="{$charUrl}/achievement" class=" has-submenu" rel="np"> <span class="arrow"><span class="icon"> Erfolge </span></span> </a> </li>
        <li class=" disabled"> <a href="{$charUrl}/companion" class="" rel="np"> <span class="arrow"><span class="icon"> Haus- und Reittiere </span></span> </a> </li>
        <li class=" disabled"> <a href="{$charUrl}/profession/" class=" has-submenu" rel="np"> <span class="arrow"><span class="icon"> Berufe </span></span> </a> </li>
        <li class=" disabled"> <a href="{$charUrl}/reputation/" class="" rel="np"> <span class="arrow"><span class="icon"> Ruf </span></span> </a> </li>
        <li class=" disabled"> <a href="{$charUrl}/pvp" class="" rel="np"> <span class="arrow"><span class="icon"> PvP </span></span> </a> </li>
        <li class="disabled"> <a href="{$charUrl}/feed" class="" rel="np"> <span class="arrow"><span class="icon"> Aktivitäten-Feed </span></span> </a> </li>
        <li class="disabled"> <a href="{$char->GetGuildLink()}" class=" has-submenu" rel="np"> <span class="arrow"><span class="icon"> Gilde </span></span> </a> </li>
    </ul>
    <div class="summary-sidebar-links">
                        <span class="summary-sidebar-button">
                            <a href="javascript:;" id="summary-link-tools" class="summary-link-tools"></a>
                        </span>
                        <span class="summary-sidebar-button">
                            <a href="javascript:;" data-fansite="character|EU|{$char->GetName()}|{$char->realmName}" class="fansite-link "> </a>
                        </span>
    </div>
</div>