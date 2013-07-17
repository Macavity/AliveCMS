<section class="box big" id="main_bugtracker">
  <h2>
    <img src="{$url}application/themes/admin/images/icons/black16x16/ic_grid.png"/>
    Bugtracker Projekte (<div style="display:inline;" id="sidebox_count">{$projectCount}</div>)
  </h2>

  <div class="table form">
    <table width="100%">
      {foreach from=$projects item=project}
        <tr>
          <td>{$project.title}</td>
          <td>
            Tickets: {$project.open_tickets} offen (Gesamtzahl: {$project.all_tickets})
          </td>
          <td>
            <!--<div id="progressbar_{$project.id}"></div>-->
          </td>
          <td>
            <a href="{$url}bugtracker/admin/bug_list/{$project.id}/open" class="nice_button">Offene anzeigen</a>
          </td>
          <td>
            <a href="{$url}bugtracker/admin/bug_list/{$project.id}/all" class="nice_button">Alle anzeigen</a>
          </td>
        </tr>
      {/foreach}
    </table>
  </div>
</section>

<script src="{$url}application/js/libs/jquery/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
<script type="text/javascript">
  $(document).ready(function(){
    /*
    {foreach from=$projects item=project}
      $("#progressbar_{$project.id}").progressbar({ value: {$project.percentage} });
    {/foreach}
    */
  });
</script>