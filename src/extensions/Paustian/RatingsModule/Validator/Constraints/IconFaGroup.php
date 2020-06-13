<?php

/**
 * Ratings.
 *
 * @copyright Timothy Paustian (Paustian)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Timothy Paustian <tdpaustian@gmail.com>.
 * @see https://www.microbiologytext.com/
 * @see https://ziku.la
 * @version Generated by ModuleStudio 1.4.0 (https://modulestudio.de).
 */

declare(strict_types=1);

namespace Paustian\RatingsModule\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IconFaGroup extends Constraint
{
    /**
     * @var string
     */
    public $message = 'You need to fill out either all the Icon fa text boxes with Font Awesome icons strings or all the Icon url text boxes paths to images to be used.';

    public function validatedBy()
    {
        return 'paustian_ratings_module.validator.icon_fa_group.validator';
    }
}
