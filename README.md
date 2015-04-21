#LATCH INSTALLATION GUIDE FOR PRESTASHOP


##PREREQUISITES 
 * Prestashop versions 1.5 to 1.6.0.9 - For newer versions some features like the OTP may not work properly.

 * Curl extensions active in PHP (uncomment **"extension=php_curl.dll"** or"** extension=curl.so"** in Windows or Linux php.ini respectively. 

 * To get the **"Application ID"** and **"Secret"**, (fundamental values for integrating Latch in any application), it’s necessary to register a developer account in [Latch's website](https://latch.elevenpaths.com "https://latch.elevenpaths.com"). On the upper right side, click on **"Developer area"**.

 
##DOWNLOADING THE PRESTASHOP PLUGIN
 * When the account is activated, the user will be able to create applications with Latch and access to developer documentation, including existing SDKs and plugins. The user has to access again to [Developer area](https://latch.elevenpaths.com/www/developerArea "https://latch.elevenpaths.com/www/developerArea"), and browse his applications from **"My applications"** section in the side menu.

* When creating an application, two fundamental fields are shown: **"Application ID"** and **"Secret"**, keep these for later use. There are some additional parameters to be chosen, as the application icon (that will be shown in Latch) and whether the application will support OTP  (One Time Password) or not.

* From the side menu in developers area, the user can access the **"Documentation & SDKs"** section. Inside it, there is a **"SDKs and Plugins"** menu. Links to different SDKs in different programming languages and plugins developed so far, are shown.


##INSTALLING THE MODULE IN PRESTASHOP
* Once the administrator has downloaded the plugin, it has to be added as a module in its administration panel in Prestashop. Click on **"Modules"**, where the Latch module will be uploaded. In the upper right of the screen, **"Add a new module"** will be available. A form to upload **"latch.zip"** (previously downloaded from GitHub) will be shown.

* If everything is ok, a notification bar will appear: **"Module downloaded successfully"**. The module is now uploaded but not installed. To install it, the administrator has to search for it, going to the search menu in the down left part of the screen. 

* After installation, a new webpage to configure it, is opened. The Previously generated **"Application ID"** and **"Secret"** values have to be introduced. Press **"Save"** to store the values and start using Latch.


##CONFIGURING THE INSTALLED MODULE
* To modify its position up or down in the screen, use **"Configures hooks"**. **"LiveEdit"** feature may be used to place the module by dragging and dropping it directly in place. 

* Before configuring where the modules will be placed, it’s necessary to login in the shop with a **"Front Office"** user, drag and drop Latch module, and finally save the new location for the module.


##UNINSTALLING THE MODULE IN PRESTASHOP
* Go to **"Modules"**. Module "Latch" may be searched from the search box on the left. Press on "Uninstall" on the right side, and accept confirmation message.


##USE OF LATCH MODULE FOR THE USERS
**Latch does not affect in any case or in any way the usual operations with an account. It just allows or denies actions over it, acting as an independent extra layer of security that, once removed or without effect, will have no effect over the accounts, that will remain with its original state.**


###Pairing a user in Prestashop
The user needs the Latch application installed on the phone, and follow these steps:

* **Step 1:** Logged in your own account, go to **"My Account"**, and click on the new button **"Protect your account with Latch"** twice.

* **Step 2:** From the Latch app on the phone, the user has to generate the token, pressing on **“Add a new service"** at the bottom of the application, and pressing **"Generate new code"** will take the user to a new screen where the pairing code will be displayed.

* **Step 3:** The user has to type the characters generated on the phone into the text box displayed on the web page. Click on **"Pair account"** button.

* **Step 4:** Now the user may lock and unlock the account, preventing any unauthorized access.


###Unpairing a user in Prestashop
* From your Prestashop account tap the **“Unpair your Latch account”** button, and in a new window tap the other button with the same. He will receive a notification indicating that the service has been unpaired.


##RESOURCES
- You can access Latch´s use and installation manuals, together with a list of all available plugins here: [https://latch.elevenpaths.com/www/developers/resources](https://latch.elevenpaths.com/www/developers/resources)

- Further information on de Latch´s API can be found here: [https://latch.elevenpaths.com/www/developers/doc_api](https://latch.elevenpaths.com/www/developers/doc_api)

- For more information about how to use Latch and testing more free features, please refer to the user guide in Spanish and English:
	1. [English version](https://latch.elevenpaths.com/www/public/documents/howToUseLatchNevele_EN.pdf)
	1. [Spanish version](https://latch.elevenpaths.com/www/public/documents/howToUseLatchNevele_ES.pdf)
