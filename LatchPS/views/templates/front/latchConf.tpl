{if isset($confirmation) && $confirmation}
    <p class="success">
        {l s='Your Latch account settings have been succesfully updated.' mod='LatchPS'}
    </p>
{/if}
{if isset($latchError) && $latchError}
    <p class="error">
        {l s='Error updating the status of your Latch account.' mod='LatchPS'}
    </p>
{/if}

{if $isPaired}
    <h3>{l s='Your account is protected with Latch'  mod='LatchPS'}</h3>
    <p>
        {l s='To stop using Latch to add an extra security to this account, you just have to unpair your account. After unpairing, this service will be removed from your Latch mobile app. To proceed, press the button below' mod='LatchPS'}.
    </p>
    <form class="std" method="POST" action="{$link->getModuleLink('LatchPS', 'PairingOperations')|escape:'html'}">
        <input type="hidden" name="latchPaired" value="no" autocomplete="off">
        <input type="hidden" name="latchCSRFToken" value="{$latchCSRFToken}">
        <button type="submit" class='unpair_button'>{l s='Unpair your Latch account'  mod='LatchPS'}</button>
    </form>
{else}
    <h3>{l s='Your account is not protected yet'  mod='LatchPS'}</h3>
    <form class="std" method="POST" action="{$link->getModuleLink('LatchPS', 'PairingOperations')|escape:'html'}">
        <input type="hidden" name="latchPaired" value="yes" autocomplete="off">
        <input type="hidden" name="latchCSRFToken" value="{$latchCSRFToken}">
        <input type="submit" value="{l s='Protect your account with Latch' mod='LatchPS'}" class="latch_configuration">
    </form>
    <div class="latch-documentation">
        <h4>{l s='What is Latch?' mod='LatchPS'}</h4>
        <p>
            {l s='Would you like to have your credit card or online bank account active just when you use it to reduce the time it is exposed to fraudulent use? Do you want to switch off your social network accounts when you are offline to prevent any unauthorized use?' mod='LatchPS'}
        </p>
        <p>
            {l s='Do it with Latch now! It also lets you handily schedule automatic downtimes, for instance at nights or when on vacation, to systematically secure your accounts in an unattended way.' mod='LatchPS'}
        </p>
        <p>
            {l s='The Latch monitor lets you track the access to your paired accounts and shows you an alert any time there´s an attempt to access one of your locked accounts.' mod='LatchPS'}
        </p>
    </div>
    <div class="info-container">
        <div class="info-section pull-left">
            <img src="{$imagesURL}description/img1-large.jpg" class="thumbnail" />
            <h3>{l s='Get back control of your digital identity'  mod='LatchPS'}</h3>
            <p>{l s='Switch off your digital accounts when you are not using them to prevent any unauthorized use' mod='LatchPS'}.</p>
        </div>
        <div class="info-section pull-right">
            <img src="{$imagesURL}description/img2-large.jpg" class="thumbnail" />
            <h3>{l s='Get an extra security level on your accounts' mod='LatchPS'}</h3>
            <p>{l s='Pair your Latch-enabled digital services from their web site' mod='LatchPS'}.</p>
        </div>
    </div>
    <div class="info-container">
        <div class="info-section clear-left pull-left">
            <img src="{$imagesURL}description/img4-large.jpg" class="thumbnail" />
            <h3>{l s='OFF-time automatic schedule' mod='LatchPS'}</h3>
            <p>{l s='Easily schedule the automatic disconnection of your accounts at night or custom times and dates' mod='LatchPS'}.</p>
        </div>
        <div class="info-section pull-right">
            <img src="{$imagesURL}description/img5-large.jpg" class="thumbnail" />
            <h3>{l s='Monitor the access to your accounts' mod='LatchPS'}</h3>
            <p>{l s='You can monitor the attempts to access your accounts when they are latched and report back to your service provider' mod='LatchPS'}.</p>
        </div>
    </div>
{/if}