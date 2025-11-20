<?php

declare(strict_types=1);

namespace Del\Test\Form\Field;

use Codeception\Test\Unit;
use Del\Form\Field\SubForm;
use Del\Form\Field\Text;
use Del\Form\Form;
use Del\Form\FormInterface;

class SubFormTest extends Unit
{
    private FormInterface $form;

    protected function setUp(): void
    {
        $firstname = new Text('firstName');
        $firstname->setRequired(true);
        $personForm = new Form('person');
        $personForm->addField($firstname);
        $personForm->addField(new Text('lastName'));
        $subform = new SubForm('person', $personForm);
        $subform->isRequired(true);
        $form = new Form('user');
        $form->addField(new Text('username'));
        $form->addField(new Text\EmailAddress('email'));
        $form->addField($subform);
        $this->form = $form;
    }

    public function testValidSubForm(): void
    {
        $data = [
            'username' => 'johndoe',
            'email' => 'john_doe@mail.org',
            'person' => [
                'firstName' => 'John',
                'lastName' => 'Doe',
            ],
        ];

        $this->form->populate($data);
        $this->assertTrue($this->form->isValid());
    }

    public function testInvalidSubForm(): void
    {
        $data = [
            'username' => 'johndoe',
            'email' => 'john_doe@mail.org',
            'person' => [
                'lastName' => 'Doe',
            ],
        ];

        $this->form->populate($data);
        $this->assertFalse($this->form->isValid());
    }
}
