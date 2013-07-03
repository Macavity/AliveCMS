<div id="embedded-login">
    {form_open('login', $class)}
        <a id="embedded-close" href="javascript:;"><!--  --></a>
        <h1>ALive Login</h1>
        <div>
            <p>
                <label for="accountName" class="label">Username</label>
                <input id="login_username" value="{$username}" name="login_username" maxlength="320" type="text" tabindex="1" class="input"/>
            </p>
            <p>
                <label for="password" class="label">Passwort</label>
                <input id="login_password" name="login_password" maxlength="16" type="password" tabindex="2" autocomplete="off" class="input"/>
            </p>
            <p>
                <span id="remember-me">
                    <label for="login_remember">
                        <input type="checkbox" checked="checked" name="login_remember" id="login_remember"/>
                        Eingeloggt bleiben
                    </label>
                </span>
                <button class="ui-button button1 " type="submit" data-text="In Bearbeitung…">
                    <span class="button-left"><span class="button-right">Einloggen</span></span>
                </button>
            </p>
        </div>
        <ul id="help-links">
            {if $has_smtp}
            <li class="icon-pass"><a href="{$url}password_recovery">Sie können sich nicht mehr einloggen?</a></li>
            {/if}
            <li class="icon-signup">Haben Sie noch keinen Account? <a href="/register/">Jetzt anmelden</a>! </li>
        </ul>
    </form>
</div>
