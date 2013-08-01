<section class="box big" id="migration_cache">

    <h2>
        <i class="icon icon-tasks"></i>
        Transferlistencache erneuern
    </h2>

    <div class="alert alert-warning">
        Erneuert die Caches für die Transferlisten für alle Realms mit mehr als 2000 Transferen.
    </div>

    <div>
        {foreach from=$realms item=realm}
            <h2>Realm: {$realm.name}, {$realm.count} Transfere</h2>
            <p>&nbsp;</p>
            {foreach from=$realm.pakets item=paket}
                {if $paket.state == "new"}
                    <div class="alert alert-success">
                        <a href="{$url}migration/admin/cache_gen/{$realm.id}/{$paket.from}/{$paket.to}">Paket #{$paket.i}</a> von {$paket.from} bis {$paket.to} wurde Aktualisiert.
                    </div>
                {elseif $paket.state == "renew"}
                    <div class="alert alert-danger">
                        <a href="{$url}migration/admin/cache_gen/{$realm.id}/{$paket.from}/{$paket.to}">Paket #{$paket.i}</a> von {$paket.from} bis {$paket.to} fehlt.
                    </div>
                {elseif $paket.state == "existing"}
                    <div class="alert alert-info">
                        <a href="{$url}migration/admin/cache_gen/{$realm.id}/{$paket.from}/{$paket.to}">Paket #{$paket.i}</a> von {$paket.from} bis {$paket.to} ist vorhanden.
                    </div>
                {/if}
            {/foreach}
        {/foreach}
    </div>
</section>