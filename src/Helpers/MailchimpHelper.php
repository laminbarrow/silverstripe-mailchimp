<?php

/**
 * MailchimpHelper
 *
 * @package SwiftDevLabs\SSMailchimp\Helpers
 * @author Kong Jin Jie <jinjie@swiftdev.sg>
 */

namespace SwiftDevLabs\SSMailchimp\Helpers;

use MailchimpMarketing\ApiClient;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;

class MailchimpHelper
{
    use Injectable;
    use Configurable;

    private $api_key;

    private $server;

    public function getClient()
    {
        $client = new ApiClient();
        $client->setConfig([
            'apiKey' => $this->getApiKey(),
            'server' => $this->getServer(),
        ]);

        return $client;
    }

    public function setApiKey($value)
    {
        $this->api_key = $value;

        return $this;
    }

    public function getApiKey()
    {
        return $this->api_key;
    }

    public function setServer($value)
    {
        $this->server = $value;

        return $this;
    }

    public function getServer()
    {
        return $this->server;
    }
}
