<div id="latch_box"> 
	<div id="latch_image" >
		<img src="{$imagesURL}symbol.png" onclick="{$link->getModuleLink('LatchPS', 'LatchConf')|escape:'html'}"/>
	</div>
	<div id="latch_text">
		<a href="{$link->getModuleLink('LatchPS', 'LatchConf')|escape:'html'}">
                    {$configurationMessage}
                </a>
	</div>
</div>