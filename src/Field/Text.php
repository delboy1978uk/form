<?php
/**
 * User: delboy1978uk
 * Date: 19/11/2016
 * Time: 21:37
 */

namespace Del\Form\Field;

class Text extends FieldAbstract
{
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


}