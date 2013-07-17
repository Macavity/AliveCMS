<section class="box big" id="main_bugtracker">
  <h2>
    <img src="{$url}application/themes/admin/images/icons/black16x16/ic_grid.png"/>
    <a href="{$url}bugtracker/admin_bugs/">Bugtracker Projekte</a> -> {$project.title}
  </h2>

  <div class="table">
    <table>
      <thead>
      <tr>
        <th> <a href="javascript:;" class="sort-link numeric"> <span class="arrow">Status</span> </a> </th>
        <th class="align-center"> <a href="javascript:;" class="sort-link numeric"> <span class="arrow">BugID</span> </a> </th>
        <th class="align-center"> <a href="javascript:;" class="sort-link"> <span class="arrow">Typ</span> </a> </th>
        <th> <a href="javascript:;" class="sort-link"> <span class="arrow">Titel</span> </a> </th>
        <th> <a href="javascript:;" class="sort-link"> <span class="arrow">Letzte Änderung</span> </a> </th>
      </tr>
      </thead>
      <tbody>
      {foreach from=$bugs item=bug}
        <tr class="{cycle values="row1,row2"}">
          <td data-raw="{$bug.bug_state}">&nbsp;</td>
          <td class="align-center" data-raw="{$bug.id}">#{$bug.id}</td>
          <td>{$bug.type_string}</td>
          <td data-raw="{$bug.title}">{$bug.title}</td>
          <td data-raw="{$bug.changedSort}"> <span data-tooltip="Eintragung am {$bug.createdDate}">{$bug.changedDate}</span> </td>
          <td><input type="button" class="showBugDetails" data-bug="{$bug.id}" value="Anzeigen"></td>
        </tr>
      {/foreach}
      </tbody>
    </table>
  </div>
</section>


<script type="text/javascript">
  require([Config.URL + "application/js/libs/jquery/jquery-ui-1.10.3.custom.min.js"], function()
  {
  });
</script>