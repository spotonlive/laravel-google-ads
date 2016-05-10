<?php

namespace LaravelGoogleAds\Options;

class Options implements OptionsInterface
{
    /** @var array */
    protected $defaults = [];

    /** @var array */
    protected $options = [];

    public function __construct(array $options = [])
    {
        $this->options = $this->mergeAssociative($this->defaults, $options);
    }

    /**
     * Merge associative array
     *
     * @param array $a
     * @param array $b
     * @return array
     */
    public function mergeAssociative(array $a, array $b)
    {
        $mergedArray = $a;

        foreach ($b as $k => $v) {
            $mergedArray[$k] = $v;

            if (is_array($v) && isset($a[$k]) && is_array($a[$k])) {
                $mergedArray[$k] = $this->mergeAssociative($a[$k], $v);
            }
        }

        return $mergedArray;
    }

    /**
     * @return array
     */
    public function getDefaults()
    {
        return $this->defaults;
    }

    /**
     * @param array $defaults
     */
    public function setDefaults(array $defaults)
    {
        $this->defaults = $defaults;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * Get value from key
     *
     * @param string $key
     * @return array|mixed|null|string
     */
    public function get($key)
    {
        if (!array_key_exists($key, $this->options)) {
            return null;
        }

        /** @var string|array|mixed $options */
        $options = $this->options[$key];

        return $options;
    }
}
