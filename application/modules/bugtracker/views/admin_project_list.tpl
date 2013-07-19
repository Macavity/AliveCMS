<script type="text/javascript" src="{$url}application/js/libs/jquery/jquery-ui-1.10.3.custom.min.js"></script>

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
    <ul class="project-list">
      {foreach from=$projects item=project}
        <li>
          <label>{$project.title}</label>
          <a href="{$url}bugtracker/admin_projects/edit/{$project.id}" data-tip="Bearbeiten">
            <img src="{$url}application/themes/admin/images/icons/black16x16/ic_edit.png" />
          </a>

          {if $project.projects}
            <ul class="sub-projects">
              {foreach from=$project.projects item=sub}
                <li>
                  <label>{$sub.title}</label>
                </li>
              {/foreach}
            </ul>
          {/if}

        </li>
      {/foreach}
    </ul>

  </div>
  <script>
    $(function() {
      $( ".project-list" ).sortable();
      $( ".project-list" ).disableSelection();
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