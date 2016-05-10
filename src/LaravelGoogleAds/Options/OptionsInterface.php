<?php

namespace LaravelGoogleAds\Options;

interface OptionsInterface
{
    public function __construct(array $options = []);

    /**
     * @param string $key
     * @return mixed|null
     */
    public function get($key);

    /**
     * @return array
     */
    public function getDefaults();

    /**
     * @param array $defaults
     */
    public function setDefaults(array $defaults);

    /**
     * @return array
     */
    public function getOptions();

    /**
     * @param array $options
     */
    public function setOptions(array $options);
}
