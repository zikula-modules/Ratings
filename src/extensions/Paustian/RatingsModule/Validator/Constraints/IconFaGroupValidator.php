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
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Contracts\Translation\TranslatorInterface;
use Zikula\Bundle\CoreBundle\Translation\TranslatorTrait;

define('NO_NEED_FOR_FA_STRING', 1);
define('MUST_BEGIN_WITH_FA', 2);

/**
 * List entry validator.
 */
class IconFaGroupValidator extends ConstraintValidator
{
    use TranslatorTrait;

    public function __construct(TranslatorInterface $translator)
    {
        $this->setTranslator($translator);
    }

    public function validate($value, Constraint $constraint)
    {
        $data = $this->context->getRoot()->getData();
        // if this is empty, then check to make sure the url icons are full
        if (!preg_match('/\S/', $value)) {
            if (
                !preg_match('/\S/', $data->getIconUrl())
                || !preg_match('/\S/', $data->getHalfIconUrl())
                || !preg_match('/\S/', $data->getEmptyIconUrl())
            ) {
                $this->context->buildViolation($this->trans('If this string is empty then the urlIcon strings have to be specified.'))->addViolation();
            }
            // if the string is empty, we don't need to check further
            return;
        }

        // lets see if we have the right format
        $faMessage = $this->isFontAwesome($value);
        if (NO_NEED_FOR_FA_STRING === $faMessage) {
            $this->context->buildViolation($this->trans('You do not need to include the initial fa in the class string.'))->addViolation();
        }
        if (MUST_BEGIN_WITH_FA === $faMessage) {
            $this->context->buildViolation($this->trans('Font Awesome icons must begin with fa-'))->addViolation();
        }
    }

    // check to see if the font awesome string is available.
    private function isFontAwesome($value)
    {
        if (preg_match("/^fa .*/", $value)) {
            return NO_NEED_FOR_FA_STRING;
        }
        if (!preg_match("/^fa-.*/", $value)) {
            return MUST_BEGIN_WITH_FA;
        }
    }
}
