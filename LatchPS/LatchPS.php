<?php

/*
Latch Prestashop module - Integrates Latch into the Prestashop authentication process.
Copyright (C) 2013 Eleven Paths

This library is free software; you can redistribute it and/or
modify it under the terms of the GNU Lesser General Public
License as published by the Free Software Foundation; either
version 2.1 of the License, or (at your option) any later version.

This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public
License along with this library; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!defined('_PS_VERSION_'))
    exit;

define('LATCH_PLUGIN_NAME', 'LatchPS');

require_once _PS_MODULE_DIR_ . LATCH_PLUGIN_NAME . '/LatchWrapper.php';

class LatchPS extends Module {

    public static $MY_ACCOUNT_CONTROLLER = 'my-account';
    public static $LATCH_CONTROLLER = 'LatchConf';
    public static $PLUGIN_VERSION = '1.0.0';
    public static $AUTHOR = 'Eleven Paths';
    
    static $CSS_FILE = 'latch.css';

    function __construct() {
        $this->name = LATCH_PLUGIN_NAME;
        $this->tab = 'authentication';
        $this->version = self::$PLUGIN_VERSION;
        $this->author = self::$AUTHOR;
        $this->need_instance = 0;
        parent::__construct();
        $this->displayName = $this->l('Latch');
        $this->description = $this->l('Latch Prestashop integration.');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall the module?');
        $this->initContext();
    }

    // Retrocompatibility 1.4/1.5
    private function initContext() {
        if (class_exists('Context')) {
            $this->context = Context::getContext();
        } else {
            global $smarty, $cookie;
            $this->context = new StdClass();
            $this->context->smarty = $smarty;
            $this->context->cookie = $cookie;
        }
    }

    public function install() {
        if (parent::install()) {
            return $this->registerHook('authentication') &&
                    $this->registerHook('customerAccount') &&
                    $this->registerHook('displayHeader') &&
                    !LatchData::createTable();
        }
    }

    public function uninstall() {
        if (parent::uninstall()) {
            return Configuration::deleteByName('latch_appId') &&
                    Configuration::deleteByName('latch_appSecret') &&
                    !LatchData::DropTable();
        }
        return false;
    }

    /*
     * Displays the visual component to link to the configuration page.
     */
    public function hookCustomerAccount() {
        if ($this->isUserLogged() && $this->currentControllerIs(self::$MY_ACCOUNT_CONTROLLER)) {
            $customerId = $this->context->cookie->id_customer;
            $customerData = LatchData::getLatchDataFromCustomerId($customerId);
            $this->context->smarty->assign(array(
                'userPaired' => !empty($customerData->id_latch),
                'imagesURL' => __PS_BASE_URI__ . "modules/" . LATCH_PLUGIN_NAME . "/img/"
            ));
            return $this->display(__FILE__, 'accountBlock.tpl');
        } else {
            return false;
        }
    }
    
    public function hookDisplayHeader() {
        if ($this->isUserLogged() && $this->currentControllerIs(self::$MY_ACCOUNT_CONTROLLER)) {
            $this->context->controller->addCSS(__PS_BASE_URI__ . "modules/" . LATCH_PLUGIN_NAME . "/" . self::$CSS_FILE, 'all');
        }
    }

    private function isUserLogged() {
        return $this->context->customer->isLogged();
    }

    private function currentControllerIs($expectedController) {
        return property_exists($this->context->controller, 'php_self') &&
                $this->context->controller->php_self == $expectedController;
    }

    /*
     * Hooks when the authentication has been succesful and checks if the
     * account has been marked as blocked in Latch. If the two factor 
     * authentication is enabled, redirects the user to the two factor form or 
     * checks the received token (OTP).
     */
    public function hookActionAuthentication($params) {
        if (Tools::getValue("twoFactor") != NULL) { // Two factor OTP has been sent by the user
            $this->processTwoFactorPassword();
        } else {
            $latchStatus = $this->getLatchStatus();
            if ($latchStatus['accountBlocked']) {
                $this->context->customer->mylogout();
            } elseif (!empty($latchStatus['twoFactor'])) { // Two factor authentication required, redirect to custom controller
                $this->context->customer->mylogout();      // The user cannot be authenticated yet
                $this->storeCustomerLoginInformation($latchStatus, $_REQUEST['passwd']);
                Tools::redirect($this->context->link->getModuleLink('LatchPS', 'twoFactor'));
            }
        }
    }

    private function getLatchStatus() {
        $api = new LatchWrapper();
        return $api->status();
    }

    private function processTwoFactorPassword() {
        $storedToken = $this->context->cookie->latchTwoFactor;
        if (Tools::getValue("twoFactor") != $storedToken) {
            $this->context->customer->mylogout();
        }
        $this->removeCustomerLoginInformation();
    }
    
    private function removeCustomerLoginInformation() {
        unset($this->context->cookie->latchPasswd);
        unset($this->context->cookie->latchEmail);
        unset($this->context->cookie->latchTwoFactor);
    }

    private function storeCustomerLoginInformation($latchStatus, $passwd) {
        $this->context->cookie->latchEmail = Tools::getValue("email");
        $this->context->cookie->latchPasswd = $passwd;
        $this->context->cookie->latchTwoFactor = $latchStatus["twoFactor"];
    }

    /*
     * Tells to Prestashop that this module has a configuration page.
     */
    public function getContent() {
        $output = "";
        if (Tools::isSubmit('submit' . $this->name)) {
            $appId = strval(Tools::getValue("latch_appId"));
            $appSecret = strval(Tools::getValue("latch_appSecret"));
            Configuration::updateValue("latch_appId", $appId);
            Configuration::updateValue("latch_appSecret", $appSecret);
            $output .= $this->displayConfirmation($this->l('Configuration updated'));
        }
        return $output . $this->displayForm();
    }

    /*
     * Generates the configuration form using a Helper class provided by the
     * Prestashop Framework.
     */
    private function displayForm() {
        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;

        // Get default Language
        $default_lang = (int) Configuration::get('PS_LANG_DEFAULT');
        // Language
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
        $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'submit' . $this->name;
        $helper->toolbar_btn = array(
            'save' =>
            array(
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save' . $this->name .
                '&token=' . Tools::getAdminTokenLite('AdminModules'),
            ),
            'back' => array(
                'href' => AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            )
        );

        // Load current values
        $helper->fields_value['latch_appId'] = Configuration::get('latch_appId');
        $helper->fields_value['latch_appSecret'] = Configuration::get('latch_appSecret');

        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Latch configuration parameters')
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'name' => 'latch_appId',
                    'label' => $this->l('Application ID'),
                    'required' => true
                ),
                array(
                    'type' => 'text',
                    'name' => 'latch_appSecret',
                    'label' => $this->l('Application secret'),
                    'required' => true
                )
            ),
            'submit' => array(
                'title' => 'Save',
                'class' => 'button'
            )
        );

        return $helper->generateForm($fields_form);
    }

}
