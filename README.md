# form
[![Latest Stable Version](https://poser.pugx.org/delboy1978uk/form/v/stable)](https://packagist.org/packages/delboy1978uk/form) [![Total Downloads](https://poser.pugx.org/delboy1978uk/form/downloads)](https://packagist.org/packages/delboy1978uk/form) [![Latest Unstable Version](https://poser.pugx.org/delboy1978uk/form/v/unstable)](https://packagist.org/packages/delboy1978uk/form) [![License](https://poser.pugx.org/delboy1978uk/form/license)](https://packagist.org/packages/delboy1978uk/form)<br />
[![Build Status](https://travis-ci.org/delboy1978uk/form.png?branch=master)](https://travis-ci.org/delboy1978uk/form) [![Code Coverage](https://scrutinizer-ci.com/g/delboy1978uk/form/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/delboy1978uk/form/?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/delboy1978uk/form/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/delboy1978uk/form/?branch=master)<br />
A super easy Bootstrap ready HTML form generator for PHP
## Installation
 Install via composer
 ```
 composer require delboy1978uk/form
 ```
 ## Usage
Firstly, "use" all the classes you'll need for your form. Then create your form and fields.
```php
<?php

use Del\Form\Form;
use Del\Form\Field\Text;
use Del\Form\Field\CheckBox;
use Del\Form\Field\Submit;

// Create a form
$form = new Form('registration');

// Create a username, email, spamlist checkbox, and submit button.
$userName = new Text('username');
$email = new Text('email');
$spamMe = new CheckBox('spam');
$submit = new Submit('submit');

// Set labels
$userName->setLabel('User Name');
$email->setLabel('Email Address');
$spamMe->setLabel('Join our (and 3rd parties) email list(s)');

// Add the fields to the form
$form->addField($userName)
      ->addField($email)
      ->addField($spamMe)
      ->addField($submit);

// Render the form
echo $form->render();
```
## Fitering and validating input
For filtering input, add a Del\Form\Filter\Interface to your field object. For validating the filtered input, add a 
Del\Form\Validator\ValidatorInterface. Currently there is an adapter for Zend\Filter and Zend\Validate, but feel free to 
write an adapter for you favourite library.
```php
<?php

// A text field, and an adapter for the filters and the validators
use Del\Form\Field\Text;
use Del\Form\Filter\Adapter\FilterAdapterZf;
use Del\Form\Validator\Adapter\ValidatorAdapterZf;

// Some sensible default string filters for username/email fields 
use Zend\Filter\StripTags;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToLower;

// Validation rules
use Zend\Validator\NotEmpty;
use Zend\Validator\EmailAddress;

// Create the field
$email = new Text('email');

// Create the filters
$stripTags = new FilterAdapterZf(new StripTags());
$trim = new FilterAdapterZf(new StringTrim());
$lowerCase = new FilterAdapterZf(new StringToLower());

// Create the validators
$notEmpty = new ValidatorAdapterZf(new NotEmpty());
$emailAddress = new ValidatorAdapterZf(new EmailAddress());

// Add them to the field
$email->addFilter($stripTags)
      ->addFilter($trim)
      ->addFilter($lowerCase)
      ->addValidator($notEmpty)
      ->addValidator($emailAddress);
```
## Setting and getting values
Del\Form\FormInterface has a populate method which takes an array (usually the post data, but not necessarily;-).  
```php
<?php
if (isset($_POST['submit'])) { // or ask your request object ;-) 
    $data = $_POST;
    $form->populate($data);
    if ($form->isValid()) {
        $filteredData = $form->getValues();
    }
}
```
After populate has been called, if you call Form::render(), it will display any validation error messages.
##What's next?
*Form rendering strategies (plain form, bootstrap inline form, other layouts)
*Required Fields
*Facade Fields (e.g. an email field extending Text and adding email validation, or a credit card field. etc.)

