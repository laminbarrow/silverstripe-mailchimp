<?php

/**
 * Member
 *
 * @package SwiftDevLabs\SSMailchimp\Extensions
 * @author Kong Jin Jie <jinjie@swiftdev.sg>
 */

namespace SwiftDevLabs\SSMailchimp\Extensions;

use SilverStripe\Forms\EmailField;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Forms\Validator;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\FieldType\DBDatetime;
use SilverStripe\SiteConfig\SiteConfig;
use SwiftDevLabs\SSMailchimp\Helpers\MailchimpHelper;

class Member extends DataExtension
{
    private static $db = [
        'MailchimpID'       => 'Varchar(50)',
        'MailchimpLastSync' => 'Datetime',
    ];

    public function updateCMSFields($fields)
    {
        $fields->removeByName('MailchimpID');
        $fields->removeByName('MailchimpLastSync');
    }

    public function syncMailchimp()
    {
        if (!$this->owner->Email) {
            throw new \Exception("Empty email address");
        }

        if (!filter_var($this->owner->Email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Invalid email address");
        }

        $mc = MailchimpHelper::create()->getClient();

        if (!$this->owner->MailchimpID) {
            $response = $mc->lists->addListMember(
                SiteConfig::current_site_config()->MailchimpListID,
                [
                    "email_address" => strtolower(trim($this->owner->Email)),
                    "merge_fields" => [
                        "FNAME" => $this->owner->getName(),
                    ],
                    "status"        => "subscribed"
                ]
            );
        } else {
            $response = $mc->lists->setListMember(
                SiteConfig::current_site_config()->MailchimpListID,
                $this->owner->MailchimpID,
                [
                    "email_address" => strtolower(trim($this->owner->Email)),
                    "merge_fields" => [
                        "FNAME" => $this->owner->getName(),
                    ],
                ]
            );
        }

        $this->owner->MailchimpID = $response->id;
        $this->owner->MailchimpLastSync = DBDatetime::now()->Rfc2822();

        return $this->owner->write();
    }
}
