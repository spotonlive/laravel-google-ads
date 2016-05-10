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

        /*
         * Override Google API properties for AdWords
         * See ../src/LaravelGoogleAds/Options/AdWords/ApiPropertyOptions.php
         */
        'api.properties' => [],
    ],
];
