<script type="text/javascript">
  var bugtrackerProjectPaths = {
    {foreach name=paths from=$projectPaths key=projectId item=path}
        {$projectId}: [{foreach name=pathrow from=$path item=item}{$item}{if $smarty.foreach.pathrow.last == false}, {/if}{/foreach}]{if $smarty.foreach.paths.last == false},
        {/if}
    {/foreach}
  };
</script>

<div class="form form-horizontal">
  {form_open('bugtracker/edit/', $form_attributes)}
    <input type="hidden" name="bugId" value="{$bugId}">
    <div class="form-group" >
      <label class="col-md-2 control-label">Kategorie</label>
      <div class="col-md-4">
        <select name="project" id="project" class="form-control">
          <option value="0">- Bitte wählen -</option>
          {foreach from=$baseProjects key=baseKey item=baseRow}
            <optgroup label="{$baseRow.title}">
              {foreach from=$baseRow.children key=childKey item=childTitle}
                <option value="{$childKey}" {if $post.project == $childKey}selected="selected"{/if}>{$childTitle}</option>
              {/foreach}
            </optgroup>
          {/foreach}
        </select>
      </div>
    </div>

    <div id="alert-project" class="alert alert-danger col-md-12" {if $post.project}style="display:none"{/if}>Bitte wähle zuerst eine Kategorie aus.</div>

    <div class="row">
        <fieldset id="ac-search-wrapper" class="col-md-11 jsServerOnly jsProjectFirst">
            <legend>Bitte füge wenigstens einen OpenWoW-Link hinzu.</legend><br>

            <div class="alert alert-info">Openwow hat getrennte Unterseiten für WotLK und Cataclysm mit den korrekten Daten zu diesen Zeiten. Dagegen sind die meisten anderen Fan-Sites (wowhead, buffed,..) auf dem aktuellen Stand von World of Warcraft.</div>

            <div class="form-group">
                <label class="control-label col-md-4" for="ac-search-type">Art des Links</label>
                <div class="controls col-md-8">
                    {form_dropdown('search-type', $idTypes, '', 'id="ac-search-type" class="form-control"')}
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-4" for="ac-search-field">Suchtext</label>
                <div class="controls col-md-7">
                    <div class="input-append">
                        <input type="text" id="ac-search-field" name="quest-detail" size="50" value="" class="form-control"/>
                        <span id="ac-loader" class="add-on"></span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-4" for="form-other-link">Anderen Link hinzufügen</label>
                <div class="controls col-md-8">
                    <div class="input-group">
                        <span class="input-group-addon">http://</span>
                        <input type="text" id="form-other-link" name="other-link" size="50" value="" class="input-xlarge form-control"/>
                        <span class="input-group-btn">
                            <button class="btn btn-default jsAddOtherLink" type="button" data-target="form-other-link">Hinzufügen</button>
                        </span>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>


    <div class="row">
      <fieldset class="col-md-11 jsWebsiteOnly jsProjectFirst">
        <legend>Du kannst einen Link zu einer der betroffenen Seiten hinzufügen.</legend>

        <div class="form-group">
          <label class="control-label col-md-4" for="form-other-link">Link hinzufügen</label>
          <div class="controls col-md-8">
            <div class="input-group">
                <span class="input-group-addon">http://</span>
                <input type="text" id="form-website-link" name="other-link" size="50" value="" class="input-xlarge form-control"/>
                <span class="input-group-btn">
                    <button class="btn btn-default jsAddOtherLink" type="button" data-target="form-website-link">Hinzufügen</button>
                </span>
            </div>
          </div>
        </div>
      </fieldset>
    </div>

  {if $showFixBugShit}
      <div class="row">
          <fieldset class="col-md-11 jsServerOnly jsConfirmed">
            <legend>F.I.X.B.U.G.S.H.I.T.</legend>
            <br/>
            <div class="alert alert-info">Wenn du Quests auf Autocomplete stellst, wird der Bugstatus auf &quot;Bestätigt&quot; gestellt.</div>

            <div class="form-group">
              <label class="control-label col-md-4">Quests</label>
              <div class="controls col-md-8">
                <table class="table">
                  {foreach $fbsQuests as $quest}
                    <tr>
                      <td>{$quest.id}</td>
                      <td>
                        <label class="checkbox">
                          <input type="checkbox" value="active" name="fbs_quest_{$quest.id}" {if $quest.isAutocomplete}checked="checked"{/if} class="form-control"> Autocomplete aktiv
                        </label>
                      </td>
                      <td>{$quest.title}</td>
                    </tr>
                  {foreachelse}
                    <tr>
                      <td>Es konnte keine Quest Id gefunden werden, bitte trage zuerst einen "openwow"-Link ein und speicher das Ticket.</td>
                    </tr>
                  {/foreach}
                </table>
              </div>
            </div>
          </fieldset>
      </div>
  {/if}

  <div id="form-link-wrapper" class="form-group jsProjectFirst">
    <label class="control-label col-md-2">Links</label>
    <div class="controls col-md-10">
      <table class="table col-md-9">
        <thead>
        <tr>
          <th><a href="javascript:;" class="sort-link"><span class="arrow">Link</span></a></th>
          <th><a href="javascript:;" class="sort-link"><span>&nbsp;</span></a></th>
        </tr>
        </thead>
        <tbody>
        {foreach from=$post.links key=i item=link}
          <tr id="prefilled-{$i}">
            <td>
              <input type="hidden" name="links[]" value="{$link}">
              <a href="{$link}" target="_blank">{$link}</a>
            </td>
            <td>
              <button class="btn btn-mini jsDeleteLink" data-target="prefilled-{$i}"><i class="glyphicon glyphicon-remove"></i> Entfernen</button>
            </td>
          </tr>
          <tr class="no-results" style="display:none">
            <td colspan="3">Noch keine Links eingetragen.</td>
          </tr>
          {foreachelse}
          <tr class="no-results">
            <td colspan="3">Noch keine Links eingetragen.</td>
          </tr>
        {/foreach}
        </tbody>
      </table>
    </div>
  </div>

  <div class="form-group jsProjectFirst">
    <label class="control-label col-md-2">Status</label>
    <div class="controls col-md-9">
      {form_dropdown('state', $bugStates, $post.state, 'class="form-control"')}
    </div>
  </div>

  <div class="form-group jsProjectFirst">
    <label class="control-label col-md-2">Priorität</label>
    <div class="controls col-md-9">
      {form_dropdown('priority', $bugPriorities, $post.priority, 'class="form-control"')}
    </div>
  </div>

  <div class="form-group jsProjectFirst">
    <label class="control-label col-md-2">Titel</label>
    <div class="controls col-md-9">
      <input type="text" id="form-title" name="title" size="50" value="{$post.title}" class="col-md-9 form-control"/>
    </div>
  </div>
  <div class="form-group jsProjectFirst">
    <label class="control-label col-md-2">Beschreibung</label>
    <div class="controls col-md-9">
      <textarea rows="8" id="form-desc" name="desc" class="col-md-9 form-control">{$post.desc}</textarea>
    </div>
  </div>

  <div class="form-group jsProjectFirst">
    <div class="controls col-md-9">
      <button class="btn btn-sm comment-submit" type="button" id="form-submit">
        Speichern
      </button>
    </div>
  </div>

  {form_close()}
</div>