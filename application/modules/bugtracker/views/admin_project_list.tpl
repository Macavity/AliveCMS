<section class="box big" id="main_bugtracker">
  <h2>
    <img src="{$url}application/themes/admin/images/icons/black16x16/ic_grid.png"/>
    Bugtracker Projekte (<div style="display:inline;" id="sidebox_count">{$projectCount}</div>)
  </h2>

	<span>
		<a class="nice_button" href="javascript:void(0)" onClick="Bugtracker.addProject();">Projekt erstellen</a>
	</span>

  <div class="form">
    <table width="100%">
      {foreach from=$projects item=project}
        <tr>
          <td>{$project.title}</td>
          <td>
            Tickets: {$project.done_tickets} offen (Gesamtzahl: {$project.all_tickets})
          </td>
          <td width="45">
            <a href="javascript:void(0)" onClick="Bugtracker.moveProject('up', {$project.id}, this)" data-tip="Move up">
              <img src="{$url}application/themes/admin/images/icons/black16x16/ic_up.png" />
            </a>
            <a href="javascript:void(0)" onClick="Bugtracker.moveProject('down', {$project.id}, this)" data-tip="Move down">
              <img src="{$url}application/themes/admin/images/icons/black16x16/ic_down.png" />
            </a>
          </td>
          <td width="60" style="text-align:right;">
            <a href="{$url}bugtracker/admin_projects/edit/{$project.id}" data-tip="Bearbeiten">
              <img src="{$url}application/themes/admin/images/icons/black16x16/ic_edit.png" /></a>&nbsp;
            <!--
            <a href="javascript:void(0)" onClick="Bugtracker.removeProject({$project.id}, this)" data-tip="Löschen">
              <img src="{$url}application/themes/admin/images/icons/black16x16/ic_minus.png" /></a>
            -->
          </td>
        </tr>
      {/foreach}
    </table>

  </div>

</section>

<section class="box big" id="add_project" style="display:none;">
  <h2><a href='javascript:void(0)' onClick="Bugtracker.addProject()" data-tip="Return to sideboxes">Bugtracker Projekte</a> &rarr; Neues Projekt</h2>
  <div class="form">
    <label for="displayName">Titel</label>
    <input type="text" name="projectTitle" id="projectTitle" />

    <label for="displayName">Beschreibung</label>
    <input type="text" name="projectDesc" id="projectDesc" />
    <input type="button" value="Projekt erstellen" onclick="Bugtracker.createProject(this);" />
  </div>
</section>