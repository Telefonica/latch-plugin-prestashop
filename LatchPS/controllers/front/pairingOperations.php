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

require_once _PS_MODULE_DIR_ . LATCH_PLUGIN_NAME . '/LatchWrapper.php';

/*
 * Manages the pairing/unpairing operations.
 */
class LatchPSPairingOperationsModuleFrontController extends ModuleFrontController {

    private static $PAIRING_TOKEN_TEMPLATE = 'pairingToken.tpl';
    private static $LATCH_CONFIG_TEMPLATE = 'latchConf.tpl';
    private static $CSS_FILE = 'latch.css';
    
    private $api = NULL;

    public function init() {
        $this->api = new LatchWrapper();
        parent::init();
    }

    public function setMedia() {
        parent::setMedia();
        $this->addCSS(__PS_BASE_URI__ . "modules/" . LATCH_PLUGIN_NAME . "/" . self::$CSS_FILE, 'all');
    }
    
    public function initContent() {
        parent::initContent();
        if ($this->isUserLogged()) {
            if ((Tools::getValue("latchPaired") != NULL || Tools::getValue("pairingToken") != NULL) && $this->csrfTokenMismatch()) {
                // Do not perform any operation, possible CSRF
                Tools::redirect('index.php');
            }
            $template = "";
            if (Tools::getValue("latchPaired") != NULL) { // The user comes from the configuration form
                $template = $this->processUserChoiceForLatchConfiguration();
            } elseif (Tools::getValue("pairingToken") != NULL) { // The user has just submitted the pairing token
                $template = $this->processPairingToken();
            } else { // The user typed the URL for this controller directly
                Tools::redirect('index.php');
            }
            $this->setTemplate($template);
        } else {
            Tools::redirect('index.php');
        }
    }

    private function isUserLogged() {
        return $this->context->customer->isLogged();
    }

    /*
     * Process the customer decision to pair/unpar the account. If the choice is
     * the account pairing, loads the page to ask for the pairing token.
     */
    private function processUserChoiceForLatchConfiguration() {
        $latchPaired = Tools::getValue("latchPaired");
        $userWantsToPairAccount = ($latchPaired == "yes");
        $this->setCSRFToken();
        if ($userWantsToPairAccount) {
            $this->context->smarty->assign(array('imagesURL' => __PS_BASE_URI__ . "modules/" . LATCH_PLUGIN_NAME . "/img/"));
            return self::$PAIRING_TOKEN_TEMPLATE;
        } else {
            $this->unpairAccount();
            $this->context->smarty->assign(array(
                'isPaired' => false,
                'confirmation' => true,
                'imagesURL' => __PS_BASE_URI__ . "modules/" . LATCH_PLUGIN_NAME . "/img/"
            ));
            return self::$LATCH_CONFIG_TEMPLATE;
        }
    }

    private function processPairingToken() {
        $this->pairAccount(Tools::getValue("pairingToken"));
        $customerData = LatchData::getLatchDataFromCustomerId($this->getCustomerId());
        $smarty_variables = array(
            'isPaired' => $this->containsLatchId($customerData),
            'imagesURL' => __PS_BASE_URI__ . "modules/" . LATCH_PLUGIN_NAME . "/img/"
        );
        if ($smarty_variables['isPaired']) {
            $smarty_variables['confirmation'] = true;
        } else {
            $smarty_variables['latchError'] = true;
        }
        $this->context->smarty->assign($smarty_variables);
        $this->setCSRFToken();
        return self::$LATCH_CONFIG_TEMPLATE;
    }

    private function getCustomerId() {
        return $this->context->cookie->id_customer;
    }
    
    private function containsLatchId($customerData) {
        return $customerData->id_latch != NULL &&
                $customerData->id_latch != "";
    }
    
    private function csrfTokenMismatch() {
        $token = $this->context->cookie->latchCSRFToken;
        if (isset($this->context->cookie->latchCSRFToken)) {
            unset($this->context->cookie->latchCSRFToken);
        }
        $receivedToken = Tools::getValue('latchCSRFToken');
        return empty($receivedToken) || $token != $receivedToken;
    }
    
    private function setCSRFToken() {
        $time = new DateTime();
        $token = Tools::getToken($time->getTimestamp());
        $this->context->cookie->latchCSRFToken = $token;
        $this->context->smarty->assign(array('latchCSRFToken' => $token));
    }
    
    private function pairAccount($pairingToken) {
        return $this->api->pair($pairingToken);
    }

    private function unpairAccount() {
        return $this->api->unpair();
    }
    
    private function getStatus() {
        return $this->api->status();
    }
}
