<?php

// @codingStandardsIgnoreStart

namespace
{
    /**
     * Override laravels global config method
     *
     * @return array
     */
    function config() {
        return [
            'ADWORDS' => [
                'developerToken' => 'A',
                'clientCustomerId' => 'B',
            ],

            'OAUTH2' => [
                 'clientId' => 'A',
                 'clientSecret' => 'B',
                 'refreshToken' => 'C',
            ],
        ];
    }
}

namespace LaravelGoogleAdsTest\Services
{

    use Google\AdsApi\AdWords\v201609\cm\CampaignService;
    use Google\AdsApi\Common\Configuration;
    use Google\Auth\Credentials\UserRefreshCredentials;
    use LaravelGoogleAds\Services\AdWordsService;
    use PHPUnit_Framework_TestCase;
    use ReflectionClass;
    use ReflectionException;
    use ReflectionMethod;

    class AdWordsServiceTest extends PHPUnit_Framework_TestCase
    {
        // @codingStandardsIgnoreEnd

        /** @var AdWordsService */
        protected $service;

        public function setUp()
        {
            $this->service = new AdWordsService();
        }

        public function testConfiguration()
        {
            $method = self::getMethod('configuration', $this->service);

            /** @var Configuration $result */
            $result = $method->invoke($this->service);

            $expectedConfiguration = config();

            $this->assertInstanceOf(Configuration::class, $result);
            $this->assertSame($expectedConfiguration['ADWORDS'], $result->getConfiguration('ADWORDS'));
        }

        public function testConfigurationWithClientCustomerId()
        {
            $clientCustomerId = '123-456-78';

            $method = self::getMethod('configuration', $this->service);

            /** @var Configuration $result */
            $result = $method->invokeArgs($this->service, [$clientCustomerId]);

            $expectedConfiguration = config();
            $expectedConfiguration['ADWORDS']['clientCustomerId'] = $clientCustomerId;

            $this->assertInstanceOf(Configuration::class, $result);
            $this->assertSame($expectedConfiguration['ADWORDS'], $result->getConfiguration('ADWORDS'));
        }

        public function testOauth2Credentials()
        {
            $clientCustomerId = '123-456-78';

            $method = self::getMethod('oauth2Credentials', $this->service);

            /** @var UserRefreshCredentials $result */
            $result = $method->invokeArgs($this->service, [$clientCustomerId]);

            $this->assertInstanceOf(UserRefreshCredentials::class, $result);
        }
        
        public function testSession()
        {
            $clientCustomerId = '123-456-78';

            $config = config();
            
            $result = $this->service->session($clientCustomerId);

            $this->assertSame($clientCustomerId, $result->getClientCustomerId());
            $this->assertSame($config['ADWORDS']['developerToken'], $result->getDeveloperToken());
        }

        public function testGetService()
        {
            $service = CampaignService::class;

            /** @var CampaignService $result */
            $result = $this->service->getService($service);

            $this->assertInstanceOf($service, $result);
        }

        public function testGetServiceInvalidServiceClass()
        {
            $service = 'InvalidServiceClass';

            $this->setExpectedException(ReflectionException::class,
                sprintf(
                    'Class %s does not exist',
                    $service
                )
            );

            $this->service->getService($service);
        }

        /**
         * Get method from class
         *
         * @param string $name
         * @return ReflectionMethod
         */
        protected static function getMethod($name, $class)
        {
            $class = new ReflectionClass($class);
            $method = $class->getMethod($name);
            $method->setAccessible(true);

            return $method;
        }
    }
}