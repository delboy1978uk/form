<?php
/**
 * User: delboy1978uk
 * Date: 05/12/2016
 * Time: 00:57
 */

namespace Del\Form\Field;

use Del\Form\Renderer\Field\SelectRender;

class Select extends FieldAbstract
{
    /** @var array $options */
    private $options = [];

    /**
     * @return string
     */
    public function getTag()
    {
        return 'select';
    }


    public function init()
    {
        $this->setAttribute('type', 'text');
        $this->setAttribute('class', 'form-control');
        $this->setRenderer(new SelectRender());
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