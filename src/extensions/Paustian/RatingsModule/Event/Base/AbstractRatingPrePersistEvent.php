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

namespace Paustian\RatingsModule\Event\Base;

use Paustian\RatingsModule\Entity\RatingEntity;

/**
 * Event base class for filtering rating processing.
 */
abstract class AbstractRatingPrePersistEvent
{
    /**
     * @var RatingEntity Reference to treated entity instance.
     */
    protected $rating;

    public function __construct(RatingEntity $rating)
    {
        $this->rating = $rating;
    }

    /**
     * @return RatingEntity
     */
    public function getRating(): RatingEntity
    {
        return $this->rating;
    }
}
