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

/*
 * Loads the Two factor request page.
 */
class LatchPSTwoFactorModuleFrontController extends ModuleFrontController {

    private static $TWO_FACTOR_TEMPLATE = 'twoFactor.tpl';
    
    public function init() {
        parent::init();
    }

    public function initContent() {
        parent::initContent();
        $this->context->smarty->assign(array(
            'passwd' => $this->context->cookie->latchPasswd,
            'email' => $this->context->cookie->latchEmail
        ));
        $this->setTemplate(self::$TWO_FACTOR_TEMPLATE);
    }

}
