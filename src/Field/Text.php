<?php
/**
 * User: delboy1978uk
 * Date: 19/11/2016
 * Time: 21:37
 */

namespace Del\Form\Field;

class Text extends FieldAbstract
{
    /** @var string $placeholder */
    private $placeholder;

    /**
     * @return string
     */
    public function getTag()
    {
        return 'input';
    }

    /**
     * @return string
     */
    public function getTagType()
    {
        return 'text';
    }

    /**
     * @return string
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * @param string $placeholder
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;
    }


}