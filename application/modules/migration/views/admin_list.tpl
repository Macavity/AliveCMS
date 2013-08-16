<style>
    .table tr td {
        -o-text-overflow: ellipsis;   /* Opera */
        text-overflow:    ellipsis;   /* IE, Safari (WebKit) */
        overflow:hidden;              /* don't show excess chars */
        white-space:nowrap;           /* force single line */
    }
    .w110 {
        width: 110px;
        max-width: 110px;
    }
    .w80 {
        width: 80px;
    }
    .w50 {
        width: 50px;
    }
</style>
<section class="box big" id="migration_list">

    <h2>
        <i class="icon icon-tasks"></i>
        Transferliste
    </h2>

    <div class="alert alert-info">
        Der Spieler sollte euch ingame mitteilen k&ouml;nnen welche ID er bekommen hat!
    </div>

    <div class="wiki">
        <div class="related">
            <span class="clear"><!-- --></span>
            <div class="related-content" id="related-migrations">
                <div class="filters inline">
                    <div class="keyword">
                        <input id="filter-name-loot" type="text" class="input filter-name" data-filter="row" maxlength="25" title="Filter..." value="Filter..." />
                    </div>
                    <div class="filter-tabs">
                        <a href="javascript:;" data-filter="column" data-column="0" data-value="" data-name="type" class="tab-active"> Alle </a>
                        <a href="javascript:;" data-filter="column" data-column="0" data-name="type" data-value="{$state_open}"> Offen </a>
                        <a href="javascript:;" data-filter="column" data-column="0" data-name="type" data-value="{$state_inprogress}"> In Bearbeitung </a>
                        <a href="javascript:;" data-filter="column" data-column="0" data-name="type" data-value="{$state_done}"> Erledigt </a>
                        <a href="javascript:;" data-filter="column" data-column="0" data-name="type" data-value="{$state_declined}"> Abgewiesen </a>
                    </div>
                    <span class="clear"><!-- --></span>
                </div>
                <div class="data-options-top">
                    <div class="table-options data-options ">
                        <div class="option">
                            <ul class="ui-pagination"></ul>
                        </div>
                        Zeige <strong class="results-start">1</strong>–<strong class="results-end">100</strong> von <strong class="results-total">{$count}</strong> Ergebnissen <span class="clear"><!-- --></span>
                    </div>
                </div>
                <table class="table full-width">
                    <thead>
                    <tr>
                        <th><a href="javascript:;" class="sort-link"><span class="arrow">S</span></a></th>
                        <th class="align-center"> <a href="javascript:;" class="sort-link numeric default"><span class="arrow">#</span></a></th>
                        <th class="align-center"> <a href="javascript:;" class="sort-link numeric"> <span class="arrow">Account</span></a></th>
                        <th class="align-center"> <a href="javascript:;" class="sort-link"> <span class="arrow">Charakter</span></a></th>
                        <th><a href="javascript:;" class="sort-link"><span class="arrow">Server</span></a></th>
                        <th><a href="javascript:;" class="sort-link numeric"><span class="arrow">Datum</span></a></th>
                        <th><a href="javascript:;" class="default"><span>GM</span></a></th>
                    </tr>
                    </thead>
                    <tbody>
                    {foreach from=$migrations item=transfer}
                    <tr class="{cycle values="row1,row2"} {$transfer.classes}">
                        <td class="w20" data-raw="{$transfer.status}">&nbsp;</td>
                        <td class="w50" data-raw="{$transfer.id}">
                            <a href="{$url}migration/admin/detail/{$transfer.id}" target="_blank">{$transfer.id}</a>
                        </td>
                        <td class="w80">{$transfer.account_id}</td>
                        <td class="w110">{$transfer.character_name}</td>
                        <td class="w110">{$transfer.server_name}</td>
                        <td class="w110">{$transfer.date}</td>
                        <td class="w110">{$transfer.message}</td>
                    </tr>
                    {/foreach}
                    {$cached_rows}
                    </tbody>
                </table>
                <div class="data-options-bottom">
                    <div class="table-options data-options">
                        <div class="option">
                            <ul class="ui-pagination">
                            </ul>
                        </div>
                        Zeige <strong class="results-start">1</strong>–<strong class="results-end">100</strong> von <strong class="results-total">{$count}</strong> Ergebnissen
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
<script type="text/javascript">
    require(['/application/js/static'], function(){
        require([
            'controller/AdminController',
            'tooltip'
        ],
                function (AdminController, Tooltip) {
                    $(function () {
                        debug.debug("js/migration_admin");

                        var controller = new AdminController();

                        var totalResults = $("#totalResults").val();

                        controller.initWiki('loot', {
                            paging: true,
                            results: 100,
                            totalResults: {$count},
                            column: 1,
                            method: 'numeric',
                            type: 'desc'
                        });

                    });
                });
    });
</script>