<script type="text/javascript">

    var bugtrackerProjectPaths = {
    {foreach name=paths from=$projectPaths key=projectId item=path}
        {$projectId}: [{foreach name=pathrow from=$path item=item}{$item}{if $smarty.foreach.pathrow.last == false}, {/if}{/foreach}]{if $smarty.foreach.paths.last == false},
    {/if}
    {/foreach}
    };

  var fixBugShitCategories = [{foreach name=fbscats from=$fbsCategories item=item}{$item}{if $smarty.foreach.fbscats.last == false}, {/if}{/foreach}];
</script>

<div class="form form-horizontal">
{form_open('bugtracker/create', $form_attributes)}

    <div class="form-group" >
        <label class="control-label col-md-2">Kategorie</label>
        <div class="controls col-md-9">
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

    <div id="alert-project" class="alert alert-danger col-md-10 col-md-offset-1">Bitte wähle zuerst eine Kategorie aus.</div>

    <fieldset id="ac-search-wrapper" class="span11 jsServerOnly jsProjectFirst">
        <legend>Bitte füge wenigstens einen OpenWoW-Link hinzu.</legend><br>

        <div class="alert alert-info">Openwow hat getrennte Unterseiten für WotLK und Cataclysm mit den korrekten Daten zu diesen Zeiten. Dagegen sind die meisten anderen Fan-Sites (wowhead, buffed,..) auf dem aktuellen Stand von World of Warcraft.</div><br>

        <div class="alert alert-info jsFixBugShitOnly">Bei diesen Quests ist unser F.I.X.B.U.G.S.H.I.T.-System aktiv. Wenn du einen OpenWow-Quest-Link einträgst kann dieses Quest von einem GameMaster auf Autocomplete gestellt werden.</div>

        <div class="form-group">
            <label class="control-label col-md-3" for="ac-search-type">Art des Links</label>
            <div class="controls col-md-9">
                {form_dropdown('search-type', $idTypes, '', 'id="ac-search-type" class="form-control"')}
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-3" for="ac-search-field">Suchtext</label>
            <div class="controls col-md-8">
                <div class="input-append">
                    <input type="text" id="ac-search-field" name="quest-detail" size="50" value="" class="form-control"/>
                    <span id="ac-loader" class="add-on"></span>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-md-3" for="form-other-link">Anderen Link hinzufügen</label>
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

    <div id="form-link-wrapper" class="form-group jsProjectFirst">
        <label class="control-label col-md-2">Links</label>
        <div class="controls col-md-8">
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


    <div id="form-similar-bugs-wrapper" class="form-group jsProjectFirst">
        <label class="control-label col-md-2"></label>
        <div class="controls col-md-8">

        </div>
    </div>

    {if hasPermission("canPrioritize")}
    <div class="form-group jsProjectFirst">
        <label class="control-label col-md-2">Priorität</label>
        <div class="controls col-md-6">
            {form_dropdown('priority', $bugPriorities, $post.priority, 'class="form-control"')}
        </div>
    </div>
    {/if}

    <div class="form-group jsProjectFirst">
        <label class="control-label col-md-2">Titel</label>
        <div class="controls col-md-6">
            <input type="text" id="form-title" name="title" size="50" value="{$post.title}" class="form-control"/>
        </div>
    </div>
    <div class="form-group jsProjectFirst">
        <label class="control-label col-md-2">Beschreibung</label>
        <div class="controls col-md-6">
            <textarea rows="8" id="form-desc" name="desc" class="form-control">{$post.desc}</textarea>
        </div>
    </div>

    <div class="form-group jsProjectFirst">
        <div class="controls col-md-10 col-md-offset-2">
            <button class="btn btn-sm comment-submit" type="button" id="form-submit">
                Eintragen
            </button>
        </div>
    </div>

{form_close()}
</div>