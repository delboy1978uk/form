<?php

declare(strict_types=1);

namespace Del\Test\Form;

use DateTime;
use Del\Form\Field\Attributes\Field;

class TestEntity
{
    #[Field('integer|required')]
    public int $id = 6;

    #[Field('email|required|min:2|max:50')]
    public string $email = 'man@work.com';

    #[Field('password|required|min:2|max:50')]
    public string $password = 'xxxx';

    #[Field('checkbox')]
    public bool $isAdmin = true;

    #[Field('float')]
    public float $price = 9.99;

    #[Field('datetime')]
    public DateTime $dateTime;

    #[Field('hidden')]
    public string $hidden = 'secret';

    #[Field('textarea')]
    public string $blurb = 'lorem ipsum dolor sit amet';

    #[Field('file|file_ext:gif|upload:tests/_output|mime:image/gif')]
    public string $file = 'tests/_data/fol.gif';

    #[Field('multiselect')]
    public string $multiselect = 'value1';

    #[Field('select')]
    public string $select = 'value2';

    #[Field('radio')]
    public string $radio = 'value3';

    public function __construct()
    {
        $this->dateTime = new DateTime();
    }
}
