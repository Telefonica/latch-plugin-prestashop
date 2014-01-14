<div id="latch_box"> 
	<div id="latch_image" >
		<img src="{$imagesURL}symbol.png" onclick="{$link->getModuleLink('LatchPS', 'LatchConf')|escape:'html'}"/>
	</div>
	<div id="latch_text">
		<a href="{$link->getModuleLink('LatchPS', 'LatchConf')|escape:'html'}">
                    {if $userPaired }
                        {l s='Unpair your Latch account.' mod='LatchPS'}
                    {else}
                        {l s='Protect your account with Latch.' mod='LatchPS'}
                    {/if}
                </a>
	</div>
</div>