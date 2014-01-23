
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
<div class="bugtracker-actions row">
    <div class="col-md-12">
        <a href="/bugtracker/edit/{$bugId}" class="btn btn-default btn-sm">Bug-Report bearbeiten</a>
    </div>
</div><br>
{/if}


<div class="row">
    <div class="col-md-2 col-md-offset-1"><strong>Erstellungsdatum</strong></div>
    <div class="col-md-9">
        {$createdDate}{if $createdDetail}, {$createdDetail}{/if}
    </div>
</div>

<div class="row">
    <div class="col-md-2 col-md-offset-1"><strong>Letzte Änderung</strong></div>
    <div class="col-md-9">
        {$changedDate}{if $changedDetail}, {$changedDetail}{/if}
    </div>
</div><br>

<div class="row">
    <div class="col-md-2 col-md-offset-1"><strong>Status</strong></div>
    <div class="col-md-9 {$cssState}">{$stateLabel}</div>
</div><br>

<div class="row">
    <div class="col-md-2 col-md-offset-1"><strong>Priorität</strong></div>
    <div class="col-md-9">
        <i class="icon {$priorityClass}"></i> {$priorityLabel}
    </div>
</div><br>


<div class="row">
    <div class="col-md-2 col-md-offset-1"><strong>Beschreibung</strong></div>
    <div class="col-md-9">
        {if $bugPoster.details}
            eingereicht von
            <strong><a href="{$url}{$bugPoster.url}" class="wow-class-{$bugPoster.class}" rel="np" target="_blank">{$bugPoster.name}</a></strong>:<br/>
        {/if}
        {$desc}
    </div>
</div><br>

<div class="row">
    <div class="col-md-2 col-md-offset-1"><strong>Links</strong></div>
    <div class="col-md-9">
        <ul>
            {foreach from=$links item=link}
                <li><a href="{$link.url}" target="_blank">{$link.label}</a></li>
            {/foreach}
        </ul>
    </div>
</div><br>

{if $showFixBugShit}
  <div class="alert alert-info col-md-offset-1 col-md-9">F.I.X.B.U.G.S.H.I.T. Einsatzfähig</div>

  <div class="row">
    <div class="col-md-2 col-md-offset-1"><strong>Quests</strong></div>
    <div class="col-md-9">
      <table class="table">
        {foreach $fbsQuests as $quest}
          <tr>
            <td>{$quest.id}</td>
            <td>
              {if $quest.isAutocomplete}
                <span class="workaround">Autocomplete aktiv</span>
              {else}
                Autocomplete Inaktiv
              {/if}
            </td>
            <td>{$quest.title}</td>
          </tr>
          {foreachelse}
          <tr>
            <td>Damit F.I.X.B.U.G.S.H.I.T. eingesetzt werden kann muss ein "openwow"-Quest-Link eingetragen sein. Es konnte kein Link oder kein passendes Quest gefunden werden.</td>
          </tr>
        {/foreach}
      </table>
    </div>
  </div><br>
{/if}

{if count($similarBugs) > 0}
<div class="row">
    <div class="col-md-2 col-md-offset-1"><strong>Ähnliche Bug Reports</strong></div>
    <div class="col-md-9">
        <ul>
            {foreach from=$similarBugs item=row}
                <li><a href="{$row.url}" target="_blank">{$row.label}</a></li>
            {/foreach}
        </ul>
    </div>
</div>
{/if}

<div class="row">
    <div id="page-comments" class="col-md-10 col-md-offset-1">
        {foreach from=$bugLog key=timestamp item=comment}
            {if $comment.posterDetails}
                <div class="bug-comment row {if $comment.isStaff}blizzard{/if}">
                    <div class="portrait-b col-md-2">
                        <div class="avatar-interior">
                            {if isset($comment.avatar)}
                                <a href="{$url}{$comment.char_url}">
                                    <img height="64" src="{$url}{$comment.avatar}" alt=""/>
                                </a>
                            {/if}
                        </div>
                    </div>
                    <div class="comment-interior col-md-10">
                        <div class="character-info user row">
                            <div class="user-name col-md-12">
                                {if $comment.isStaff}<span class="employee"></span>{/if}
                                <a href="{$url}{$comment.char_url}" class="wow-class-{$comment.char_class}" rel="np" target="_blank"> {$comment.name} </a>
                            </div>
                            <div class="time  col-md-12">{$comment.createdDetail}</div>
                        </div>
                        <div class="content row">
                            {if $comment.text}
                                <div id="comment-content-{$comment.id}" class="col-md-12">{$comment.text}</div><br/>
                            {/if}
                            {foreach from=$comment.action item=actionRow}
                                <div class="action-log col-md-12">{$actionRow}</div><br/>
                            {/foreach}
                        </div>
                        <div class="comment-actions">
                            {if $comment.canEditThisComment}
                                <button type="button" data-comment="{$comment.id}" class="btn btn-default btn-sm jsEditComment">
                                    Bearbeiten
                                </button>
                            {/if}
                        </div>
                    </div>
                </div>
            {else}
                <div class="bug-comment row">
                    <div class="comment-interior">
                        <div class="character-info user col-md-12">
                            <div class="user-name">
                                {$comment.name}
                            </div>
                        </div>
                        <div class="content col-md-12">
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
                <div class="bug-comment row">
                    <div class="portrait-b col-md-2">
                        <div class="avatar-interior">
                            <a href="{$url}{$activeCharacter.url}">
                                <img height="64" src="{$url}{$activeCharacter.avatar}" alt=""/>
                            </a>
                        </div>
                    </div>
                    <div class="comment-interior col-md-10">
                        <div class="character-info user row">
                            <div class="user-name col-md-12">
                                <a href="{$url}{$activeCharacter.url}" class="wow-class-{$activeCharacter.class}" rel="np" target="_blank"> {$activeCharacter.name} </a>
                            </div>
                        </div>
                        <div class="content row">
                            <div class="col-md-12">
                            {form_open('bugtracker/add_comment', $bugtrackerFormAttributes)}
                                <input type="hidden" name="bug" value="{$bugId}"/>
                                {if hasPermission("canEditBugs")}
                                <div class="row">
                                    <div class="col-md-12">
                                        Status ändern:
                                    </div>
                                    <div class="col-md-12">
                                        <select name="change-state" id="change-state" class="form-control">
                                            {html_options options=$bugStates selected=$state}
                                        </select>
                                    </div>
                                </div>
                                {/if}
                                <div class="row">
                                    <div class="col-md-12">
                                        <textarea id="comment-ta" cols="78" rows="3" name="detail" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="action">
                                    <div class="submit">
                                        <button class="btn btn-default btn-sm comment-submit" type="submit" onclick="Cms.Comments.ajaxComment(this, Wiki.postComment);">
                                            Kommentieren
                                        </button>
                                    </div>
                                    <span class="clear"><!-- --></span>
                                </div>
                            {form_close()}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        {/if}
    </div>
</div>