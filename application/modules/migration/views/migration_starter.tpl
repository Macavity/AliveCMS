
<p>Beschreibungstext</p>

{form_open_multipart('migration/form')}

{if $activeCharGuid == 0}
    <div class="alert alert-danger">
        <p>
            Bitte wähle zuerst (rechts neben dem Hauptmenü) den gewünschten Charakter aus, der das Starter-Paket erhalten soll.<br/>
            Wenn du noch keinen Charakter haben solltest, logge dich im Spiel ein und erstelle deinen Wunschcharakter mit der gewünschten Klasse.
        </p>
    </div>

    <a href="{$url}migration/starter/" class="btn btn-small pull-right">
        Ok, habe einen ausgewählt. Weiter.
        <i class="glyphicon glyphicon-chevron-right"></i>
    </a>
{elseif $hasError}
    <div class="alert alert-danger">
        {foreach $errorMessages as $errorMessage}
            <p>{$errorMessage}</p>
        {/foreach}
    </div>
{else}
<fieldset>
    <legend>Wähle dein Starterpaket für die Klasse <span class="wow-class-{$classId}">{$classLabel}</span></legend>

    <div class="form-group">
        <label class="control-label col-md-8">Gewünschte Rolle</label>
        <div class="controls col-md-4">
            <select name="talenttree" id="role-select" class="form-control">
                <option>Bitte auswählen</option>
                {foreach $talentTrees as $tree => $items}
                    <option value="{$tree}">{$talentTreeLabels.$tree}</option>
                {/foreach}
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-12">Vorschau auf das Starterpaket für diese Rolle</label>
        <div id="role-preview" class="controls col-md-12">
            {foreach $talentTrees as $tree => $items}
                <div id="tree-{$tree}" class="row" style="display: none;">
                    <div class="col-md-1">{$talentTreeLabels.$tree}</div>
                    {foreach $items as $item}
                        <div class="col-md-1">
                            <a href="/item/1/{$item}" class="item-link" target="_blank">{$item}</a>
                        </div>
                    {/foreach}
                </div>
                <option value="{$tree}">{$talentTreeLabels.$tree}</option>
            {/foreach}
        </div>
    </div>


</fieldset>
{/if}

</form>
<br/>
<div class="alert alert-info">
    Bei Problemen oder Fragen meldet euch Ingame, im <a href="/forum/"><strong>Forum</strong></a> oder per TS3 bei einem GM
</div>
<br/>

<button type="button" class="btn btn-default pull-right jsMigrationSubmit">
    Abschicken
    <i class="glyphicon glyphicon-chevron-right"></i>
</button>

<script>
    $(document).ready(function(){

        var roleSelect = $("#role-select");
        var rolePreview = $("#role-preview");

        roleSelect.on('change', function(){
            selectRole();
        });

        var selectRole = function() {
            var value = roleSelect.find("option:selected").val();

            rolePreview.find(".row").fadeOut();
            rolePreview.find("#tree-"+value).fadeIn();
        };

        selectRole();
    });
</script>
