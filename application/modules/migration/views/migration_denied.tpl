{if $reason == "limit"}
    <div class="alert alert-danger">
        Du kannst keine Transfere erstellen, weil du bereits das Maximum an Transferen erreicht hast.
    </div>
{elseif $reason == "cash"}
    <div class="alert alert-danger">
        Du kannst keine Transfere erstellen, weil du nicht über die benötigten {$cash_needed} Votepunkte verfügst.
    </div>
{else}
    <div class="alert alert-danger">
        Du kannst keine Transfere erstellen, deine Benutzergruppe hat nicht die benötigten Rechte dafür.
    </div>
{/if}