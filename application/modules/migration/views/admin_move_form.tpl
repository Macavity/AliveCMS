<section class="box big" id="account_articles">
  <h2>
    <img src="{$url}application/themes/admin/images/icons/black16x16/ic_users.png"/>
    Charakter verschieben
  </h2>

  {if $message}
    {if $message.type == "info"}<div class="alert alert-info">{$message.message}</div>{/if}
    {if $message.type == "error"}<div class="alert alert-danger">{$message.message}</div>{/if}
    {if $message.type == "success"}<div class="alert alert-success">{$message.message}</div>{/if}
  {/if}

  {form_open('migration/admin/move/')}

    <label>Realm</label>
    {foreach $realms as $realm}
      <input type="radio" id="move_realm_{$realm->getId()}" name="move_realm" value="{$realm->getId()}" style="margin-top: -3px;"> {$realm->getName()}
    {/foreach}
    <br/>

    <label>Charakter (Name oder ID)</label>
    <input type="text" name="move_char" id="move_char" /><br/>

    <label>Zielaccount (Name oder ID)</label>
    <input type="text" name="move_destination" id="move_destination" /><br/>

    <input type="submit" value="Verschieben" />
  </form>

</section>