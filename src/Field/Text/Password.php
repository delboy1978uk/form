<?php
/**
 * User: delboy1978uk
 * Date: 19/11/2016
 * Time: 21:37
 */

namespace Del\Form\Field\Text;

use Del\Form\Field\Text;

class Password extends Text
{
    public function init()
    {
        parent::init();
        $this->setAttribute('type', 'password');
    }
}