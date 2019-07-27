<?php
/**
 * User: delboy1978uk
 * Date: 04/12/2016
 * Time: 17:24
 */

namespace Del\Form\Field\Text;

use Del\Form\Field\Text;
use Del\Form\Filter\Adapter\FilterAdapterZf;
use Zend\Filter\ToInt;

class Integer extends Text
{
    public function init()
    {
        parent::init();
        $this->setAttribute('type', 'email');
        $this->setAttribute('placeholder', 'Enter an email address..');
        $toIntegerFilter = new FilterAdapterZf(new ToInt());
        $this->addFilter($toIntegerFilter);
    }
}