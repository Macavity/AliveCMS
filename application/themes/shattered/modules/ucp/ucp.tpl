<section id="ucp_top">

  <div class="section-title">
    <span>Alive Accountdienste</span>
    <p>Benutze die Accountdienste, die für World of Warcraft verfügbar sind, um einfach deine Spiele upzugraden oder auf verschiedene,
      zusätzliche Accountoptionen zuzugreifen.</p>
  </div>


  <section id="ucp_info">

    <aside>
      <table width="100%">
        <tr>
          <td width="25"><img src="{$url}application/images/icons/user.png" /></td>
          <td width="130">{lang("nickname", "ucp")}</td>
          <td width="250">
            <a href="{$url}ucp/settings" data-tip="Change nickname" style="float:right;margin-right:10px;"><img src="{$url}application/images/icons/pencil.png" align="absbottom" /></a>
            <a href="profile/{$id}" data-tip="View profile">{$username}</a>
          </td>
        </tr>
        <tr>
          <td><img src="{$url}application/images/icons/world.png" /></td>
          <td>{lang("location", "ucp")}</td>
          <td>
            <a href="{$url}ucp/settings" data-tip="Change location" style="float:right;margin-right:10px;"><img src="{$url}application/images/icons/pencil.png" align="absbottom" /></a>
            {$location}
          </td>
        </tr>
        <tr>
          <td><img src="{$url}application/images/icons/plugin.png" /></td>
          <td>Expansion</td>
          <td>
            <a href="{$url}ucp/expansion" data-tip="Change expansion" style="float:right;margin-right:10px;"><img src="{$url}application/images/icons/cog.png" align="absbottom" /></a>
            {$expansion}
          </td>
        </tr>
        <tr>
          <td><img src="{$url}application/images/icons/award_star_bronze_1.png" /></td>
          <td>{lang("account_rank", "ucp")}</td>
          <td>{foreach from=$groups item=group} <span {if $group.color}style="color:{$group.color}"{/if}>{$group.name}</span> {/foreach}</td>
        </tr>
      </table>
    </aside>

    <aside>
      <table width="100%">
        <tr data-tip="Earn voting points by voting for the server">
          <td width="25"><img src="{$url}application/images/icons/lightning.png" /></td>
          <td width="160">{lang("voting_points", "ucp")}</td>
          <td width="250">{$vp}</td>
        </tr>
        {if false}
        <tr data-tip="Earn donation points by donating money to the server">
          <td><img src="{$url}application/images/icons/coins.png" /></td>
          <td>{lang("donation_points", "ucp")}</td>
          <td>{$dp}</td>
        </tr>
        {/if}
        <tr>
          <td><img src="{$url}application/images/icons/shield.png" /></td>
          <td>{lang("account_status", "ucp")}</td>
          <td>{$status}</td>
        </tr>
        <tr>
          <td><img src="{$url}application/images/icons/date.png" /></td>
          <td>{lang("member_since", "ucp")}</td>
          <td>{$register_date}</td>
        </tr>
      </table>
    </aside>
  </section>

  <div class="clear"></div>
</section>

<div class="ucp_divider"></div>

<div class="main-feature">

  <section id="ucp_buttons">

    <div class="main-services">
      {if hasPermission('view', "vote") && $config['vote']}
        <a href="{$url}{$config.vote}" class="main-services-banner left-bnr" style="background-image:url('{$image_path}/boxes/thumb-wallpaper.jpg');">
        <div class="panel">
          <span class="wrapper">
            <span class="banner-title">Voten</span>
            <span class="banner-desc">Vote f&uuml;r ALive damit mehr Spieler auf den Server kommen und die Gemeinde weiter w&auml;t.</span>
          </span>
        </div>
        </a>
      {/if}

      {if hasPermission('view', "store") && $config['store']}
        <a href="{$url}{$config.store}" class="main-services-banner right-bnr" style="background-image:url('{$image_path}/boxes/thumb-main-services-5.jpg');">
          <span class="banner-title">Vote Shop</span>
          <span class="banner-desc">Besuche den Alive Vote Shop und wirf einen Blick auf die M&ouml;glichkeiten die wir anbieten.</span>
        </a>
      {/if}

      {if hasPermission('canUpdateAccountSettings', 'ucp') && $config['settings']}
        <a href="{$url}{$config.settings}" class="main-services-banner left-bnr"
        style="background-image:url('{$image_path}/boxes/thumb-main-services-1.jpg');">
          <span class="banner-title">Accountverwaltung</span>
          <span class="banner-desc">Hier kannst du einstellen welche Charaktere f&uuml;r andere Forenbenutzer sichtbar sein sollen.</span>
        </a>
      {/if}

      {if hasPermission("canMigrateCharacter", "migration")}
        <a href="{$url}migration" class="main-services-banner right-bnr" style="background-image:url('{$image_path}/boxes/thumb-main-content-2.jpg');" >
          <span class="banner-title">Transferantrag</span>
          <span class="banner-desc">Details und Informationen wie du deinen bisherigen Charakter auf unseren Server &uuml;n kannst.</span>
        </a>
      {/if}

      <span class="clear"><!-- --></span>
    </div>

    <div class="clear"></div>
  </section>

  {$characters}

</div>
