<section class="box big" id="bugtracker_edit">
    <h2>Projekt bearbeiten</h2>

    <div class="form">
        <label for="displayName">Titel</label>
        <input type="text" name="projectTitle" id="projectTitle" value="{htmlspecialchars($project.title)}"/>

        <label for="page">Beschreibung</label>
        <input type="text" name="projectDesc" id="projectDesc" value="{htmlspecialchars($project.description)}" class="input-xxlarge" />

        <label for="page">Oberkategorie</label>
        <select id="projectParent" name="projectParent">
            <option value="0">- Keine -</option>
            {foreach from=$projectChoices item=choice key=choiceId}
                <option value="{$choiceId}"{if $choiceId == $project.parent} selected="selected"{/if}>{$choice}</option>
            {/foreach}
        </select>


        <input type="button" value="Speichern" onclick="Bugtracker.saveProject({$project.id});" />
    </div>
</section>

<script>
    require([Config.URL + "application/themes/admin/js/mli.js"], function()
    {
        new MultiLanguageInput($("#displayName"));
    });
</script>