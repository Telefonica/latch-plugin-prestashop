#LATCH INSTALLATION GUIDE FOR PRESTASHOP


##PREREQUISITES
 * Prestashop version 1.5 or later.

 * Curl extensions active in PHP (uncomment **"extension=php_curl.dll"** or"** extension=curl.so"** in Windows or Linux php.ini respectively.

 * To get the **"Application ID"** and **"Secret"**, (fundamental values for integrating Latch in any application), it’s necessary to register a developer account in [Latch's website](https://latch.elevenpaths.com). On the upper right side, click on **"Developer area"**.


##DOWNLOADING THE PRESTASHOP PLUGIN
 * When the account is activated, the user will be able to create applications with Latch and access to developer documentation, including existing SDKs and plugins. The user has to access again to [Developer area](https://latch.elevenpaths.com/www/developerArea), and browse his applications from **"My applications"** section in the side menu.

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
