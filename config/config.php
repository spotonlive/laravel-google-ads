<?php

return [
    'common' => [
        'build' => [
            'LIB_VERSION' => '6.6.0',
            'LIB_NAME' => 'Common-PHP',
        ],
    ],

    'adWords' => [
        'build' => [
            'LIB_NAME' => 'AwApi-PHP',
        ],

        'auth' => [
            'developerToken' => '',
            'userAgent' => '',

            /*
             * Uncomment clientCustomerId to make requests against a single AdWords account,
             * such as when you run the examples.
             * If you don't set it here, you can set the client customer ID dynamically:
             *  $user = new AdWordsUser();
             *  $user->SetClientCustomerId(...);
             */
            // clientCustomerId => '',

            /*
             * [OAUTH2]
             *
             * If you do not have a client ID or secret, please create one of type
             * "installed application" in the Google API console:
             * https://cloud.google.com/console
             */
            'OAUTH2' => [
                /*
                 * If you do not have a client ID or secret, please create one of type
                 * "installed application" in the Google API console:
                 * https://cloud.google.com/console
                 */
                'client_id' => '',
                'client_secret' => '',

                /*
                 * If you already have a refresh token, enter it below. Otherwise run
                 * GetRefreshToken.php.
                 */
                'refresh_token' => '',

                /*
                 * Optionally, uncomment the oAuth2AdditionalScopes line to provide additional
                 * OAuth2 scopes. The AdWords API OAuth2 scope is always included. For
                 * additional OAuth2 scopes, reference the OAuth 2.0 Playground
                 * (https://developers.google.com/oauthplayground/). In the playground, each
                 * application has a list of OAuth2 scopes. For example, you would enter
                 * https://www.googleapis.com/auth/analytics here as a value if you would like
                 * to include Google Analytics as an additional scope.oAuth2AdditionalScopes = "INSERT_COMMA_SEPARATED_LIST_OF_SCOPES_HERE"
                 */
            ],
        ],

        'settings' => [
            'LOGGING' => [
                /*
                 * Log directory is either an absolute path, or relative path from the AdWordsUser.php file.
                 */
                // 'PATH_RELATIVE' => '0',
                // 'LIB_LOG_DIR_PATH' => 'path/to/logs',
            ],

            'SERVER' => [
                'DEFAULT_VERSION' => 'v201509',
                'DEFAULT_SERVER' => 'https://adwords.google.com',
            ],

            'SOAP' => [
                /*
                 * Enable/disable gzip compression on SOAP requests and responses.
                 */
                'COMPRESSION' => 1,

                /*
                 * The level of gzip compression to use, from 1 to 9. The higher the level the
                 * greater the compression and time needed to perform the compression. The
                 * recommended and default value is 1.
                 */
                'COMPRESSION_LEVEL' => 1,

                /*
                 * The type of WSDL caching to use. The possible values are 0 (none), 1 (disk),
                 * 2 (memory), or 3 (disk and memory). The default value is 0.
                 */
                'WSDL_CACHE' => 0,

                /*
                 * Other WSDL caching settings can be set in php.ini. See the following page for
                 * the complete list: http://www.php.net/manual/en/soap.configuration.php
                 */

                /*
                 * Forces HTTP requests to be made with the specified version. Valid versions are
                 * those supported by the protocol_version HTTP context option:
                 * http://us3.php.net/manual/en/context.http.php.
                 *
                 * If not set, the client library will use the default HTTP protocol version that
                 * your version of PHP uses, unless you're using PHP < 5.4.x, in which case it
                 * of HTTP 1.1 chunked data properly.
                 */
                // 'FORCE_HTTP_VERSION' = '<HTTP VERSION>',

                /*
                 * Forces SOAP requests to add the XSI types or not. Different PHP versions
                 * on different platforms may need to set to true or false.
                 * If not set, the client library will default to 0 (false), except for the
                 * following:
                 * - PHP < 5.2.6 on any OS will be set to 1 (true) when this setting is not set
                 * - PHP < 5.3.0 on Darwin OS will be set to 1 (true) when this setting is not set
                 * If you are receiving "Unmarshalling Error", uncomment the following setting
                 * and set it to either 0 (false) or 1 (true).
                 */
                // 'FORCE_ADD_XSI_TYPES' => 1,
            ],

            'PROXY' => [
                /*
                 * Proxy settings to be used by HTTP (and therefore SOAP) requests.
                 */
                // 'HOST' => '<HOST>',
                // 'PORT' => '<PORT>',
                // 'USER' => '<USER NAME>',
                // 'PASSWORD' => '<PASSWORD>',
            ],

            'AUTH' => [
                /*
                 * The OAuth2Handler class to use for OAuth2 flow.
                 */
                'OAUTH2_HANDLER_CLASS' => 'SimpleOAuth2Handler',
            ],

            'SSL' => [
                /*
                 * Enable/disable peer verification of SSL certificates. If enabled, specify
                 * either CA_PATH or CA_FILE.
                 */
                'VERIFY_PEER' => '0',

                /*
                 * Enable/disable host verification of SSL certificates. This option requires
                 * that VERIFY_PEER is enabled.
                 */
                'VERIFY_HOST' => '0',
            ],
        ],
        
        'api.properties' => [
           /*
            * Library
            */
            'lib.product' => 'AdWords',
            'lib.package' => 'GoogleApiAdsAdWords',

            /*
             * WSDL2PHP
             * Optionally specify a proxy to use when processing the WSDLs in the format
             * tcp://host:port.
             */
            'wsdl2php.proxy' => '',

            /*
             * If enabled, class names (but not service names) will be prefixed with the
             * package name. Ex) Google\Api\Ads\AdWords\Campaign
             */
            'wsdl2php.enableNamespaces' => false,

            /*
             * Class map to be used if namespace support is not enabled, to work around
             * common conflicts, in JSON format.
             */
            'wsdl2php.conflictClassmap' => '{"DateTime":"AdWordsDateTime", "SoapHeader":"SoapRequestHeader"}',

            /*
             * Other class fixes.
             */
            'wsdl2php.classmap' => '{"getResponse":"${service}GetResponse", "get":"${service}Get", "mutate":"${service}Mutate", "mutateResponse":"${service}MutateResponse", "mutateCallToAction":"${service}MutateCallToAction", "search":"${service}Search", "Function":"FeedFunction"}',
            'wsdl2php.skipClassNameCheckTypes' => 'Target,Location',

            /*
             * API
             */
            'api.server' => 'https://adwords.google.com',
            'api.versions' => 'v201506,v201509',
            'api.soapClientClassNamespace' => 'Google_Api_Ads_AdWords_Lib',

            /*
             * v201506
             */
            'api.versions.v201506.namespace' => 'Google_Api_Ads_AdWords_v201506',
            'api.versions.v201506.services=AdGroupAdService,AdGroupBidModifierService,AdGroupCriterionService,AdGroupFeedService,AdGroupService,AdParamService,AdwordsUserListService,BiddingStrategyService,BudgetOrderService,BudgetService,CampaignCriterionService,CampaignFeedService,CampaignService,ConstantDataService,ConversionTrackerService,CustomerFeedService,CustomerService,CustomerSyncService,DataService,ExperimentService,FeedItemService,FeedMappingService,FeedService,GeoLocationService,LabelService,LocationCriterionService,ManagedCustomerService,MediaService,MutateJobService,OfflineConversionFeedService,ReportDefinitionService,TargetingIdeaService,TrafficEstimatorService,ExpressBusinessService,ProductServiceService,BudgetSuggestionService,PromotionService,CampaignSharedSetService,SharedCriterionService,SharedSetService,CampaignExtensionSettingService,AdGroupExtensionSettingService,CustomerExtensionSettingService,AdCustomizerFeedService,AccountLabelService',
            'api.versions.v201506.services.AdGroupAdService.wsdl' => '${api.server}/api/adwords/cm/v201506/AdGroupAdService?wsdl',
            'api.versions.v201506.services.AdGroupBidModifierService.wsdl' => '${api.server}/api/adwords/cm/v201506/AdGroupBidModifierService?wsdl',
            'api.versions.v201506.services.AdGroupCriterionService.wsdl' => '${api.server}/api/adwords/cm/v201506/AdGroupCriterionService?wsdl',
            'api.versions.v201506.services.AdGroupFeedService.wsdl' => '${api.server}/api/adwords/cm/v201506/AdGroupFeedService?wsdl',
            'api.versions.v201506.services.AdGroupService.wsdl' => '${api.server}/api/adwords/cm/v201506/AdGroupService?wsdl',
            'api.versions.v201506.services.AdParamService.wsdl' => '${api.server}/api/adwords/cm/v201506/AdParamService?wsdl',
            'api.versions.v201506.services.AdwordsUserListService.wsdl' => '${api.server}/api/adwords/rm/v201506/AdwordsUserListService?wsdl',
            'api.versions.v201506.services.BiddingStrategyService.wsdl' => '${api.server}/api/adwords/cm/v201506/BiddingStrategyService?wsdl',
            'api.versions.v201506.services.BudgetOrderService.wsdl' => '${api.server}/api/adwords/billing/v201506/BudgetOrderService?wsdl',
            'api.versions.v201506.services.BudgetService.wsdl' => '${api.server}/api/adwords/cm/v201506/BudgetService?wsdl',
            'api.versions.v201506.services.BudgetSuggestionService.wsdl' => '${api.server}/api/adwords/express/v201506/BudgetSuggestionService?wsdl',
            'api.versions.v201506.services.CampaignCriterionService.wsdl' => '${api.server}/api/adwords/cm/v201506/CampaignCriterionService?wsdl',
            'api.versions.v201506.services.CampaignFeedService.wsdl' => '${api.server}/api/adwords/cm/v201506/CampaignFeedService?wsdl',
            'api.versions.v201506.services.CampaignService.wsdl' => '${api.server}/api/adwords/cm/v201506/CampaignService?wsdl',
            'api.versions.v201506.services.CampaignSharedSetService.wsdl' => '${api.server}/api/adwords/cm/v201506/CampaignSharedSetService?wsdl',
            'api.versions.v201506.services.ConstantDataService.wsdl' => '${api.server}/api/adwords/cm/v201506/ConstantDataService?wsdl',
            'api.versions.v201506.services.ConversionTrackerService.wsdl' => '${api.server}/api/adwords/cm/v201506/ConversionTrackerService?wsdl',
            'api.versions.v201506.services.CustomerFeedService.wsdl' => '${api.server}/api/adwords/cm/v201506/CustomerFeedService?wsdl',
            'api.versions.v201506.services.CustomerService.wsdl' => '${api.server}/api/adwords/mcm/v201506/CustomerService?wsdl',
            'api.versions.v201506.services.CustomerSyncService.wsdl' => '${api.server}/api/adwords/ch/v201506/CustomerSyncService?wsdl',
            'api.versions.v201506.services.DataService.wsdl' => '${api.server}/api/adwords/cm/v201506/DataService?wsdl',
            'api.versions.v201506.services.ExperimentService.wsdl' => '${api.server}/api/adwords/cm/v201506/ExperimentService?wsdl',
            'api.versions.v201506.services.ExpressBusinessService.wsdl' => '${api.server}/api/adwords/express/v201506/ExpressBusinessService?wsdl',
            'api.versions.v201506.services.FeedItemService.wsdl' => '${api.server}/api/adwords/cm/v201506/FeedItemService?wsdl',
            'api.versions.v201506.services.FeedMappingService.wsdl' => '${api.server}/api/adwords/cm/v201506/FeedMappingService?wsdl',
            'api.versions.v201506.services.FeedService.wsdl' => '${api.server}/api/adwords/cm/v201506/FeedService?wsdl',
            'api.versions.v201506.services.GeoLocationService.wsdl' => '${api.server}/api/adwords/cm/v201506/GeoLocationService?wsdl',
            'api.versions.v201506.services.LabelService.wsdl' => '${api.server}/api/adwords/cm/v201506/LabelService?wsdl',
            'api.versions.v201506.services.LocationCriterionService.wsdl' => '${api.server}/api/adwords/cm/v201506/LocationCriterionService?wsdl',
            'api.versions.v201506.services.ManagedCustomerService.wsdl' => '${api.server}/api/adwords/mcm/v201506/ManagedCustomerService?wsdl',
            'api.versions.v201506.services.MediaService.wsdl' => '${api.server}/api/adwords/cm/v201506/MediaService?wsdl',
            'api.versions.v201506.services.MutateJobService.wsdl' => '${api.server}/api/adwords/cm/v201506/MutateJobService?wsdl',
            'api.versions.v201506.services.OfflineConversionFeedService.wsdl' => '${api.server}/api/adwords/cm/v201506/OfflineConversionFeedService?wsdl',
            'api.versions.v201506.services.ProductServiceService.wsdl' => '${api.server}/api/adwords/express/v201506/ProductServiceService?wsdl',
            'api.versions.v201506.services.PromotionService.wsdl' => '${api.server}/api/adwords/express/v201506/PromotionService?wsdl',
            'api.versions.v201506.services.ReportDefinitionService.wsdl' => '${api.server}/api/adwords/cm/v201506/ReportDefinitionService?wsdl',
            'api.versions.v201506.services.SharedCriterionService.wsdl' => '${api.server}/api/adwords/cm/v201506/SharedCriterionService?wsdl',
            'api.versions.v201506.services.SharedSetService.wsdl' => '${api.server}/api/adwords/cm/v201506/SharedSetService?wsdl',
            'api.versions.v201506.services.TargetingIdeaService.wsdl' => '${api.server}/api/adwords/o/v201506/TargetingIdeaService?wsdl',
            'api.versions.v201506.services.TrafficEstimatorService.wsdl' => '${api.server}/api/adwords/o/v201506/TrafficEstimatorService?wsdl',
            'api.versions.v201506.services.CampaignExtensionSettingService.wsdl' => '${api.server}/api/adwords/cm/v201506/CampaignExtensionSettingService?wsdl',
            'api.versions.v201506.services.AdGroupExtensionSettingService.wsdl' => '${api.server}/api/adwords/cm/v201506/AdGroupExtensionSettingService?wsdl',
            'api.versions.v201506.services.CustomerExtensionSettingService.wsdl' => '${api.server}/api/adwords/cm/v201506/CustomerExtensionSettingService?wsdl',
            'api.versions.v201506.services.AdCustomizerFeedService.wsdl' => '${api.server}/api/adwords/cm/v201506/AdCustomizerFeedService?wsdl',
            'api.versions.v201506.services.AccountLabelService.wsdl' => '${api.server}/api/adwords/mcm/v201506/AccountLabelService?wsdl',

            /*
             * v201509
             */
            'api.versions.v201509.namespace' => 'Google_Api_Ads_AdWords_v201509',
            'api.versions.v201509.services' => 'AdGroupAdService,AdGroupBidModifierService,AdGroupCriterionService,AdGroupFeedService,AdGroupService,AdParamService,AdwordsUserListService,BatchJobService,BiddingStrategyService,BudgetOrderService,BudgetService,CampaignCriterionService,CampaignFeedService,CampaignService,ConstantDataService,ConversionTrackerService,CustomerFeedService,CustomerService,CustomerSyncService,DataService,ExperimentService,FeedItemService,FeedMappingService,FeedService,LabelService,LocationCriterionService,ManagedCustomerService,MediaService,MutateJobService,OfflineConversionFeedService,ReportDefinitionService,TargetingIdeaService,TrafficEstimatorService,ExpressBusinessService,ProductServiceService,BudgetSuggestionService,PromotionService,CampaignSharedSetService,SharedCriterionService,SharedSetService,CampaignExtensionSettingService,AdGroupExtensionSettingService,CustomerExtensionSettingService,AdCustomizerFeedService,AccountLabelService',
            'api.versions.v201509.services.AdGroupAdService.wsdl' => '${api.server}/api/adwords/cm/v201509/AdGroupAdService?wsdl',
            'api.versions.v201509.services.AdGroupBidModifierService.wsdl' => '${api.server}/api/adwords/cm/v201509/AdGroupBidModifierService?wsdl',
            'api.versions.v201509.services.AdGroupCriterionService.wsdl' => '${api.server}/api/adwords/cm/v201509/AdGroupCriterionService?wsdl',
            'api.versions.v201509.services.AdGroupFeedService.wsdl' => '${api.server}/api/adwords/cm/v201509/AdGroupFeedService?wsdl',
            'api.versions.v201509.services.AdGroupService.wsdl' => '${api.server}/api/adwords/cm/v201509/AdGroupService?wsdl',
            'api.versions.v201509.services.AdParamService.wsdl' => '${api.server}/api/adwords/cm/v201509/AdParamService?wsdl',
            'api.versions.v201509.services.AdwordsUserListService.wsdl' => '${api.server}/api/adwords/rm/v201509/AdwordsUserListService?wsdl',
            'api.versions.v201509.services.BatchJobService.wsdl' => '${api.server}/api/adwords/cm/v201509/BatchJobService?wsdl',
            'api.versions.v201509.services.BiddingStrategyService.wsdl' => '${api.server}/api/adwords/cm/v201509/BiddingStrategyService?wsdl',
            'api.versions.v201509.services.BudgetOrderService.wsdl' => '${api.server}/api/adwords/billing/v201509/BudgetOrderService?wsdl',
            'api.versions.v201509.services.BudgetService.wsdl' => '${api.server}/api/adwords/cm/v201509/BudgetService?wsdl',
            'api.versions.v201509.services.BudgetSuggestionService.wsdl' => '${api.server}/api/adwords/express/v201509/BudgetSuggestionService?wsdl',
            'api.versions.v201509.services.CampaignCriterionService.wsdl' => '${api.server}/api/adwords/cm/v201509/CampaignCriterionService?wsdl',
            'api.versions.v201509.services.CampaignFeedService.wsdl' => '${api.server}/api/adwords/cm/v201509/CampaignFeedService?wsdl',
            'api.versions.v201509.services.CampaignService.wsdl' => '${api.server}/api/adwords/cm/v201509/CampaignService?wsdl',
            'api.versions.v201509.services.CampaignSharedSetService.wsdl' => '${api.server}/api/adwords/cm/v201509/CampaignSharedSetService?wsdl',
            'api.versions.v201509.services.ConstantDataService.wsdl' => '${api.server}/api/adwords/cm/v201509/ConstantDataService?wsdl',
            'api.versions.v201509.services.ConversionTrackerService.wsdl' => '${api.server}/api/adwords/cm/v201509/ConversionTrackerService?wsdl',
            'api.versions.v201509.services.CustomerFeedService.wsdl' => '${api.server}/api/adwords/cm/v201509/CustomerFeedService?wsdl',
            'api.versions.v201509.services.CustomerService.wsdl' => '${api.server}/api/adwords/mcm/v201509/CustomerService?wsdl',
            'api.versions.v201509.services.CustomerSyncService.wsdl' => '${api.server}/api/adwords/ch/v201509/CustomerSyncService?wsdl',
            'api.versions.v201509.services.DataService.wsdl' => '${api.server}/api/adwords/cm/v201509/DataService?wsdl',
            'api.versions.v201509.services.ExperimentService.wsdl' => '${api.server}/api/adwords/cm/v201509/ExperimentService?wsdl',
            'api.versions.v201509.services.ExpressBusinessService.wsdl' => '${api.server}/api/adwords/express/v201509/ExpressBusinessService?wsdl',
            'api.versions.v201509.services.FeedItemService.wsdl' => '${api.server}/api/adwords/cm/v201509/FeedItemService?wsdl',
            'api.versions.v201509.services.FeedMappingService.wsdl' => '${api.server}/api/adwords/cm/v201509/FeedMappingService?wsdl',
            'api.versions.v201509.services.FeedService.wsdl' => '${api.server}/api/adwords/cm/v201509/FeedService?wsdl',
            'api.versions.v201509.services.LabelService.wsdl' => '${api.server}/api/adwords/cm/v201509/LabelService?wsdl',
            'api.versions.v201509.services.LocationCriterionService.wsdl' => '${api.server}/api/adwords/cm/v201509/LocationCriterionService?wsdl',
            'api.versions.v201509.services.ManagedCustomerService.wsdl' => '${api.server}/api/adwords/mcm/v201509/ManagedCustomerService?wsdl',
            'api.versions.v201509.services.MediaService.wsdl' => '${api.server}/api/adwords/cm/v201509/MediaService?wsdl',
            'api.versions.v201509.services.MutateJobService.wsdl' => '${api.server}/api/adwords/cm/v201509/MutateJobService?wsdl',
            'api.versions.v201509.services.OfflineConversionFeedService.wsdl' => '${api.server}/api/adwords/cm/v201509/OfflineConversionFeedService?wsdl',
            'api.versions.v201509.services.ProductServiceService.wsdl' => '${api.server}/api/adwords/express/v201509/ProductServiceService?wsdl',
            'api.versions.v201509.services.PromotionService.wsdl' => '${api.server}/api/adwords/express/v201509/PromotionService?wsdl',
            'api.versions.v201509.services.ReportDefinitionService.wsdl' => '${api.server}/api/adwords/cm/v201509/ReportDefinitionService?wsdl',
            'api.versions.v201509.services.SharedCriterionService.wsdl' => '${api.server}/api/adwords/cm/v201509/SharedCriterionService?wsdl',
            'api.versions.v201509.services.SharedSetService.wsdl' => '${api.server}/api/adwords/cm/v201509/SharedSetService?wsdl',
            'api.versions.v201509.services.TargetingIdeaService.wsdl' => '${api.server}/api/adwords/o/v201509/TargetingIdeaService?wsdl',
            'api.versions.v201509.services.TrafficEstimatorService.wsdl' => '${api.server}/api/adwords/o/v201509/TrafficEstimatorService?wsdl',
            'api.versions.v201509.services.CampaignExtensionSettingService.wsdl' => '${api.server}/api/adwords/cm/v201509/CampaignExtensionSettingService?wsdl',
            'api.versions.v201509.services.AdGroupExtensionSettingService.wsdl' => '${api.server}/api/adwords/cm/v201509/AdGroupExtensionSettingService?wsdl',
            'api.versions.v201509.services.CustomerExtensionSettingService.wsdl' => '${api.server}/api/adwords/cm/v201509/CustomerExtensionSettingService?wsdl',
            'api.versions.v201509.services.AdCustomizerFeedService.wsdl' => '${api.server}/api/adwords/cm/v201509/AdCustomizerFeedService?wsdl',
            'api.versions.v201509.services.AccountLabelService.wsdl' => '${api.server}/api/adwords/mcm/v201509/AccountLabelService?wsdl',
        ],
    ],
];
