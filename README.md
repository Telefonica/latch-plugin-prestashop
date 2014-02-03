### LATCH PRESTASHOP PLUGIN -- INSTALLATION GUIDE ###


#### PREREQUISITES ####

* Prestashop version 1.5 or later.

* Curl extensions active in PHP (uncomment "extension=php_curl.dll" or" extension=curl.so" in Windows or Linux php.ini respectively.

 * To get the "Application ID" and "Secret", (fundamental values for integrating Latch in any application), it’s necessary to register a developer account in Latch's website: https://latch.elevenpaths.com. On the upper right side, click on "Developer area".


#### INSTALLING THE MODULE IN PRESTASHOP ####

1. Once the administrator has downloaded the plugin, it has to be added as a module in its administration panel in Prestashop. Click on "Modules", where the Latch module will be uploaded. In the upper right of the screen, "Add a new module" will be available. A form to upload "latch.zip" (previously downloaded from GitHub) will be shown.

2. If everything is ok, a notification bar will appear: "Module downloaded successfully". The module is now uploaded but not installed. To install it, the administrator has to search for it, going to the search menu in the down left part of the screen.

3. After installation, a new webpage to configure it, is opened. The Previously generated "Application ID" and "Secret" values have to be introduced. Press "Save" to store the values and start using Latch.

#### CONFIGURING THE INSTALLED MODULE ####

1. To modify its position up or down in the screen, use "Configures hooks". "LiveEdit" feature may be used to place the module by dragging and dropping it directly in place.

2. Before configuring where the modules will be placed, it’s necessary to login in the shop with a "Front Office" user, drag and drop Latch module, and finllay save the new location for the module.

