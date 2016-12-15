<?php
/**
 * Created by PhpStorm.
 * User: DM0C60544
 * Date: 15/12/2016
 * Time: 10:41 AM
 */

namespace Del\Form\Traits;


trait HasOptionsTrait
{
    /** @var array $options */
    private $options = [];

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return array
     */
    public function getOption($key)
    {
        return $this->options[$key];
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function setOption($key, $value)
    {
        $this->options[$key] = $value;
        return $this;
    }
}