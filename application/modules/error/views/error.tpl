{if isset($is404) && $is404}
  <style type="text/css">
    /* error page */
    #content .content-top { background: url("http://forum.wow-alive.de/static-wow/images/layout/error-bg.jpg") no-repeat; }
  </style>
  <div class="server-error">
    <h2 class="http">Vier,<br> Null, Vier.</h2>
    <h3>Seite nicht gefunden</h3>
    <p>Hier war mal eine<br> <strong>SEITE</strong><br>.<br>Die ist nu wech.<br><br><em>(Wir haben die Seite gewarnt: Geh nicht allein in den Wald! Das hat sie jetzt davon!)</em></p>
    <!-- http : 404 -->
  </div>

{else}
	<center style='margin:10px;font-weight:bold;'>{$errorMessage}</center>
{/if}