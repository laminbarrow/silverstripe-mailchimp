<?php

/**
 * SiteConfig
 *
 * @package SwiftDevLabs\SSMailchimp\Extensions
 * @author Kong Jin Jie <jinjie@swiftdev.sg>
 */

namespace SwiftDevLabs\SSMailchimp\Extensions;

use SilverStripe\Forms\DropdownField;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\Queries\SQLUpdate;
use SilverStripe\Security\Member;
use SwiftDevLabs\SSMailchimp\Helpers\MailchimpHelper;

class SiteConfig extends DataExtension
{
    private static $db = [
        'MailchimpListID' => 'Varchar(10)',
    ];

    public function updateCMSFields($fields)
    {
        $mc = MailchimpHelper::create()->getClient();
        $response = $mc->lists->getAllLists('lists.id,lists.web_id,lists.name');

        $fields->addFieldToTab(
            'Root.Mailchimp',
            DropdownField::create(
                'MailchimpListID',
                _t('Mailchimp.MailchimpListID', 'Mailchimp List ID')
            )
                ->setSource(ArrayList::create($response->lists)->map('id', 'name'))
                ->setEmptyString('Select List')
        );
    }

    public function onBeforeWrite()
    {
        if ($this->owner->isChanged('MailchimpListID')) {
            $update = SQLUpdate::create(
                singleton(Member::class)->baseTable(),
                [
                    'MailchimpID'       => null,
                    'MailchimpLastSync' => null,
                ]
            );

            $update->execute();
        }

        parent::onBeforeWrite();
    }
}
