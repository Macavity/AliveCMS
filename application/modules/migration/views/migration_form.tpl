<style>
    .form-horizontal .control-label {
        width: 170px;
    }
    .form-horizontal .controls{
        max-resolution: 190px;
    }
</style>
<a href="#formEnd" class="formEndLink">Zum Ende des Formulars</a>

{form_open_multipart('migration/form', $formAttributes)}

    {if $validationErrors != "" || $formErrors != ""}
        <div class="alert alert-error">
            {$validationErrors}
            {$formErrors}
        </div>
    {/if}

  <fieldset>
    <legend>Dein alter Server</legend>

    <div class="control-group">
      <label class="control-label">Alter Charaktername</label>
      <div class="controls">
        <input type="text" name="name" size="25" value="{$post.name}" class="input-xlarge">
      </div>
    </div>

    <div class="control-group">
      <label class="control-label">Alter Servername</label>
      <div class="controls">
        <input type="text" name="Server" value="{$post.Server}" class="input-xlarge">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label">Webseite des Servers</label>
      <div class="controls">
        <div class="input-prepend">
          <span class="add-on">http://</span>
          <input type="text" name="Link" value="{$post.Link}" class="input-large">
        </div>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label">Link zum Arsenal</label>
      <div class="controls">
       <div class="input-prepend">
          <span class="add-on">http://</span>
          <input type="text" name="Armory" value="{$post.Armory}" class="input-large">
        </div>
      </div>
    </div>

    <p>
      Screenshots (Played Time, Login, Abzeichen, Berufe, Ruf, Abzeichen, Taschen)! Bitte in eine gezippte Datei (ZIP oder RAR) packen und zum Beispiel bei einem Dienst wie <a href="http://www.file-upload.net/" target="_blank">http://www.file-upload.net</a> hochladen.
    </p>
    <div class="control-group">
      <label class="control-label">Screenshotdatei</label>
      <div class="controls">
        <input type="text" name="Download" value="{$post.Download}" class="input-large">
      </div>
    </div>

    <div class="control-group">
      <label class="control-label">Bemerkung</label>
      <div class="controls">
        <textarea name="Bemerkung">{$post.Bemerkung}</textarea>
      </div>
    </div>

  </fieldset>


  <fieldset>
    <legend>Über dich</legend>
    <p>Bitte gib uns eine zusätzliche Kontaktmöglichkeit an.<br><br></p>

    <div class="control-group">
      <label class="control-label">ICQ (optional)</label>
      <div class="controls">
        <input type="text" name="icq" value="{$post.icq}" size="25">
      </div>
    </div>

    <div class="control-group">
      <label class="control-label">Skype (optional)</label>
      <div class="controls">
        <input type="text" name="skype" value="{$post.skype}" size="25">
      </div>
    </div>
  </fieldset>

  <fieldset>
    <legend>Dein neuer Charakter</legend>

    <div class="control-group">
      <label class="control-label">Gewünschte Rasse</label>
      <div class="controls">
        <select name="race" id="race">
          {html_options options=$races selected=$post.race}
        </select>
        <br><br>
        <div class="alert alert-info">Achtung: Du kannst die Rasse wählen, die du hier bei uns spielen möchtest. Natürlich muss die Rasse zur Klasse passen.</div>
      </div>
    </div>

    <div class="control-group">
        <label class="control-label">Gewünschte Klasse</label>
        <div class="controls">
            <select name="class" id="class">
                {html_options options=$classes selected=$post.class}
            </select>
            <br><br>
            <div class="alert alert-danger">Achtung: Bitte trage die Klasse ein die dein alter Charakter hatte, beim Transfer wird genau die Klasse transferiert die du vorher gespielt hast.</div>
        </div>
    </div>

    <div class="control-group">
      <label class="control-label">Level</label>
      <div class="controls">
        <input type="text" name="Level" value="{$post.Level}" size="3">
      </div>
    </div>

    <div class="control-group">
      <label class="control-label">Gold (maximal 10000)</label>
      <div class="controls">
        <input type="text" name="Gold" value="{$post.Gold}">
      </div>
    </div>
  </fieldset>

  <fieldset>
    <legend>Reiten</legend>

    <div class="control-group">
      <label class="control-label">Reitenf&auml;higkeit</label>
      <div class="controls">
        <input type="hidden" class="hidden" name="Riding" value="{$post.Riding}">
        <div class="btn-group" data-toggle="buttons-radio">
          {foreach from=$ridingLevels key=level item=label}
            <button type="button" class="btn {if $level == $post.Riding}active{/if}" data-target="Riding" value="{$level}">{$label}</button>
          {/foreach}
        </div>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label">Bodenmount (ID)</label>
      <div class="controls">
        <input type="text" name="Mount_boden" value="{$post.Mount_boden}" class="jsItemId">
        <span class="help-inline"></span>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label">Flugmount (ID)</label>
      <div class="controls">
        <input type="text" name="Mount_flug" value="{$post.Mount_flug}" class="jsItemId">
        <span class="help-inline"></span>
      </div>
    </div>
  </fieldset>

  <fieldset>
    <legend>Berufe</legend>

    <h2>Hauptberufe</h2>

    <div class="control-group">
      <label class="control-label">Hauptberuf 1</label>
      <div class="controls">
        <select name="Beruf1">
          {html_options options=$profs selected=$post.Beruf1}
        </select>
        <span class="help-inline"></span>
      </div>
      <div class="controls">
        <br>
        <input type="hidden" class="hidden" name="Beruf1_skill" value="{$post.Beruf1_skill}">
        <div class="btn-group" data-toggle="buttons-radio">
          {foreach from=array(0,75,150,225,300,350,400,450) item=item}
            <button type="button" class="btn {if $item == $post.Beruf1_skill}active{/if}" data-target="Beruf1_skill" value="{$item}">{$item}</button>
          {/foreach}
        </div>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label">Hauptberuf 2</label>
      <div class="controls">
        <select name="Beruf2">
          {html_options options=$profs selected=$post.Beruf2}
        </select>
        <span class="help-inline"></span>
      </div>
      <div class="controls">
        <br>
        <input type="hidden" class="hidden" name="Beruf2_skill" value="{$post.Beruf2_skill}">
        <div class="btn-group" data-toggle="buttons-radio">
          {foreach from=array(0,75,150,225,300,350,400,450) item=item}
            <button type="button" class="btn {if $item == $post.Beruf2_skill}active{/if}" data-target="Beruf2_skill" value="{$item}">{$item}</button>
          {/foreach}
        </div>
      </div>
    </div>

    <h2>Nebenberufe</h2>

    <div class="control-group">
      <label class="control-label">Kochen</label>
      <div class="controls">
        <input type="hidden" class="hidden" name="Cooking" value="{$post.Cooking}">
        <div class="btn-group" data-toggle="buttons-radio">
          {foreach from=array(0,75,150,225,300,350,400,450) item=item}
            <button type="button" class="btn {if $item == $post.Cooking}active{/if}" data-target="Cooking" value="{$item}">{$item}</button>
          {/foreach}
        </div>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label">Angeln</label>
      <div class="controls">
        <input type="hidden" class="hidden" name="Angling" value="{$post.Angling}">
        <div class="btn-group" data-toggle="buttons-radio">
          {foreach from=array(0,75,150,225,300,350,400,450) item=item}
            <button type="button" class="btn {if $item == $post.Angling}active{/if}" data-target="Angling" value="{$item}">{$item}</button>
          {/foreach}
        </div>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label">Erste Hilfe</label>
      <div class="controls">
        <input type="hidden" class="hidden" name="Firstaid" value="{$post.Firstaid}">
        <div class="btn-group" data-toggle="buttons-radio">
          {foreach from=array(0,75,150,225,300,350,400,450) item=item}
            <button type="button" class="btn {if $item == $post.Firstaid}active{/if}" data-target="Firstaid" value="{$item}">{$item}</button>
          {/foreach}
        </div>
      </div>
    </div>
  </fieldset>


  <fieldset>
    <legend>Ausrüstung</legend>

    {foreach from=$slots key=eqkey item=slot}
      <div class="control-group">
        <label class="control-label">{$slot}</label>
        <div class="controls">
          <input type="text" name="equip-{$eqkey}" value="{$post.equipment[$slot]}" class="input-mini jsItemId">
          <span class="help-inline"></span>
        </div>
      </div>
    {/foreach}

  </fieldset>

  <fieldset>
    <legend>Gemischte Gegenstände</legend>

    <p>Du kannst bis zu 10 gemischte Gegenstände mitnehmen von deinem alten Server, das können spezielle Handwerksgegenstände, Equipteile für deine zweite Spec oder auch Reittiere/Haustiere sein, was immer du möchtest und dir wichtig ist. Beachte dass das maximale Itemlevel auch hier nicht überschritten werden darf.<br><br></p>

    {section name=random_item start=1 loop=11}
      <div class="control-group">
        <label class="control-label">#{$smarty.section.random_item.index}</label>
        <div class="controls">
          <input type="text" name="random-{$smarty.section.random_item.index}" value="{$post.random_item[$smarty.section.random_item.index]}" class="input-mini jsItemId">
          <span class="help-inline"></span>
        </div>
      </div>
    {/section}
  </fieldset>

  <fieldset>
    <legend>Rufe</legend>
    <p>Du kannst den Ruf von Fraktionen den du dir bereits erarbeitet hattest ebenfalls mitnehmen. Beachte dass dies auf dem Screenshot bestätigt sein muss.</p>

    {foreach from=$reputations key=rep_id item=rep}
        <a data-toggle="collapse" data-target="#{$rep_id}"><i class="icon"></i>{$rep.label}</a>
        <div class="tab-pane collapse in" id="{$rep_id}">
            {foreach from=$rep.factions key=rep_id item=rep_name}
                <div class="control-group">
                    <label class="control-label">{$rep_name}</label>
                    <div class="controls">
                        <input type="hidden" name="faction_{$rep_id}" value="{$post.faction[$rep_id]}">
                        <div class="btn-group" data-toggle="buttons-radio">
                            {foreach from=$reputationStates key=state_key item=state}
                                <button type="button" class="btn {if $post.faction[$rep_id] == $state_key}active{/if}" data-target="faction_{$rep_id}" value="{$state_key}">{$state}</button>
                            {/foreach}
                        </div>
                    </div>
                </div>
            {/foreach}
            {foreach from=$rep.alliance key=rep_id item=rep_name}
                <div class="control-group allianceOnly">
                    <label class="control-label">{$rep_name}<i class="icon-faction-0"></i></label>
                    <div class="controls">
                        <input type="hidden" name="faction_{$rep_id}" value="{$post.faction[$rep_id]}">
                        <div class="btn-group" data-toggle="buttons-radio">
                            {foreach from=$reputationStates key=state_key item=state}
                                <button type="button" class="btn {if $post.faction[$rep_id] == $state_key}active{/if}" data-target="faction_{$rep_id}" value="{$state_key}">{$state}</button>
                            {/foreach}
                        </div>
                    </div>
                </div>
            {/foreach}
            {foreach from=$rep.horde key=rep_id item=rep_name}
                <div class="control-group hordeOnly">
                    <label class="control-label">{$rep_name}<i class="icon-faction-1"></i></label>
                    <div class="controls">
                        <input type="hidden" name="faction_{$rep_id}" value="{$post.faction[$rep_id]}">
                        <div class="btn-group" data-toggle="buttons-radio">
                            {foreach from=$reputationStates key=state_key item=state}
                                <button type="button" class="btn {if $post.faction[$rep_id] == $state_key}active{/if}" data-target="faction_{$rep_id}" value="{$state_key}">{$state}</button>
                            {/foreach}
                        </div>
                    </div>
                </div>
            {/foreach}
        </div>

    {/foreach}


  </fieldset>

</form>

<p class="lead">
  Ich best&auml;tige dass ich die <a href ="/migration/" target="_blank">Transferanleitung</a> gelesen habe und mein Equipment entsprechend ausgef&uuml;llt habe. Ich bin mir bewusst das ein leeres Formular auch einen leeren Charakter ergibt.
</p>

<div class="alert alert-info">
  Bei Problemen oder Fragen meldet euch Ingame, im <a href="http://forum.wow-alive.de/"><strong>WoW Alive Forum</strong></a> oder per TS3 bei einem GM
</div>

<a name="formEnd"></a>
<button type="button" class="ui-button button1 button1-next float-right jsMigrationSubmit">
  <span><span>Abschicken</span></span>
</button>