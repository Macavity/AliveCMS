<style type="text/css">
    textarea{
        width:500px;
        overflow:auto;
    }
</style>

<script type="text/javascript">
    $(document).ready(function() {
        $("textarea").click(function(){
            // Select field contents
            this.select();
        });
    });
</script>

<section class="box big form-horizontal" id="migration_list">
    <h2>
        <i class="icon icon-resize-full"></i>
        Transferdetail
    </h2>

    {if $message}
        {if $message.type == "info"}<div class="alert alert-info">{$message.message}</div>{/if}
        {if $message.type == "error"}<div class="alert alert-danger">{$message.message}</div>{/if}
        {if $message.type == "success"}<div class="alert alert-success">{$message.message}</div>{/if}
    {/if}

    {if $migration.status == $state_done}
        <div class="alert alert-success">Aktueller Status: {$migration.status_label}</div>
    {elseif $migration.status == $state_inprogress}
        <div class="alert alert-warning">Aktueller Status: {$migration.status_label}</div>
    {elseif $migration.status == $state_declined}
        <div class="alert alert-warning">Aktueller Status: {$migration.status_label}</div>
    {elseif $migration.status == $state_open}
        <div class="alert alert-danger">Aktueller Status: {$migration.status_label}</div>
    {/if}

    <fieldset>
        <legend>Vorgehensweise</legend>
        <ol class="list">
            <li>&Uuml;berpr&uuml;ft den Alten Server genau ob er Blizzlike ist, durchforstet die HP und ggf das Forum! Sollte der Link zum Alten Server fehlen muss der Spieler diesen Nachreichen, da er beweisen muss das der letzte Server Blizzlike war!</li>
            <li>Wichtig: Wenn der Spieler von Blizzard kommt ist der Armorylink absolute Pflicht.</li>
            <li>Ladet euch die Screenshots runter, solltet ihr Probleme mit dem Download von z.B Rapidshare haben bittet den Spieler, das Packet woanders hochzuladen! Wichtig: schau dir die Screenshots genau an, wichtig sind vor allem Play-Time (sollte bei Level 80 so bei mindestens 5 Tagen liegen) und der Login Screenshot.</li>
            <li>Erstellt den Charakter gem&auml;&szlig; Screens (Rasse etc), portet euch zur <b>.tele transferinsel</b> und macht den Transfer. Als letztes gibst du <b>.character customize</b> ein und logst dich aus.</li>
            <li>Trage nun unten im Formular die GUID des neuen Chars ein und wähle die Checkbox für "Abgeschlossen" aus, der Charakter wird dann auf den neuen Account transferiert.</li>
        </ol>
    </fieldset>

    <fieldset>
        <legend>Basis</legend>

        <div class="control-group">
            <label class="control-label">Formular ID</label>
            <div class="controls">{$migration.id}</div>
        </div>

        <div class="control-group">
            <label class="control-label">Account ID</label>
            <div class="controls">{$migration.account_id}</div>
        </div>

        <div class="control-group">
            <label class="control-label">Account Name</label>
            <div class="controls">{$migration.account_name}</div>
        </div>

        <div class="control-group">
            <label class="control-label">Erstellt am</label>
            <div class="controls">{$migration.date_created}</div>
        </div>

        <div class="control-group">
            <label class="control-label">ICQ</label>
            <div class="controls">{$migration.icq}</div>
        </div>

        <div class="control-group">
            <label class="control-label">Skype</label>
            <div class="controls">{$migration.skype}</div>
        </div>

        <div class="control-group">
            <label class="control-label">Klasse</label>
            <div class="controls">{$migration.class_label}</div>
        </div>

        <div class="control-group">
            <label class="control-label">Rasse</label>
            <div class="controls">{$migration.race_label}</div>
        </div>

        <div class="control-group">
            <label class="control-label">Level</label>
            <div class="controls">{$migration.level}</div>
        </div>

    </fieldset>

    <fieldset>
        <legend>Andere Transfere ({$migration_count-1}) dieses Accounts</legend>

        {if $migration_count > 0}
            <div class="table">
                <table width="100%">
                    <tr>
                        <th>ID</th>
                        <th>Char</th>
                        <th>Server</th>
                        <th>Status</th>
                        <th>&nbsp;</th>
                    </tr>
                    {foreach from=$other_migrations item=transfer}
                        <tr class="{cycle values="row1,row2"}">
                            <td><a href="{$url}migration/admin/detail/{$transfer.id}" target="_blank">{$transfer.id}</a></td>
                            <td>{$transfer.character_name}</td>
                            <td>{$transfer.server_name}</td>
                            <td>{$transfer.state_label}</td>
                            <td>{$transfer.message}</td>
                        </tr>
                    {/foreach}
                </table>
            </div>
            <br />
        {/if}
    </fieldset>

    <fieldset>
        <legend>Charaktere dieses Accounts</legend>

        <div class="table">
            <table width="100%">
                <tr>
                    <th>ID</th>
                    <th>Level</th>
                    <th>Charaktername</th>
                    <th>Klasse</th>
                    <th>Realm</th>
                </tr>
                {foreach from=$characters item=char}
                    <tr class="{cycle values="row1,row2"}">
                        <td>{$char.guid}</td>
                        <td>{$char.level}</td>
                        <td>{$char.name}</td>
                        <td class="color-c{$char.class}">{$char.class_label}</td>
                        <td>{$char.realm}</td>
                    </tr>
                {/foreach}
            </table>
        </div>
    </fieldset>


    <fieldset>
        <legend>Der alte Server</legend>

        <div class="control-group">
            <label class="control-label">Alter Charaktername</label>
            <div class="controls">{$migration.character_name}</div>
        </div>

        {if $migration.current_name}
            <div class="control-group">
                <label class="control-label">Aktueller Charaktername</label>
                <div class="controls">{$migration.current_name}</div>
            </div>
        {/if}

        <div class="control-group">
            <label class="control-label">Alter Servername</label>
            <div class="controls">{$migration.server_name}</div>
        </div>

        <div class="control-group">
            <label class="control-label">Webseite des Servers</label>
            <div class="controls"><a href="{$migration.server_link}" target="_blank">{$migration.server_link}</a></div>
        </div>

        <div class="control-group">
            <label class="control-label">Link zum Arsenal</label>
            <div class="controls"><a href="{$migration.character_armory}" target="_blank">{$migration.character_armory}</a></div>
        </div>

        <div class="control-group">
            <label class="control-label">Screenshotdatei</label>
            <div class="controls"><a href="{$migration.screenshots_link}" target="_blank">{$migration.screenshots_link}</a></div>
        </div>

        <div class="control-group">
            <label class="control-label">Bemerkung</label>
            <div class="controls">{$migration.comment}</div>
        </div>

    </fieldset>

    <div class="alert alert-info">
        Wichtig: Der hier angegebene Levelup-Befehl setzt voraus dass der Spieler Level 1 ist.
    </div>

    <div>
        <div class="control-group">
            <label class="control-label">Level, Lerngeld und Taschen:</label>
            <div class="controls">
<pre>
.levelup {$migration.level - 1}

.modify money 2000000000
.char customize
.add 21841 4
</pre>
            </div>
        </div>

        <div class="control-group">
            <div class="controls">
                Nach dem Lernen:<br>
                <code>.modify money -2000000000</code>
            </div>
        </div>

        <div class="control-group">
            <div class="controls">
                Geld: Jetzt erst den Orginal Betrag adden.<br>
                <code>.modify money {$migration.gold}</code>
            </div>
        </div>


        <div class="control-group">
            <label class="control-label">Haupt- und Nebenberufe</label>
            <div class="controls">
                Hauptberufe: {foreach from=$migration.professions item=prof}{$prof.label} {/foreach}
<pre>
{if $migration.Riding > 0}
{if $migration.Riding == 301}
.learn {$migration.Riding_learn}
.learn 54197
{else}
.learn {$migration.Riding_learn}
{/if}
{/if}

{foreach from=$migration.professions item=prof}
.learn {$prof.learn_spell}
.setskill {$prof.skill} {min($prof.skill_level,450)} 450
{/foreach}

{if $migration.Cooking > 0}
.learn 2550
.setskill 185 {$migration.Cooking} 450
{/if}

{if $migration.Angling > 0}
.learn 7620
.setskill 356 {$migration.Angling} 450
{/if}

{if $migration.Firstaid > 0}
.learn 3279
.setskill 129 {$migration.Firstaid} 450
{/if}
</pre>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label">Zusatzmaterial:</label>
            <div class="controls">
        {if $migration.juwe_max}
            <code>.add 41596 50</code>
        {/if}
        {if $migration.vz_max}
            <code>.add 34052 40</code>
        {/if}
        {if $migration.ik_max}
            <code>.add 45912 10</code>
        {/if}
        {if $migration.leder_max}
            <code>.add 38425 30</code>
        {/if}
        {if $migration.koch_max}
            <code>.add 43016 20</code>
        {/if}
            </div>
        </div>
    </div>

    <fieldset>
        <legend>Items</legend>

        <div class="alert alert-warning">&Uuml;berpr&uuml;ft bei den Items ob diese Itemlevel 245 &uuml;berschreiten sollte dies der Fall sein, soll der Spieler sich ein neues Item rausuchen.</div>

        <div class="control-group">
            <div class="controls">

                <table class="table">
                    {foreach from=$migration.slots item=slot}
                        <tr>
                            <td>{$slot.name}</td>
                            <td>{$slot.item_id}</td>
                            <td>{$slot.item_level}</td>
                            <td><a href="/item/1/{$slot.item_id}" target="_blank">{$slot.item_name}</a></td>
                            <td>{$slot.message}</td>
                        </tr>
                    {/foreach}
                </table>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label">Makro Ausrüstung</label>
            <div class="controls">
<pre>
{foreach from=$migration.slots item=slot}
.additem {$slot.item_id}
{/foreach}
</pre>
            </div>
        </div>

    </fieldset>

    <fieldset>
        <legend>Reittiere und Gemischte Gegenstände</legend>

        <div class="control-group">
            <div class="controls">
                <table class="table">
                    {foreach from=$migration.items item=item}
                        <tr>
                            <td>{$item.name}</td>
                            <td>{$item.item_id}</td>
                            <td><a href="/item/1/{$item.item_id}" target="_blank">{$item.item_name}</a></td>
                        </tr>
                    {/foreach}
                </table>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Makro</label>
            <div class="controls">
<pre>
{foreach from=$migration.items item=item}
.additem {$item.item_id}
{/foreach}
</pre>
            </div>
        </div>

    </fieldset>

    <fieldset>
        <legend>Ruf</legend>

        <div class="control-group">
            <label class="control-label">Makro</label>
            <div class="controls">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Fraktionsname</th>
                        </tr>
                    </thead>
                    <tr>
                        <td>
                            {foreach from=$migration.factions key=faction_id item=faction}
                                <a href="http://de.wowhead.com/?faction={$faction_id}">{$faction.label}</a><br>
                            {/foreach}</td>
                        <td>
                            {foreach from=$migration.factions key=faction_id item=faction}
                                .modify rep {$faction_id} {$faction.standing}<br>
                            {/foreach}</td>
                    </tr>
                </table>
            </div>
        </div>
    </fieldset>

    <fieldset>
        <legend>Protokoll</legend>
        <table class="table">
            <thead>
            <tr>
                <th>Datum</th>
                <th>GameMaster</th>
                <th>Status</th>
                <th>Bemerkung</th>
            </tr>
            </thead>
            <tbody>
            {foreach from=$migration.actions item=action}
                <tr>
                    <td>{$action.date}</td>
                    <td>{$action.by}</td>
                    <td>{$action.status_label}</td>
                    <td>{$action.reason}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    </fieldset>


    <fieldset>
        <legend>Fertig?</legend>
        {form_open($form_action, $form_attributes)}
            <input type="hidden" name="change_detail" value="yes">

            <div class="alert alert-info">Bitte tragt die GUID von dem neu erstellen Charakter ein.</div>
            <div class="control-group">
                <label class="control-label">Charakter GUID</label>
                <div class="controls">
                    <input type="text" name="character_guid" class="input-xlarge" value="{$migration.character_guid}"><br/><br/>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label"></label>
                <div class="controls">
                    <label class="checkbox">
                        <input type="checkbox" name="transfer_to_account" value="yes">
                        Charakter auf Spieler-Account schieben?
                    </label>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Dein Name</label>
                <div class="controls">
                    {$gm_account_name}
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Neuer Status</label>
                <div class="controls">
                    <input type="hidden" class="hidden" name="new_status" value="{$migration.status}">
                    <div class="btn-group" data-toggle="buttons-radio">
                        {foreach from=$migration_states key=status_id item=status}
                            <button type="button" class="btn {if $status_id == $migration.status}active{/if}" data-target="new_status" value="{$status_id}">{$status.label}</button>
                        {/foreach}
                    </div>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Bemerkung/Grund</label>
                <div class="controls">
                    <input type="text" name="new_comment" class="input-xlarge" value="">
                </div>
            </div>

            <div class="control-group">
                <div class="controls">
                    <button type="submit" class="btn btn-primary">Abschicken</button>
                </div>
            </div>
        </form>

    </fieldset>

</section>
<script type="text/javascript">
  require(['/application/js/static.js'], function(){
    require([
      'controller/AdminController',
      'tooltip'
    ],
            function (AdminController, Tooltip) {
              $(function () {
                debug.debug("js/migration_admin/detail");

                var controller = new AdminController();


              });
            });
  });

</script>


