<div id="donate">
	{if $donate_paypal.use || $donate_paygol.use}
		<div id="donate_select">
			<a href="javascript:void(0)" onClick="Donate.showPayPal(this)" class="nice_active nice_button">PayPal</a>
			<a href="javascript:void(0)" onClick="Donate.showPayGol(this)" class="{if !$donate_paypal.use}nice_active{/if} nice_button">PayGol (SMS)</a>
		</div>

		<div class="ucp_divider"></div>

			{if $donate_paypal.use}
		<section id="paypal_area">
			<form action="https://www{if $donate_paypal.sandbox}.sandbox{/if}.paypal.com/cgi-bin/webscr" method="post" class="page_form">
				<div class="right_image"><img src="{$url}application/images/misc/paypal_logo.png" /></div>
					<input type="hidden" name="cmd" value="_xclick" />
					<input type="hidden" name="business" value="{$donate_paypal.email}" />
					<input type="hidden" name="item_name" value="Donation for {$server_name}" />
					<input type="hidden" name="quantity" value="1" />
					<input type="hidden" name="currency_code" value="{$currency}" />
					<input type="hidden" name="notify_url" value="{$donate_paypal.postback_url}" />
					<input type="hidden" name="return" value="{$donate_paypal.return_url}" />
					<input type="hidden" name="custom" value="{$user_id}" />
				
				{foreach from=$donate_paypal.values item=value key=key}
					<label for="option_{$key}">
						<input type="radio" name="amount" value="{$key}" id="option_{$key}" {if reset($donate_paypal.values) == $value}checked="checked"{/if}/> <b>{$value} Donation points</b> for <b>{$currency_sign}{$key}</b>
					</label>
				{/foreach}

					<input type='submit' value='Pay with PayPal' />
				<div class="clear"></div>
				</form>
		</section>
			{/if}

			{if $donate_paygol.use}
		<section id="paygol_area" {if $donate_paypal.use}style="display:none;"{/if}>
			<form action="http://www.paygol.com/micropayment/paynow_post" method="post" class="page_form">
				<div class="right_image"><img src="{$url}application/images/misc/paygol_logo.png" /></div>
					<input type="hidden" name="pg_custom" value="{$user_id}">
					<input type="hidden" name="pg_serviceid" value="{$donate_paygol.service_id}">
					<input type="hidden" name="pg_currency" value="{$currency}">
					<input type="hidden" name="pg_name" value="Donation for {$server_name}">

				{foreach from=$donate_paygol.values item=value key=key}
					<label for="option_{$key}">
						<input type="radio" name="pg_price" value="{$key}" id="option_{$key}" {if reset($donate_paygol.values) == $value}checked="checked"{/if}/> <b>{$value} Donation points</b> for <b>{$currency_sign}{$key}</b>
					</label>
				{/foreach}
					<input type="hidden" name="pg_return_url" value="{$donate_paygol.return_url}">
					<input type="hidden" name="pg_cancel_url" value="{$donate_paygol.cancel_url}">
					<input type='submit' value='Pay with SMS' />
				<div class="clear"></div>
				</form>
		</section>
			{/if}
	{else}
		Please configure at least one payment option.
	{/if}
</div>