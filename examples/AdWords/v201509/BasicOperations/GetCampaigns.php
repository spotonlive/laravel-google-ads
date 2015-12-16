<?php

namespace App\Services;

use AdWordsConstants;
use LaravelGoogleAds\AdWords\AdWordsUser;
use OrderBy;
use Paging;
use Selector;

class AdWordsService
{
    public function example()
    {
        $user = new AdWordsUser(null, null, 'ID');
        $user->LogAll();

        $this->getCampaignsExample($user);
    }

    public function getCampaignsExample(AdWordsUser $user)
    {
        /** @var \CampaignService $campaignService */
        $campaignService = $user->GetService('CampaignService', 'v201509');

        $selector = new Selector();
        $selector->fields = [
            'Id',
            'Name'
        ];

        $selector->ordering[] = new OrderBy('Name', 'ASCENDING');

        $selector->paging = new Paging(
            0,
            AdWordsConstants::RECOMMENDED_PAGE_SIZE
        );

        do {
            // Request
            $page = $campaignService->get($selector);

            // No entries found
            if (!isset($page->entries)) {
                return null;
            }

            foreach ($page->entries as $campaign) {
                echo sprintf(
                    "Campaign with name '%s' and ID #'%s' was found\n",
                    $campaign->name,
                    $campaign->id
                );
            }

            $selector->paging->startIndex += AdWordsConstants::RECOMMENDED_PAGE_SIZE;
        } while ($page->totalNumEntries > $selector->paging->startIndex);
    }
}
