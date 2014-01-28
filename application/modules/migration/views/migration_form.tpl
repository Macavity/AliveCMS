<a href="#formEnd" class="formEndLink btn btn-xs">Zum Ende des Formulars</a><br><br>

{form_open_multipart('migration/form', $formAttributes)}

    {if $validationErrors != "" || $formErrors != ""}
        <div class="alert alert-error">
            {$validationErrors}
            {$formErrors}
        </div>
    {/if}

  <fieldset>
    <legend>Dein alter Server</legend>

    <div class="form-group">
      <label class="control-label col-md-3">Alter Charaktername</label>
      <div class="controls col-md-8">
        <input type="text" name="name" size="25" value="{$post.name}" class="form-control">
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-3">Alter Servername</label>
      <div class="controls col-md-8">
        <input type="text" name="Server" value="{$post.Server}" class="form-control">
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-md-3">Webseite des Servers</label>
      <div class="controls col-md-8">
        <div class="input-group">
          <span class="input-group-addon">http://</span>
          <input type="text" name="Link" value="{$post.Link}" class="form-control">
        </div>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-3">Link zum Arsenal</label>
      <div class="controls col-md-8">
          <div class="input-group">
              <span class="input-group-addon">http://</span>
          <input type="text" name="Armory" value="{$post.Armory}" class="form-control">
        </div>
      </div>
    </div>

    <p>
      Screenshots (Played Time, Login, Abzeichen, Berufe, Ruf, Abzeichen, Taschen)! Bitte in eine gezippte Datei (ZIP oder RAR) packen und zum Beispiel bei einem Dienst wie <a href="http://www.file-upload.net/" target="_blank">http://www.file-upload.net</a> hochladen.
    </p>
    <div class="form-group">
      <label class="control-label col-md-3">Screenshotdatei</label>
      <div class="controls col-md-8">
        <input type="text" name="Download" value="{$post.Download}" class="form-control">
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-3">Bemerkung</label>
      <div class="controls col-md-8">
        <textarea name="Bemerkung" class="form-control">{$post.Bemerkung}</textarea>
      </div>
    </div>

  </fieldset>


  <fieldset>
    <legend>Über dich</legend>
    <p>Bitte gib uns eine zusätzliche Kontaktmöglichkeit an.<br><br></p>

    <div class="form-group">
      <label class="control-label col-md-3">ICQ (optional)</label>
      <div class="controls col-md-8">
        <input type="text" name="icq" value="{$post.icq}" size="25" class="form-control">
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-3">Skype (optional)</label>
      <div class="controls col-md-8">
        <input type="text" name="skype" value="{$post.skype}" size="25" class="form-control">
      </div>
    </div>
  </fieldset>

  <fieldset>
    <legend>Dein neuer Charakter</legend>

    <div class="form-group">
      <label class="control-label col-md-3">Gewünschte Rasse</label>
      <div class="controls col-md-8">
        <select name="race" id="race" class="form-control">
          {html_options options=$races selected=$post.race}
        </select>
        <br><br>
        <div class="alert alert-info">Achtung: Du kannst die Rasse wählen, die du hier bei uns spielen möchtest. Natürlich muss die Rasse zur Klasse passen.</div>
      </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3">Gewünschte Klasse</label>
        <div class="controls col-md-8">
            <select name="class" id="class" class="form-control">
                {html_options options=$classes selected=$post.class}
            </select>
            <br><br>
            <div class="alert alert-danger">Achtung: Bitte trage die Klasse ein die dein alter Charakter hatte, beim Transfer wird genau die Klasse transferiert die du vorher gespielt hast.</div>
        </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-3">Level</label>
      <div class="controls col-md-2">
        <input type="text" name="Level" value="{$post.Level}" size="3" class="form-control">
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-3">Gold (maximal 10000)</label>
      <div class="controls col-md-2">
        <input type="text" name="Gold" value="{$post.Gold}" class="form-control">
      </div>
    </div>
  </fieldset>

  <fieldset>
    <legend>Reiten</legend>

    <div class="form-group">
      <label class="control-label col-md-3">Reitenf&auml;higkeit</label>
      <div class="controls col-md-8">
        <div class="btn-group" data-toggle="buttons">
          {foreach from=$ridingLevels key=level item=label}
              <label class="btn btn-default {if $level == $post.Riding}active{/if}">
                  <input type="radio" name="Riding" id="Riding{$level}"> {$label}
              </label>
          {/foreach}
        </div>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-3">Bodenmount (ID)</label>
      <div class="controls col-md-2">
        <input type="text" name="Mount_boden" value="{$post.Mount_boden}" class="jsItemId form-control">
        <span class="help-inline"></span>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-3">Flugmount (ID)</label>
      <div class="controls col-md-2">
        <input type="text" name="Mount_flug" value="{$post.Mount_flug}" class="jsItemId form-control">
        <span class="help-inline"></span>
      </div>
    </div>
  </fieldset>

  <fieldset>
    <legend>Berufe</legend>

    <h2>Hauptberufe</h2>

    <div class="form-group">
      <label class="control-label col-md-3">Hauptberuf 1</label>
      <div class="controls col-md-8">
        <select name="Beruf1" class="form-control">
          {html_options options=$profs selected=$post.Beruf1}
        </select>
        <span class="help-inline"></span>
      </div>
      <div class="controls col-md-8 col-md-offset-3">
        <br>
        <div class="btn-group" data-toggle="buttons">
          {foreach from=array(0,75,150,225,300,350,400,450) item=item}
              <label class="btn btn-default {if $item == $post.Beruf1_skill}active{/if}">
                  <input type="radio" name="Beruf1_skill" id="Beruf1_skill{$item}" value="{$item}"> {$item}
              </label>
          {/foreach}
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-md-3">Hauptberuf 2</label>
      <div class="controls col-md-8">
        <select name="Beruf2" class="form-control">
          {html_options options=$profs selected=$post.Beruf2}
        </select>
        <span class="help-inline"></span>
      </div>
      <div class="controls col-md-8 col-md-offset-3">
        <br>
        <div class="btn-group" data-toggle="buttons">
          {foreach from=array(0,75,150,225,300,350,400,450) item=item}
              <label class="btn btn-default {if $item == $post.Beruf2_skill}active{/if}">
                  <input type="radio" name="Beruf2_skill" id="Beruf2_skill{$item}" value="{$item}"> {$item}
              </label>
          {/foreach}
        </div>
      </div>
    </div>

    <h2>Nebenberufe</h2>

    <div class="form-group">
      <label class="control-label col-md-3">Kochen</label>
      <div class="controls col-md-8">
        <div class="btn-group" data-toggle="buttons">
          {foreach from=array(0,75,150,225,300,350,400,450) item=item}
              <label class="btn btn-default {if $item == $post.Cooking}active{/if}">
                  <input type="radio" name="Cooking" id="Cooking{$item}" value="{$item}"> {$item}
              </label>
          {/foreach}
        </div>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-3">Angeln</label>
      <div class="controls col-md-8">
        <div class="btn-group" data-toggle="buttons">
          {foreach from=array(0,75,150,225,300,350,400,450) item=item}
              <label class="btn btn-default {if $item == $post.Angling}active{/if}">
                  <input type="radio" name="Angling" id="Angling{$item}" value="{$item}"> {$item}
              </label>
          {/foreach}
        </div>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-3">Erste Hilfe</label>
      <div class="controls col-md-8">
        <div class="btn-group" data-toggle="buttons">
          {foreach from=array(0,75,150,225,300,350,400,450) item=item}
              <label class="btn btn-default {if $item == $post.Firstaid}active{/if}">
                  <input type="radio" name="Firstaid" id="Firstaid{$item}" value="{$item}"> {$item}
              </label>
          {/foreach}
        </div>
      </div>
    </div>
  </fieldset>


  <fieldset>
    <legend>Ausrüstung</legend>

    {foreach from=$slots key=eqkey item=slot}
      <div class="form-group">
        <label class="control-label col-md-3">{$slot}</label>
        <div class="controls col-md-2">
          <input type="text" name="equip-{$eqkey}" value="{$post.equipment[$slot]}" class="input-mini jsItemId form-control">
          <span class="help-inline"></span>
        </div>
      </div>
    {/foreach}

  </fieldset>

  <fieldset>
    <legend>Gemischte Gegenstände</legend>

    <p>Du kannst bis zu 10 gemischte Gegenstände mitnehmen von deinem alten Server, das können spezielle Handwerksgegenstände, Equipteile für deine zweite Spec oder auch Reittiere/Haustiere sein, was immer du möchtest und dir wichtig ist. Beachte dass das maximale Itemlevel auch hier nicht überschritten werden darf.<br><br></p>

    {section name=random_item start=1 loop=11}
      <div class="form-group">
        <label class="control-label col-md-3">#{$smarty.section.random_item.index}</label>
        <div class="controls col-md-2">
          <input type="text" name="random-{$smarty.section.random_item.index}" value="{$post.random_item[$smarty.section.random_item.index]}" class="form-control jsItemId">
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
                <div class="form-group">
                    <label class="control-label col-md-3">{$rep_name}</label>
                    <div class="controls col-md-8">
                        <div class="btn-group" data-toggle="buttons">
                            {foreach from=$reputationStates key=state_key item=state}
                                <label class="btn btn-default {if $post.faction[$rep_id] == $state_key}active{/if}">
                                    <input type="radio" name="faction_{$rep_id}" value="{$state_key}"> {$state}
                                </label>
                            {/foreach}
                        </div>
                    </div>
                </div>
            {/foreach}
            {foreach from=$rep.alliance key=rep_id item=rep_name}
                <div class="form-group allianceOnly">
                    <label class="control-label col-md-3">{$rep_name}<i class="icon-faction-0"></i></label>
                    <div class="controls col-md-8">
                        <div class="btn-group" data-toggle="buttons">
                            {foreach from=$reputationStates key=state_key item=state}
                                <label class="btn btn-default {if $post.faction[$rep_id] == $state_key}active{/if}">
                                    <input type="radio" name="faction_{$rep_id}" value="{$state_key}"> {$state}
                                </label>
                            {/foreach}
                        </div>
                    </div>
                </div>
            {/foreach}
            {foreach from=$rep.horde key=rep_id item=rep_name}
                <div class="form-group hordeOnly">
                    <label class="control-label col-md-3">{$rep_name}<i class="icon-faction-1"></i></label>
                    <div class="controls col-md-8">
                        <div class="btn-group" data-toggle="buttons">
                            {foreach from=$reputationStates key=state_key item=state}
                                <label class="btn btn-default {if $post.faction[$rep_id] == $state_key}active{/if}">
                                    <input type="radio" name="faction_{$rep_id}" value="{$state_key}"> {$state}
                                </label>
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
<button type="button" class="btn btn-default pull-right jsMigrationSubmit">
  Abschicken
  <i class="glyphicon glyphicon-chevron-right"></i>
</button>
