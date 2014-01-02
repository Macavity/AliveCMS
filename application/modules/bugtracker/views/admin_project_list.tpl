<script type="text/javascript" src="{$url}application/js/libs/jquery/jquery-ui-1.10.3.custom.min.js"></script>
<link rel="stylesheet" href="{$url}application/js/libs/jquery/css/ui-lightness/jquery-ui-1.10.3.custom.min.css" type="text/css">

<section class="box big" id="main_bugtracker">
    <h2>
        <img src="{$url}application/themes/admin/images/icons/black16x16/ic_grid.png"/>
        Bugtracker Projekte (<div style="display:inline;" id="sidebox_count">{$projectCount}</div>)
    </h2>

	<span>
		<a class="nice_button" href="javascript:void(0)" onClick="Bugtracker.addProject();">Projekt erstellen</a>
	</span>

    <p>Die Projekte können per Drag & Drop in ihrer Reihenfolge verändert werden.</p>

    <div class="form">
        <div id="tabs">
            <ul>
                {foreach from=$projects item=project}
                    <li><a href="#project-tab-{$project.id}">{$project.title}</a></li>
                {/foreach}
            </ul>
            {foreach from=$projects item=project}
                <div id="project-tab-{$project.id}" class="project-list">
                    <a href="{$url}bugtracker/admin_projects/edit/{$project.id}" data-tip="Bearbeiten">
                        <img src="{$url}application/themes/admin/images/icons/black16x16/ic_edit.png" />
                        Hauptprojekt bearbeiten
                    </a>
                    <br/>
                    {if $project.projects}
                        <ul id="sortable-{$project.id}" class="sortable ui-helper-reset">
                            {foreach from=$project.projects item=sub}
                                <li class="ui-state-default">
                                    <label><i class="icon-flag icon"></i> {$sub.title}</label>
                                    <a href="{$url}bugtracker/admin_projects/edit/{$sub.id}" data-tip="Bearbeiten">
                                        <img src="{$url}application/themes/admin/images/icons/black16x16/ic_edit.png" />
                                         Bearbeiten
                                    </a>
                                    {if $sub.projects}
                                        <ul id="sortable-{$sub.id}" class="subsortable">
                                            {foreach from=$sub.projects item=sub2}
                                                <li class="ui-state-default">
                                                    <label><i class="icon-tag icon"></i> {$sub2.title}</label>
                                                    <a href="{$url}bugtracker/admin_projects/edit/{$sub2.id}" data-tip="Bearbeiten">
                                                        <img src="{$url}application/themes/admin/images/icons/black16x16/ic_edit.png" />
                                                        Bearbeiten
                                                    </a>
                                                </li>
                                            {/foreach}
                                        </ul>
                                    {/if}
                                </li>
                            {/foreach}
                        </ul>
                    {/if}
                </div>
            {/foreach}
        </div>
    </div>
    <script>
        $(function() {
            $( "#tabs" ).tabs();
            $( ".sortable, .subsortable" ).sortable().disableSelection();
        });
    </script>

</section>

<section class="box big" id="add_project" style="display:none;">
    <h2><a href='javascript:void(0)' onClick="Bugtracker.addProject()" data-tip="Return to sideboxes">Bugtracker Projekte</a> &rarr; Neues Projekt</h2>
    <div class="form">
        <label for="displayName">Titel</label>
        <input type="text" name="projectTitle" id="projectTitle" />

        <label for="displayName">Beschreibung</label>
        <input type="text" name="projectDesc" id="projectDesc" />

        <label for="displayName">Oberkategorie</label>
        <select id="projectParent" name="projectParent">
            <option value="0">- Keine -</option>
            {foreach from=$projectChoices item=choice key=choiceId}
                <option value="{$choiceId}">{$choice}</option>
            {/foreach}
        </select>


        <input type="button" value="Projekt erstellen" onclick="Bugtracker.createProject(this);" />
    </div>
</section>