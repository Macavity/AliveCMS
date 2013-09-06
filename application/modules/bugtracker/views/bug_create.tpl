<style type="text/css">
	textarea{
	    width:500px;
	    overflow:auto;
	}
	pre{
	    border: 1px solid white;
	    padding: 10px 20px;
	    margin-left: 50px;
	}
	h2{ padding: 10px 0px; color:#F0E29A;}
    #content fieldset{
        position: relative;
        width: 662px;
        border: 1px solid #ddd;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
        padding: 39px 19px 14px;
        margin: 15px 0 15px 180px;
    }
    #content fieldset legend{
        position: absolute;
        top: -1px;
        left: -1px;
        width: auto;
        padding: 3px 7px;

        color: black;
        font-weight: bold;
        font-size: 12px;

        background-color: #f5f5f5;
        border: 1px solid #ddd;

        -webkit-border-radius: 4px 0 4px 0;
        -moz-border-radius: 4px 0 4px 0;
        border-radius: 4px 0 4px 0;
    }
</style>

<script type="text/javascript">

    var bugtrackerProjectPaths = {
    {foreach name=paths from=$projectPaths key=projectId item=path}
        {$projectId}: [{foreach name=pathrow from=$path item=item}{$item}{if $smarty.foreach.pathrow.last == false}, {/if}{/foreach}]{if $smarty.foreach.paths.last == false},
    {/if}
    {/foreach}
    };
</script>

<div class="form form-horizontal">
{form_open('bugtracker/create', $form_attributes)}

    <div class="control-group" >
        <label class="control-label">Kategorie</label>
        <div class="controls">
            <select name="project" id="project">
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

    <div id="alert-project" class="alert alert-danger span11">Bitte wähle zuerst eine Kategorie aus.</div>

    <fieldset id="ac-search-wrapper" class="span11 jsProjectFirst">
        <legend>Bitte füge wenigstens einen OpenWoW-Link hinzu.</legend><br>

        <div class="alert alert-info">Openwow hat getrennte Unterseiten für WotLK und Cataclysm mit den korrekten Daten zu diesen Zeiten. Dagegen sind die meisten anderen Fan-Sites (wowhead, buffed,..) auf dem aktuellen Stand von World of Warcraft.</div>

        <div class="control-group">
            <label class="control-label" for="ac-search-type">Art des Links</label>
            <div class="controls">
                {form_dropdown('search-type', $idTypes, '', 'id="ac-search-type"')}
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="ac-search-field">Suchtext</label>
            <div class="controls">
                <div class="input-append">
                    <input type="text" id="ac-search-field" name="quest-detail" size="50" value=""/>
                    <span id="ac-loader" class="add-on"></span>
                </div>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="form-other-link">Anderen Link hinzufügen</label>
            <div class="controls">
                <div class="input-prepend input-append">
                    <span class="add-on">http://</span>
                    <input type="text" id="form-other-link" name="other-link" size="50" value="" class="input-xlarge"/>
                    <button class="btn jsAddOtherLink" type="button">Hinzufügen</button>
                </div>
            </div>
        </div>
    </fieldset>

    <div id="form-link-wrapper" class="control-group jsProjectFirst">
        <label class="control-label">Links</label>
        <div class="controls">
            <table class="table span9">
                <thead>
                <tr>
                    <th><a href="javascript:;" class="sort-link"><span class="arrow">Link</span></a></th>
                    <th><a href="javascript:;" class="sort-link"><span>&nbsp;</span></a></th>
                </tr>
                </thead>
                <tbody>
                {foreach from=$post.links key=i item=link}
                    <tr id="prefilled-{$i}" class="{cycle values="row1,row2"}">
                        <td>
                            <input type="hidden" name="links[]" value="{$link}">
                            <a href="{$link}" target="_blank">{$link}</a>
                        </td>
                        <td>
                            <button class="btn btn-mini jsDeleteLink" data-target="prefilled-{$i}"><i class="icon icon-remove"></i> Entfernen</button>
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

    <div id="form-similar-bugs-wrapper" class="control-group jsProjectFirst">
        <label class="control-label"></label>
        <div class="controls">

        </div>
    </div>

    {if hasPermission("canPrioritize")}
    <div class="control-group jsProjectFirst">
        <label class="control-label">Priorität</label>
        <div class="controls">
            {form_dropdown('priority', $bugPriorities, $post.priority)}
        </div>
    </div>
    {/if}

    <div class="control-group jsProjectFirst">
        <label class="control-label">Titel</label>
        <div class="controls">
            <input type="text" id="form-title" name="title" size="50" value="{$post.title}" class="span9"/>
        </div>
    </div>
    <div class="control-group jsProjectFirst">
        <label class="control-label">Beschreibung</label>
        <div class="controls">
            <textarea rows="8" id="form-desc" name="desc" class="span9">{$post.desc}</textarea>
        </div>
    </div>

    <div class="control-group jsProjectFirst">
        <div class="controls">
            <button class="ui-button button1 comment-submit" type="button" id="form-submit">
                <span><span>Eintragen</span></span>
            </button>
        </div>
    </div>

{form_close()}
</div>