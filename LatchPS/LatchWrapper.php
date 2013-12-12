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

require_once _PS_MODULE_DIR_ . LATCH_PLUGIN_NAME . '/Latch.php';
require_once _PS_MODULE_DIR_ . LATCH_PLUGIN_NAME . '/LatchResponse.php';
require_once _PS_MODULE_DIR_ . LATCH_PLUGIN_NAME . '/Error.php';
require_once _PS_MODULE_DIR_ . LATCH_PLUGIN_NAME . '/LatchData.php';

/*
 * Wrapper class for the Latch API to perform the database tasks after the 
 * requests to the server. It abstracts the controllers from the configuration
 * parameters and database interactions.
 */
class LatchWrapper {

    private $api = NULL;

    function __construct() {
        $appId = Configuration::get('latch_appId');
        $appSecret = Configuration::get('latch_appSecret');
        if ($appId != NULL && $appId != "" && $appSecret != NULL && $appSecret != "") {
            $this->api = new Latch($appId, $appSecret);
        }
    }

    public function pair($pairingToken) {
        if ($this->api != NULL) {
            return $this->performPairing($pairingToken);
        } else {
            return false;
        }
    }

    private function performPairing($pairingToken) {
        $response = $this->api->pair($pairingToken);
        return $this->containsAccountId($response) &&
                $this->storeAccountId($response->getData()->{"accountId"});
    }

    private function containsAccountId($apiResponse) {
        return $apiResponse->getData() != NULL &&
                $apiResponse->getData()->{"accountId"} != NULL;
    }

    private function storeAccountId($accountId) {
        $customerId = $this->getCurrentCustomerId();
        return LatchData::storeLatchId($customerId, $accountId);
    }

    public function unpair() {
        if ($this->api != NULL) {
            return $this->performUnpairing();
        } else {
            return false;
        }
    }

    private function performUnpairing() {
        $customerData = $this->getCustomerLatchConfiguration();
        if ($this->isLatchIdStored($customerData)) {
            $this->api->unpair($customerData->id_latch);
            return LatchData::removeLatchId($this->getCurrentCustomerId());
        }
        return true;
    }

    public function status() {
        if ($this->api != NULL) {
            $customerData = $this->getCustomerLatchConfiguration();
            if ($this->isLatchIdStored($customerData)) { // Account is paired
                $response = $this->api->status($customerData->id_latch);
                $applicationId = Configuration::get('latch_appId');
                if ($response->getError() != NULL && $response->getError()->getCode() == 201) {
                    // Account externally unpaired
                    LatchData::removeLatchId($this->getCurrentCustomerId());
                } elseif ($this->responseFormatIsValid($response, $applicationId)) {
                    $status = $this->getStatusFromResponse($response);
                    return array(
                        "accountBlocked" => ($status == "off"),
                        "twoFactor" => $this->getTwoFactorToken($response)
                    );
                }
            }
        }
        // If the Latch plugin is not configured, the server is down or the response
        // is not the expected, the default behavior is to allow the login.
        return array("accountBlocked" => false);
    }

    private function getCustomerLatchConfiguration() {
        $customerId = $this->getCurrentCustomerId();
        return LatchData::getLatchDataFromCustomerId($customerId);
    }

    private function getCurrentCustomerId() {
        $context = Context::getContext();
        return $context->cookie->id_customer;
    }

    public function isLatchIdStored($customerData) {
        return $customerData->id_latch != NULL && $customerData->id_latch != "";
    }

    private function responseFormatIsValid($response, $applicationId) {
        $data = $response->getData();
        return $data != NULL &&
                property_exists($data, "operations") &&
                property_exists($data->{"operations"}, $applicationId) &&
                $response->getError() == NULL;
    }

    private function getStatusFromResponse($response) {
        $applicationId = Configuration::get('latch_appId');
        return $response->getData()->{"operations"}->{$applicationId}->{"status"};
    }

    private function getTwoFactorToken($response) {
        $applicationId = Configuration::get('latch_appId');
        if (property_exists($response->getData()->{"operations"}->{$applicationId}, "two_factor")) {
            return $response->getData()->{"operations"}->{$applicationId}->{"two_factor"}->{"token"};
        } else {
            return NULL;
        }
    }

}
