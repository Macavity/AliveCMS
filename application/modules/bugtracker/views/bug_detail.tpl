
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

<input type="hidden" id="bug-id" name="bug-id" value="{$bugId}">
{if $canEditBugs}
<div class="row">
    <div class="span2 offset1">
        <a href="/bugtracker/admin/edit/{$bugId}" class="ui-button button2"><span><span>Bug-Report bearbeiten</span></span></a>
    </div>
</div><br>
{/if}


<div class="row">
    <div class="span2 offset1"><strong>Erstellungsdatum</strong></div>
    <div class="span8">
        {$createdDate}{if $createdDetail}, {$createdDetail}{/if}
    </div>
</div>

<div class="row">
    <div class="span2 offset1"><strong>Letzte Änderung</strong></div>
    <div class="span8">
        {$changedDate}{if $changedDetail}, {$changedDetail}{/if}
    </div>
</div><br>

<div class="row">
    <div class="span2 offset1"><strong>Status</strong></div>
    <div class="span8 {$cssState}">{$stateLabel}</div>
</div><br>

<div class="row">
    <div class="span2 offset1"><strong>Priorität</strong></div>
    <div class="span8">
        <i class="icon {$priorityClass}"></i> {$priorityLabel}
    </div>
</div><br>


<div class="row">
    <div class="span2 offset1"><strong>Beschreibung</strong></div>
    <div class="span8">
        {if $bugPoster.details}
            eingereicht von
            <strong><a href="{$url}{$bugPoster.url}" class="wow-class-{$bugPoster.class}" rel="np" target="_blank">{$bugPoster.name}</a></strong>:<br/>
        {/if}
        {$desc}
    </div>
</div><br>

<div class="row">
    <div class="span2 offset1"><strong>Links</strong></div>
    <div class="span8">
        <ul>
            {foreach from=$links item=link}
                <li><a href="{$link.url}" target="_blank">{$link.label}</a></li>
            {/foreach}
        </ul>
    </div>
</div><br>

{if count($similarBugs) > 0}
<div class="row">
    <div class="span2 offset1"><strong>Ähnliche Bug Reports</strong></div>
    <div class="span8">
        <ul>
            {foreach from=$similarBugs item=row}
                <li><a href="{$row.url}" target="_blank">{$row.label}</a></li>
            {/foreach}
        </ul>
    </div>
</div>
{/if}


<div class="table">
  <div id="page-comments">
    {foreach from=$bugLog key=timestamp item=comment}
      {if $comment.posterDetails}
        <div class="comment {if $comment.isStaff}blizzard{/if}">
          <div class="avatar portrait-b">
            <div class="avatar-interior">
              {if isset($comment.avatar)}
                <a href="{$url}{$comment.char_url}">
                  <img height="64" src="{$url}{$comment.avatar}" alt=""/>
                </a>
              {/if}
            </div>
          </div>
          <div class="comment-interior">
            <div class="character-info user">
              <div class="user-name">
                {if $comment.isStaff}<span class="employee"></span>{/if}
                <a href="{$url}{$comment.char_url}" class="wow-class-{$comment.char_class}" rel="np" target="_blank"> {$comment.name} </a>
              </div>
              <span class="time">{$comment.createdDetail}</span>
            </div>
            <div class="content">
              {if $comment.text}
                  <span id="comment-content-{$comment.id}">{$comment.text}</span><br/>
              {/if}
              {foreach from=$comment.action item=actionRow}
                <span class="action-log">{$actionRow}</span><br/>
              {/foreach}
            </div>
            <div class="comment-actions">
                {if $comment.canEditThisComment}
                    <button type="button" data-comment="{$comment.id}" class="reply-link ui-button button2 jsEditComment"><span><span>Bearbeiten</span></span></button>
                {/if}
            </div>
          </div>
        </div>
      {else}
        <div class="comment">
            <div class="comment-interior">
                <div class="character-info user">
                    <div class="user-name">
                        {$comment.name}
                    </div>
                </div>
                <div class="content">
                    {if $comment.text}
                        <span id="comment-content-{$comment.id}">{$comment.text}</span>
                    {/if}
                </div>
            </div>
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
                {form_open('bugtracker/add_comment')}
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
                {form_close()}
            </div>
          </div>
        </div>
      </div>
    {/if}

  </div> <!-- /page-comments -->


</div>