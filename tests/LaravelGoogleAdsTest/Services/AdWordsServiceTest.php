<?php

namespace LaravelGoogleAdsTest\Exceptions;

use LaravelGoogleAds\AdWords\AdWordsUser;
use LaravelGoogleAds\Services\AdWordsService;
use OAuth2Handler;
use PHPUnit_Framework_TestCase;

require_once 'vendor/googleads/googleads-php-lib/src/Google/Api/Ads/Common/Util/UrlUtils.php';

class AdWordsServiceTest extends PHPUnit_Framework_TestCase
{
    /** @var AdWordsService */
    protected $service;

    public function setUp()
    {
        $this->service = new AdWordsService();
    }

    public function testGetOAuthAuthorizationUrl()
    {
        $oAuth2Info = [
            'demo' => 'info',
        ];

        $authorizationUrl = 'demourl';

        /** @var AdWordsUser $adWordsUser */
        $adWordsUser = $this->getMockBuilder(AdWordsUser::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var OAuth2Handler $oAuth2Handler */
        $oAuth2Handler = $this->getMockBuilder(OAuth2Handler::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'GetAuthorizationUrl',
                'GetAccessToken',
                'RefreshAccessToken',
            ])
            ->getMock();

        $adWordsUser->expects($this->at(0))
            ->method('getOAuth2Handler')
            ->willReturn($oAuth2Handler);

        $adWordsUser->expects($this->at(1))
            ->method('GetOAuth2Info')
            ->willReturn($oAuth2Info);

        $oAuth2Handler->expects($this->once())
            ->method('GetAuthorizationUrl')
            ->with($oAuth2Info, null, true)
            ->willReturn($authorizationUrl);

        $result = $this->service->getOAuthAuthorizationUrl($adWordsUser);

        $this->assertSame($authorizationUrl, $result);
    }

    public function testGetOAuthCredentials()
    {
        $accessCode = 'demo access code';
        $accessToken = 'this is a demo access token';
        $redirectUri = 'http://github.coms';

        $oAuth2Info = [
            'demo' => 'info',
        ];

        /** @var AdWordsUser $adWordsUser */
        $adWordsUser = $this->getMockBuilder(AdWordsUser::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var OAuth2Handler $oAuth2Handler */
        $oAuth2Handler = $this->getMockBuilder(OAuth2Handler::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'GetAuthorizationUrl',
                'GetAccessToken',
                'RefreshAccessToken',
            ])
            ->getMock();


        $adWordsUser->expects($this->at(0))
            ->method('getOAuth2Handler')
            ->willReturn($oAuth2Handler);

        $adWordsUser->expects($this->at(1))
            ->method('getOauth2Info')
            ->willReturn($oAuth2Info);

        $adWordsUser->expects($this->at(2))
            ->method('setOauth2Info')
            ->with($accessToken);

        $adWordsUser->expects($this->at(3))
            ->method('getOauth2Info')
            ->willReturn($oAuth2Info);

        $oAuth2Handler->expects($this->at(0))
            ->method('GetAccessToken')
            ->with($oAuth2Info, $accessCode, $redirectUri)
            ->willReturn($accessToken);

        $result = $this->service->getOAuthCredentials($adWordsUser, $accessCode, $redirectUri);

        $this->assertSame($oAuth2Info, $result);
    }
}