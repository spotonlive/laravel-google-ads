<?php

namespace LaravelGoogleAds\AdWords;

use LaravelGoogleAds\Exceptions\ValidationException;

class AdWordsUser extends \AdWordsUser
{
    /** @var string */
    protected $libName;

    /** @var string */
    protected $libVersion;

    /** @var array|null */
    protected $config = null;

    private $defaultServer;
    private $defaultVersion;
    private $logsDirectory;
    private $soapCompression;
    private $soapCompressionLevel;
    private $wsdlCache;
    private $forceHttpVersion;
    private $forceAddXsiTypes;
    private $authServer;
    private $oauth2Info;
    private $oauth2Handler;

    public function __construct(
        $developerToken = null,
        $userAgent = null,
        $clientCustomerId = null,
        $oauth2Info = null
    ) {
        $config = $this->getConfig();

        $this->libName = $config['adWords']['build']['LIB_NAME'];
        $this->libVersion = $config['common']['build']['LIB_VERSION'];

        $apiProperties = $config['adWords']['api.properties'];

        $versions = explode(',', $apiProperties['api.versions']);
        $defaultVersion = $versions[count($versions) - 1];
        $defaultServer = $apiProperties['api.server'];

        $authConfig = $config['adWords']['auth'];

        $developerToken = $this->GetAuthVarValue(
            $developerToken,
            'developerToken',
            $authConfig
        );

        $userAgent = $this->GetAuthVarValue(
            $userAgent,
            self::USER_AGENT_HEADER_NAME,
            $authConfig
        );

        $clientCustomerId = $this->GetAuthVarValue(
            $clientCustomerId,
            'clientCustomerId',
            $authConfig
        );

        $oauth2Info = $this->GetAuthVarValue(
            $oauth2Info,
            'OAUTH2',
            $authConfig
        );

        $scopes = [];

        if (isset($oauth2Info['oAuth2AdditionalScopes'])) {
            $scopes = explode(',', $oauth2Info['oAuth2AdditionalScopes']);
        }

        $scopes[] = self::OAUTH2_SCOPE;

        $clientId = $this->GetAuthVarValue(
            null,
            'clientId',
            $authConfig
        );

        if ($clientId !== null) {
            throw new ValidationException(
                'clientId',
                $clientId,
                'The authentication key "clientId" has been changed to'
                . ' "clientCustomerId", please use that instead.'
            );
        }

        $this->SetOAuth2Info($oauth2Info);
        $this->SetUserAgent($userAgent);
        $this->SetClientLibraryUserAgent($userAgent);
        $this->SetClientCustomerId($clientCustomerId);
        $this->SetDeveloperToken($developerToken);
        $this->SetScopes($scopes);

        $settingsConfig = $config['adWords']['settings'];

        $this->LoadSettings(
            $settingsConfig,
            $defaultVersion,
            $defaultServer,
            getcwd(), dirname(__FILE__)
        );
    }

    /**
     * Loads the settings for this client library. If the settings INI file
     * located at <var>$settingsPath</var> cannot be loaded, then the
     * parameters passed into this method are used.
     * @param string $settingsPath the path to the settings INI file
     * @param string $defaultVersion the default version if the settings INI file
     *     cannot be loaded
     * @param string $defaultServer the default server if the settings INI file
     *     cannot be loaded
     * @param string $defaultLogsDir the default logs directory if the settings
     *     INI file cannot be loaded
     * @param string $logsRelativePathBase the relative path base for the logs
     *     directory
     */
    public function LoadSettings(
        $settings,
        $defaultVersion,
        $defaultServer,
        $defaultLogsDir,
        $logsRelativePathBase
    ) {
        set_time_limit(0);
        ini_set('default_socket_timeout', 480);

        // Logging settings.
        $pathRelative = $this->GetSetting(
            $settings,
            'LOGGING',
            'PATH_RELATIVE',
            false
        );

        $libLogDirPath = $this->GetSetting(
            $settings,
            'LOGGING',
            'LIB_LOG_DIR_PATH',
            $defaultLogsDir
        );

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
        $proxyHost = $this->GetSetting($settings, 'PROXY', 'HOST');

        if (isset($proxyHost)) {
            $this->Define('HTTP_PROXY_HOST', $proxyHost);
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
        $this->authServer = $this->GetSetting(
            $settings,
            'AUTH',
            'AUTH_SERVER',
            'https://accounts.google.com'
        );

        // OAuth2.
        $this->oauth2Handler = $this->GetDefaultOAuth2Handler(
            $this->GetSetting(
                $settings,
                'AUTH',
                'OAUTH2_HANDLER_CLASS'
            )
        );

        // SSL settings.
        $sslVerifyPeer = $this->GetSetting(
            $settings,
            'SSL',
            'VERIFY_PEER'
        );

        if (isset($sslVerifyPeer)) {
            $this->Define(
                'SSL_VERIFY_PEER',
                $sslVerifyPeer
            );
        }
        $sslVerifyHost = $this->GetSetting(
            $settings,
            'SSL',
            'VERIFY_HOST'
        );

        if (isset($sslVerifyHost)) {
            $this->Define(
                'SSL_VERIFY_HOST',
                (int) $sslVerifyHost
            );
        }
        $sslCaPath = $this->GetSetting(
            $settings,
            'SSL',
            'CA_PATH'
        );

        if (isset($sslCaPath)) {
            $this->Define(
                'SSL_CA_PATH',
                $sslCaPath
            );
        }
        $sslCaFile = $this->GetSetting(
            $settings,
            'SSL',
            'CA_FILE'
        );

        if (isset($sslCaFile)) {
            $this->Define(
                'SSL_CA_FILE',
                $sslCaFile
            );
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
    private function GetSetting($settings, $section, $name, $default = null)
    {
        if (
            !$settings || !array_key_exists($section, $settings)
            || !array_key_exists($name, $settings[$section])
            || $settings[$section][$name] === null
            || $settings[$section][$name] === ''
        ) {
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
    private function Define($name, $value)
    {
        if (
            !defined($name)
            || (constant($name) != $value)
        ) {
            define($name, $value);
        }
    }

    /**
     * Validate user
     *
     * @throws ValidationException
     */
    public function ValidateUser()
    {
        if ($this->GetOAuth2Info() === null) {
            throw new ValidationException(
                'OAuth2Info',
                null,
                'OAuth 2.0 configuration is required.'
            );
        }

        $this->ValidateOAuth2Info();

        if (
            $this->GetUserAgent() === null
            || trim($this->GetUserAgent()) === ''
            || strpos($this->GetUserAgent(), self::DEFAULT_USER_AGENT) !== false
        ) {
            throw new ValidationException(
                'userAgent',
                null,
                sprintf(
                    "The property userAgent is required and cannot be  null, the empty string, or the default [%s]",
                    self::DEFAULT_USER_AGENT
                )
            );
        }

        if (is_null($this->GetDeveloperToken()))
        {
            throw new ValidationException(
                'developerToken',
                null,
                'developerToken is required and cannot be null.'
            );
        }
    }

    /**
     * Validate OAuth2Info
     *
     * @throws ValidationException
     */
    protected function ValidateOAuth2Info()
    {
        $requiredFields = [
            'client_id',
            'client_secret',
        ];

        foreach ($requiredFields as $field) {
            if (empty($this->oauth2Info[$field])) {
                throw new ValidationException(
                    $field,
                    null,
                    sprintf(
                        '%s is required.',
                        $field
                    )
                );
            }
        }

        if (
            empty($this->oauth2Info['access_token'])
            && empty($this->oauth2Info['refresh_token'])
        ) {
            throw new ValidationException(
                'refresh_token',
                null,
                'Either the refresh_token or the access_token is required.'
            );
        }
    }

    /**
     * @return mixed
     */
    public function getOauth2Handler()
    {
        return $this->oauth2Handler;
    }

    /**
     * @param mixed $oauth2Handler
     */
    public function setOauth2Handler($oauth2Handler)
    {
        $this->oauth2Handler = $oauth2Handler;
    }

    /**
     * @return mixed
     */
    public function getDefaultServer()
    {
        return $this->defaultServer;
    }

    /**
     * @param mixed $defaultServer
     */
    public function setDefaultServer($defaultServer)
    {
        $this->defaultServer = $defaultServer;
    }

    /**
     * @return mixed
     */
    public function getDefaultVersion()
    {
        return $this->defaultVersion;
    }

    /**
     * @param mixed $defaultVersion
     */
    public function setDefaultVersion($defaultVersion)
    {
        $this->defaultVersion = $defaultVersion;
    }

    /**
     * @return mixed
     */
    public function getLogsDirectory()
    {
        return $this->logsDirectory;
    }

    /**
     * @param mixed $logsDirectory
     */
    public function setLogsDirectory($logsDirectory)
    {
        $this->logsDirectory = $logsDirectory;
    }

    /**
     * @return mixed
     */
    public function getSoapCompression()
    {
        return $this->soapCompression;
    }

    /**
     * @param mixed $soapCompression
     */
    public function setSoapCompression($soapCompression)
    {
        $this->soapCompression = $soapCompression;
    }

    /**
     * @return mixed
     */
    public function getSoapCompressionLevel()
    {
        return $this->soapCompressionLevel;
    }

    /**
     * @param mixed $soapCompressionLevel
     */
    public function setSoapCompressionLevel($soapCompressionLevel)
    {
        $this->soapCompressionLevel = $soapCompressionLevel;
    }

    /**
     * @return mixed
     */
    public function getWsdlCache()
    {
        return $this->wsdlCache;
    }

    /**
     * @param mixed $wsdlCache
     */
    public function setWsdlCache($wsdlCache)
    {
        $this->wsdlCache = $wsdlCache;
    }

    /**
     * @return mixed
     */
    public function getForceHttpVersion()
    {
        return $this->forceHttpVersion;
    }

    /**
     * @param mixed $forceHttpVersion
     */
    public function setForceHttpVersion($forceHttpVersion)
    {
        $this->forceHttpVersion = $forceHttpVersion;
    }

    /**
     * @return mixed
     */
    public function getForceAddXsiTypes()
    {
        return $this->forceAddXsiTypes;
    }

    /**
     * @param mixed $forceAddXsiTypes
     */
    public function setForceAddXsiTypes($forceAddXsiTypes)
    {
        $this->forceAddXsiTypes = $forceAddXsiTypes;
    }

    /**
     * @return mixed
     */
    public function getAuthServer()
    {
        return $this->authServer;
    }

    /**
     * @param mixed $authServer
     */
    public function setAuthServer($authServer)
    {
        $this->authServer = $authServer;
    }

    /**
     * @return mixed
     */
    public function getOauth2Info()
    {
        return $this->oauth2Info;
    }

    /**
     * @param mixed $oauth2Info
     */
    public function setOauth2Info($oauth2Info)
    {
        $this->oauth2Info = $oauth2Info;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        if (is_null($this->config)) {
            $this->config = config('google-ads');
        }

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
