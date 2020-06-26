<?php

/**
 * Ratings.
 *
 * @copyright Timothy Paustian (Paustian)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Timothy Paustian <tdpaustian@gmail.com>.
 * @see https://www.microbiologytext.com/
 * @see https://ziku.la
 * @version Generated by ModuleStudio 1.5.0 (https://modulestudio.de).
 */

declare(strict_types=1);

namespace Paustian\RatingsModule\Entity\Repository;

use Paustian\RatingsModule\Entity\Repository\Base\AbstractRatingRepository;

/**
 * Repository class used to implement own convenience methods for performing certain DQL queries.
 *
 * This is the concrete repository class for rating entities.
 */
class RatingRepository extends AbstractRatingRepository
{
    /**
     * @param string $module
     * @param string $moduleItem
     * @param int $user
     * @return array
     */
    public function getRatingForItem($module, $moduleItem, $user=0)
    {
        $qb = $this->getListQueryBuilder();
        $qb->andWhere('tbl.objectId = :objId')
            ->setParameter('objId', $moduleItem);
        $qb->andWhere('tbl.moduleName = :module')
            ->setParameter('module', $module);
        if (0 !== $user) {
            $qb->andWhere('tbl.userId = :user')
                ->setParameter('user', $user);
        }
        $query = $this->getQueryFromBuilder($qb);

        return $this->retrieveCollectionResult($query, false);
    }
}
