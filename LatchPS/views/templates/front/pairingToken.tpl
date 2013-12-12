<h3>{l s='Type your pairing token' mod='LatchPS'}</h3>
<form method="POST" action="{$link->getModuleLink('LatchPS', 'PairingOperations')|escape:'html'}">
    <input type="text" class="large-input" name="pairingToken" placeholder="{l s='Type your pairing token' mod='LatchPS'}">
    <input type="hidden" name="latchCSRFToken" value="{$latchCSRFToken}">
    <input type="submit" value="{l s='Pair account' mod='LatchPS'}" class="pairing_token_button">
</form>

<div class="latch-documentation">
    <h4>{l s='How to enable Latch for your account?' mod='LatchPS'}</h4>
    <p>
        {l s='You first need to pair it to your Latch account, in the same way you pair your phone with your car Bluetooth system the first time you use it' mod='LatchPS'}.
    </p>
</div>

<div class="info-container">
    <div class="info-section clear-left pull-left">
        <img src="{$imagesURL}tutorial/pair_01.jpg" class="thumbnail" />
        <p>{l s='First, launch the pairing process from the service provider website. You will be asked to introduce a pairing code' mod='LatchPS'}.</p>
    </div>
    <div class="info-section pull-right">
        <img src="{$imagesURL}tutorial/how_02.jpg" class="thumbnail" />
        <p>{l s='Sign into the Latch app and on the main screen tap Add Service' mod='LatchPS'}.</p>
    </div>
</div>
<div class="info-container">
    <div class="info-section clear-left pull-left">
        <img src="{$imagesURL}tutorial/pair_03.jpg" class="thumbnail" />
        <p>{l s='On the Add Service screen tap Generate a pairing code' mod='LatchPS'}.</p>
    </div>
    <div class="info-section pull-right">
        <img src="{$imagesURL}tutorial/pair_04.jpg" class="thumbnail" />
        <p>{l s='Enter the code displayed on your app screen on the website of the service you are pairing' mod='LatchPS'}.</p>
    </div>
</div>