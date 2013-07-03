<div id="embedded-login">
	<h1>Battle.net</h1>
	<form action="" method="post">
		<a id="embedded-close" href="javascript:;" onclick="updateParent('close')"></a>
		<div>
			<p>
				<label for="accountName" class="label">E-Mail-Adresse</label>
				<input id="accountName" value="" name="accountName" maxlength="320" type="text" tabindex="1" class="input"/>
			</p>
			<p>
				<label for="password" class="label">Passwort</label>
				<input id="password" name="password" maxlength="16" type="password" tabindex="2" autocomplete="off" class="input"/>
			</p>
			<p>
				<span id="remember-me"><label for="persistLogin"><input type="checkbox" checked="checked" name="persistLogin" id="persistLogin"/>
				Eingeloggt bleiben </label>
				</span><input type="hidden" name="app" value="com-sc2"/>
				<button class="ui-button button1 " type="submit" data-text="In Bearbeitung…">
				<span class="button-left"><span class="button-right">Einloggen</span>
				</span>
				</button>
			</p>
		</div>
		<ul id="help-links">
			<li class="icon-pass"><a href="https://eu.battle.net/account/support/login-support.html">Sie können sich nicht mehr einloggen?</a>
			</li>
			<li class="icon-signup">Haben Sie noch keinen Account? <a href="/register/">Jetzt anmelden</a>! </li>
		</ul>
		<script type="text/javascript">
		  {literal}
            $(function() {
                $("#help-links a").click(function() {
                    updateParent('redirect', 'url', this.href);
                    return false;
                });
                $('#accountName').focus();
                updateParent('onload', 'height', $(document).height());
            });
            function reLoadCaptcha(target) {
                target.src = '/login/captcha.jpg?' + new Date().getTime();
            }
            {/literal}
        </script>
	</form>
</div>