<?php

/*
 * This file is part of the Zikula package.
 *
 * Copyright Zikula - https://ziku.la/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Paustian\RatingsModule\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\MissingOptionsException;
use Symfony\Component\Validator\Constraints\GreaterThan;

/**
 * @Annotation
 */
class IconFaGroup extends Constraint
{
    /**
     * @var string
     */
    public $message = 'You need to fill out either all the Icon fa text boxes with font awesome icons strings or all the Icon url text boxes paths to images to be used.';


    public function __construct($options = null)
    {
        parent::__construct($options);
    }

    public function validatedBy()
    {
        return 'paustian_ratings_module.validator.icon_fa_group.validator';
    }
}

