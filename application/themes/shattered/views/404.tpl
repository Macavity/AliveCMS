<style type="text/css">
/* error page */
#error-index #content .content-top { background: url("{$theme_path}images/layout/error-bg.jpg") no-repeat; }
.server-error { width: 375px; margin: 0 auto; padding-top: 50px; text-align: center; font-size: 18px; min-height: 900px; }
.server-error h2 { font-size:125px; }
.server-error h2.http { font-size: 85px; }
.server-error h3 { font-size:35px; margin-bottom: 50px; }
.server-error em { font-size: 12px; }
</style>

<div class="server-error">
    <h2 class="http">{$headline}</h2>
    {$content}
    <!-- http : 404 -->
</div>