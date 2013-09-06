
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
  b, strong{ color:white;}
  h2{ padding: 10px 0px; color:#F0E29A;}
</style>

<script type="text/javascript">

  function showCommentEdit(id,content){

    var bug = $("#bug-id").val();

    content = '<form action="/server/bugtracker/bug/'+bug+'/action/change-comment" method="post">\
	<input type="hidden" id="comment-id" name="comment-id" value="'+id+'">\
	<textarea name="new-content" rows="4">'+content+'</textarea>';
    content += '<button type="submit" class="ui-button button2"><span><span>Speichern</span></span></button></form>';
    $("#comment-content-"+id).html(content);

  }

  function editComment(id){

    $.ajax({
      url: "/ajax/search/bug-comment/?term="+id,
      success: function(data){
        showCommentEdit(id,data);
      }
    });
  }

</script>

<input type="hidden" id="bug-id" name="bug-id" value="{$bugId}">
{if $canEditBugs}
  <a href="/bugtracker/admin/edit/{$bugId}" class="ui-button button2"><span><span>Bug-Report bearbeiten</span></span></a>&nbsp;
  <span class="clear"></span><br/>
{/if}
<div class="table">
  <table border="0" cellpadding="5" cellspacing="0" width="800">
    <thead>
    <tr>
      <th colspan="4">
        <span class="sort-tab">Bug #{$bugId} {$typeString} {$title}
          <span class="{$cssState}" style="float: right; text-transform: uppercase;">{$stateLabel}</span>
        </span>
      </th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td width="120"><strong>Erstellt am:</strong></td>
      <td>
        {if $createdDetail == ""}
          {$date}
        {else}
          <span data-tooltip="{$date}">{$createdDetail}</span>
        {/if}
      </td>
      <td width="160"><strong>{if $date2 != ""}Letzte Bearbeitung am:{/if}</strong></td>
      <td>
        {if $changedDetail == ""}
          {$date2}
        {else}
          <span data-tooltip="{$date2}">{$changedDetail}</span>
        {/if}
      </td>
    </tr>
    <tr>
      <td><strong>Status:</strong></td>
      <td>{$stateLabel}</td>
      <td ><strong>Prozent erledigt:</strong></td>
      <td>{$complete}</td>
    </tr>
    <tr>
      <td width="120"><strong>Kategorie:</strong></td>
      <td>{$class}</td>
      <td><strong>Link:</strong></td>
      <td>
        {foreach from=$links item=link}{$link}<br/>{/foreach}
      </td>
    </tr>
    <tr>
      <td colspan="4"><hr /></td>
    </tr>
    <tr>
      <td valign="top"><strong>Beschreibung:</strong></td>
      <td colspan="3">
        {if $bugPoster.details}
          eingereicht von
          <strong><a href="{$url}{$bugPoster.url}" class="wow-class-{$bugPoster.class}" rel="np" target="_blank">{$bugPoster.name}</a></strong>:<br/>
        {/if}
        {$desc}
      </td>
    </tr>
    </tbody>
  </table>
  <hr />
  {if count($similarBugs) > 0}
  <p>Verwandte Bug Reports:</p>
  <ul>
    {foreach from=$similarBugs item=row}
      <li>{$row}</li>
    {/foreach}
  </ul>
  <hr/>
  {/if}

  <div id="page-comments">
    {foreach from=$bugLog key=timestamp item=comment}
      {if $comment.details}
        <div class="comment">
          <div class="avatar portrait-b">
            <div class="avatar-interior">
              {if isset($comment.avatar)}
                <a href="{$comment.char_url}">
                  <img height="64" src="{$comment.avatar}" alt=""/>
                </a>
              {/if}
            </div>
          </div>
          <div class="comment-interior">
            <div class="character-info user">
              <div class="user-name">
                {if $comment.gm}<span class="employee"></span>{/if}
                {if $comment.details}
                  <a href="{$comment.char_url}" class="wow-class-{$comment.char_class}" rel="np" target="_blank"> {$comment.name} </a>
                {else}
                  {$comment.name}
                {/if}
              </div>
              <span class="time">{$comment.date}</span>
            </div>
            <div class="content">
              <span id="comment-content-{$comment.id}">{$comment.text}</span>
              {if $comment.action}
                <br/><span class="action-log">{$comment.action}</span>
              {/if}
            </div>
            <div class="comment-actions">
              {if $comment.canEditThisComment}
                <button type="button" onclick="editComment({$comment.id});" class="reply-link ui-button button2"><span><span>Bearbeiten</span></span></button>
              {/if}
            </div>
          </div>
        </div>
      {else}
        <div class="comment">
          {$comment}
        </div>
      {/if}
    {/foreach}

    {if $activeCharacter.active}
      <div class="new-post">
        <div class="comment">
          <div class="portrait-b">
            <div class="avatar-interior">
              <a href="{$url}{$activeCharacter.url}">
                <img height="64" src="{$url}{$activeCharacter.avatar}" alt=""/>
              </a>
            </div>
          </div>
          <div class="comment-interior">
            <div class="character-info user">
              <div class="user-name">
                <a href="{$url}{$activeCharacter.url}" class="wow-class-{$activeCharacter.class}" rel="np" target="_blank"> {$activeCharacter.name} </a>
              </div>
            </div>
            <div class="content">
              <form action="{$url}/bugtracker/bug/{$bugId}" method="post">
                <input type="hidden" name="action" value="new-comment"/>
                <input type="hidden" name="bug" value="{$bugId}"/>
                {if hasPermission("canEditBugs")}
                  Status ändern:
                  <select name="change-state" id="change-state">
                    {html_options options=$bugStates selected=$state}
                  </select>
                {/if}
                <div class="comment-ta">
                  <textarea id="comment-ta" cols="78" rows="3" name="detail"></textarea>
                </div>
                <div class="action">
                  <div class="submit">
                    <button class="ui-button button1 comment-submit " type="submit" onclick="Cms.Comments.ajaxComment(this, Wiki.postComment);">
                      <span><span>Kommentieren</span></span>
                    </button>
                  </div>
                  <span class="clear"><!-- --></span>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    {/if}

  </div> <!-- /page-comments -->


</div>