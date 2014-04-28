<li class="latch_customers_accounts">
	<a href="{$link->getModuleLink('LatchPS', 'LatchConf')|escape:'html'}" title="Latch">
		<i><img src="{$imagesURL}latch_logo.png" width="26" height="26" class="icon"</></i> 
		<span>
		{if $userPaired }
            {l s='Unpair your Latch account.' mod='LatchPS'}
        {else}
            {l s='Protect your account with Latch.' mod='LatchPS'}
        {/if}
		</span>
    </a>
</li>