<?php

declare(strict_types=1);

namespace Del\Form\Validator;

use Laminas\Validator\NotEmpty as ZfNotEmpty;

class NotEmpty extends ZfNotEmpty implements ValidatorInterface {}
