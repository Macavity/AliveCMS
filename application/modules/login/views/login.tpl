<div id="embedded-login">
{form_open('login', $class)}
  <a id="embedded-close" href="javascript:;"><!--  --></a>
  <h1>Senzaii Login</h1>
  <div>
    <p>
      <label for="accountName" class="label">{lang("username", "login")}</label>
      <input id="login_username" value="{$username}" name="login_username" maxlength="320" type="text" tabindex="1" class="input"/>
    </p>
    <p>
      <label for="password" class="label">{lang("password", "login")}</label>
      <input id="login_password" name="login_password" maxlength="16" type="password" tabindex="2" autocomplete="off" class="input"/>
      <span id="password_error">{$password_error}</span>
    </p>
    <p>
                <span id="remember-me">
                    <label for="login_remember">
                      <input type="checkbox" checked="checked" name="login_remember" id="login_remember"/>
                      {lang("remember_me_short", "login")}
                    </label>
                </span>
      <button class="btn btn-primary" type="submit" data-text="In Bearbeitung…">
        {lang("log_in", "login")}
      </button>
    </p>
    <ul id="help-links">
      {if $has_smtp}
      <li>
        <i class="glyphicon glyphicon-refresh"></i>
        <a href="{$url}password_recovery">{lang("lost_your_password", "login")}</a></li>
      {/if}
      <li>
        <i class="glyphicon glyphicon-user"></i>
        Haben Sie noch keinen Account? <a href="/register/">Jetzt anmelden</a>! </li>
    </ul>
  </form>
</div>

