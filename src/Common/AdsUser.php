<?php

namespace LaravelGoogleAds\Common;

/**
 * User class for all API modules using the Ads API.
 * @package GoogleApiAdsCommon
 * @subpackage Lib
 */
abstract class AdsUser extends \AdsUser
{
    protected $config = [];

    private $libVersion;
    private $libName;

    private $requestHeaderElements;
    private $defaultServer;
    private $defaultVersion;
    private $logsDirectory;
    private $soapCompression;
    private $soapCompressionLevel;
    private $wsdlCache;
    private $forceHttpVersion;
    private $forceAddXsiTypes;
    private $authServer;
    private $oauth2Handler;

    public function __construct()
    {
        parent::__construct();

        $this->requestHeaderElements = [];

        $config = $this->getConfig();

        $this->libVersion = $config['common']['build']['LIB_VERSION'];
        $this->libName = $config['common']['build'];
    }

    /**
     * @param string $settings
     * @param string $defaultVersion
     * @param string $defaultServer
     * @param string $defaultLogsDir
     * @param string $logsRelativePathBase
     */
    public function LoadSettings(
        $settings,
        $defaultVersion,
        $defaultServer,
        $defaultLogsDir,
        $logsRelativePathBase
    ) {
        // Set no time limit for PHP operations.
        set_time_limit(0);
        ini_set('default_socket_timeout', 480);

        // Logging settings.
        $pathRelative = $this->GetSetting($settings, 'LOGGING',
            'PATH_RELATIVE', false);
        $libLogDirPath = $this->GetSetting($settings, 'LOGGING',
            'LIB_LOG_DIR_PATH', $defaultLogsDir);
        $relativePath = realpath($logsRelativePathBase . '/' . $libLogDirPath);

        if ($pathRelative && $relativePath) {
            $this->logsDirectory = $relativePath;
        } elseif (!$pathRelative && $libLogDirPath) {
            $this->logsDirectory = $libLogDirPath;
        } else {
            $this->logsDirectory = $defaultLogsDir;
        }
        $this->InitLogs();

        // Server settings.
        $this->defaultVersion = $this->GetSetting(
            $settings,
            'SERVER',
            'DEFAULT_VERSION',
            $defaultVersion
        );

        $this->defaultServer = $this->GetSetting(
            $settings,
            'SERVER',
            'DEFAULT_SERVER',
            $defaultServer
        );

        // SOAP settings.
        $this->soapCompression = (bool) $this->GetSetting(
            $settings,
            'SOAP',
            'COMPRESSION',
            true
        );

        $this->soapCompressionLevel = $this->GetSetting(
            $settings,
            'SOAP',
            'COMPRESSION_LEVEL',
            1
        );

        if ($this->soapCompressionLevel < 1 || $this->soapCompressionLevel > 9) {
            $this->soapCompressionLevel = 1;
        }

        $this->wsdlCache = (int) $this->GetSetting(
            $settings,
            'SOAP',
            'WSDL_CACHE',
            WSDL_CACHE_NONE
        );

        if ($this->wsdlCache < 0 || $this->wsdlCache > 3) {
            $this->wsdlCache = WSDL_CACHE_NONE;
        }
        $forceHttpVersion = $this->GetSetting(
            $settings,
            'SOAP',
            'FORCE_HTTP_VERSION'
        );

        $this->forceHttpVersion = $forceHttpVersion === null ? null : (float) $forceHttpVersion;

        $forceAddXsiTypes = $this->GetSetting(
            $settings,
            'SOAP',
            'FORCE_ADD_XSI_TYPES'
        );

        $this->forceAddXsiTypes = $forceAddXsiTypes === null ? false : (bool) $forceAddXsiTypes;

        // Proxy settings.
        $proxyHost = $this->GetSetting(
            $settings,
            'PROXY',
            'HOST'
        );

        if (isset($proxyHost)) {
            $this->Define(
                'HTTP_PROXY_HOST',
                $proxyHost
            );
        }
        $proxyPort = $this->GetSetting(
            $settings,
            'PROXY',
            'PORT'
        );

        if (isset($proxyPort)) {
            $this->Define('HTTP_PROXY_PORT', (int) $proxyPort);
        }

        $proxyUser = $this->GetSetting(
            $settings,
            'PROXY',
            'USER'
        );

        if (isset($proxyUser)) {
            $this->Define(
                'HTTP_PROXY_USER',
                $proxyUser
            );
        }
        $proxyPassword = $this->GetSetting(
            $settings,
            'PROXY',
            'PASSWORD'
        );

        if (isset($proxyPassword)) {
            $this->Define(
                'HTTP_PROXY_PASSWORD',
                $proxyPassword
            );
        }

        // Auth settings.
        $this->authServer = $this->GetSetting($settings, 'AUTH', 'AUTH_SERVER',
            'https://accounts.google.com');

        // OAuth2
        $this->oauth2Handler = $this->GetDefaultOAuth2Handler(
            $this->GetSetting(
                $settings,
                'AUTH',
                'OAUTH2_HANDLER_CLASS'
            )
        );

        // SSL settings.
        $sslVerifyPeer = $this->GetSetting($settings, 'SSL', 'VERIFY_PEER');
        if (isset($sslVerifyPeer)) {
            $this->Define('SSL_VERIFY_PEER', $sslVerifyPeer);
        }
        $sslVerifyHost = $this->GetSetting($settings, 'SSL', 'VERIFY_HOST');
        if (isset($sslVerifyHost)) {
            $this->Define('SSL_VERIFY_HOST', (int) $sslVerifyHost);
        }
        $sslCaPath = $this->GetSetting($settings, 'SSL', 'CA_PATH');
        if (isset($sslCaPath)) {
            $this->Define('SSL_CA_PATH', $sslCaPath);
        }
        $sslCaFile = $this->GetSetting($settings, 'SSL', 'CA_FILE');
        if (isset($sslCaFile)) {
            $this->Define('SSL_CA_FILE', $sslCaFile);
        }
    }

    /**
     * Gets the value for a given setting based on the contents of the parsed INI
     * file.
     * @param array $settings the parsed settings INI file
     * @param string $section the name of the section containing the setting
     * @param string $name the name of the setting
     * @param mixed $default the default value of the setting
     * @return string the value of the setting
     */
    private function GetSetting($settings, $section, $name, $default = null) {
        if (!$settings || !array_key_exists($section, $settings)
            || !array_key_exists($name, $settings[$section])
            || $settings[$section][$name] === null
            || $settings[$section][$name] === '') {
            return $default;
        }
        return $settings[$section][$name];
    }

    /**
     * Define a constant if it isn't already defined. If it is defined but the
     * value is different then attempt to redefine it, which will fail and throw
     * the appropriate error.
     * @param string $name the name of the constant
     * @param string $value the value of the constant
     */
    private function Define($name, $value) {
        if (!defined($name) || (constant($name) != $value)) {
            define($name, $value);
        }
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param array $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }
}

