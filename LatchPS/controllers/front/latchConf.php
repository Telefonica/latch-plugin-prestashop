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

require_once _PS_MODULE_DIR_ . LATCH_PLUGIN_NAME . '/LatchData.php';

/*
 * Loads the paired/unpaired configuration page for the customer.
 */
class LatchPSLatchConfModuleFrontController extends ModuleFrontController {

    static $LATCH_CONFIG_TEMPLATE = 'latchConf.tpl';
    static $PAGE_FOR_REDIRECTION = 'index.php';
    static $CSS_FILE = 'latch.css';
    

    public function init() {
        parent::init();
    }

    public function setMedia() {
        parent::setMedia();
        $this->addCSS(__PS_BASE_URI__ . "modules/" . LATCH_PLUGIN_NAME . "/" . self::$CSS_FILE, 'all');
    }
    
    public function initContent() {
        parent::initContent();

        if ($this->isUserLogged()) {
            $this->assignSmartyVariables();
            $this->setCSRFToken();
            $this->setTemplate(self::$LATCH_CONFIG_TEMPLATE);
        } else {
            Tools::redirect(self::$PAGE_FOR_REDIRECTION);
        }
    }

    private function isUserLogged() {
        return $this->context->customer->isLogged();
    }
    
    private function assignSmartyVariables() {
        $smarty_variables = array('isPaired' => $this->isAccountPaired());
        $smarty_variables['imagesURL'] = __PS_BASE_URI__ . "modules/" . LATCH_PLUGIN_NAME . "/img/";
        $this->context->smarty->assign($smarty_variables);
    }

    private function isAccountPaired() {
        $customerId = $this->getCurrentCustomerId();
        $customerData = LatchData::getLatchDataFromCustomerId($customerId);
        return $this->containsLatchId($customerData);
    }
    
    private function containsLatchId($customerData) {
        return $customerData->id_latch != NULL && $customerData->id_latch != "";
    }

    private function getCurrentCustomerId() {
        return $this->context->cookie->id_customer;
    }
    
    private function setCSRFToken() {
        $time = new DateTime();
        $token = Tools::getToken($time->getTimestamp());
        $this->context->cookie->latchCSRFToken = $token;
        $this->context->smarty->assign(array('latchCSRFToken' => $token));
    }
}
