<?php

/**
 * MailchimpSyncTask
 *
 * @package SwiftDevLabs\SSMailchimp\Dev
 * @author Kong Jin Jie <jinjie@swiftdev.sg>
 */

namespace SwiftDevLabs\SSMailchimp\Dev;

use GuzzleHttp\Exception\ClientException;
use SilverStripe\Control\Director;
use SilverStripe\Core\Convert;
use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\FieldType\DBDatetime;
use SilverStripe\Security\Member;
use SilverStripe\SiteConfig\SiteConfig;
use SwiftDevLabs\SSMailchimp\Helpers\MailchimpHelper;

class MailchimpSyncTask extends BuildTask
{
    protected $title = "Sync Mailchimp";

    protected $description = "Synchronise Member to Mailchimp list";

    protected $verbosity = 1;

    public function run($request) {
        $site_config = SiteConfig::current_site_config();

        if (!$list_id = $site_config->MailchimpListID) {
            $this->output('Please choose a list to sync in Settings');

            return false;
        }

        $mc = MailchimpHelper::create()->getClient();

        try {
            $mc->lists->getList($list_id, "id");
        } catch (ClientException $e) {
            if ($e->getCode() == 404) {
                $this->output('List not found');
            } else {
                $this->output($e->getMessage());
            }
        }

        $members = Member::get()
            ->whereAny([
                'LastEdited > MailchimpLastSync',
                'MailchimpLastSync IS NULL'
            ]);

        $success = 0;
        foreach ($members as $member) {
            try {
                $member->syncMailchimp();
                $this->output("Success: {$member->Name} ({$member->Email})");
                $success++;
            } catch (\Exception $e) {
                $this->output("Error: {$e->getMessage()} - {$member->Name}");
            }
        }

        $this->output("Done! Successfully synced {$success} contacts");
    }

    /**
     * Output a message to the browser or CLI
     *
     * @param string $message
     */
    public function output($message, $minVerbosity = 1)
    {
        if ($this->verbosity < $minVerbosity) {
            return;
        }
        $timestamp = DBDatetime::now()->Rfc2822();
        if (Director::is_cli()) {
            echo $timestamp . ' - ' . $message . PHP_EOL;
        } else {
            echo Convert::raw2xml($timestamp . ' - ' . $message) . '<br />' . PHP_EOL;
        }
    }
}
