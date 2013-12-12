<h3>{l s='Two factor authentication enabled'}</h3>
<form method="POST" action="{$link->getPageLink('authentication', true)}">
    <label for="twoFactor">{l s='Insert the one-time password' mod="LatchPS"}:</label>
    <input type="text" name="twoFactor" id='twoFactor'>
    <input type="hidden" name="email" value="{$email}">
    <input type="hidden" name="passwd" value="{$passwd}">
    <input type="submit" style="padding: 0px 3px 0px 3px;" name="SubmitLogin" id="SubmitLogin" value="{l s='Submit' mod="LatchPS"}">
</form>