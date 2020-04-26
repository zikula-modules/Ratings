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

namespace Paustian\RatingsModule\Entity\Factory\Base;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use InvalidArgumentException;
use Paustian\RatingsModule\Entity\Factory\EntityInitialiser;
use Paustian\RatingsModule\Entity\RatingSystemEntity;
use Paustian\RatingsModule\Entity\RatingEntity;
use Paustian\RatingsModule\Helper\CollectionFilterHelper;

/**
 * Factory class used to create entities and receive entity repositories.
 */
abstract class AbstractEntityFactory
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var EntityInitialiser
     */
    protected $entityInitialiser;

    /**
     * @var CollectionFilterHelper
     */
    protected $collectionFilterHelper;

    public function __construct(
        EntityManagerInterface $entityManager,
        EntityInitialiser $entityInitialiser,
        CollectionFilterHelper $collectionFilterHelper
    ) {
        $this->entityManager = $entityManager;
        $this->entityInitialiser = $entityInitialiser;
        $this->collectionFilterHelper = $collectionFilterHelper;
    }

    /**
     * Returns a repository for a given object type.
     *
     * @param string $objectType Name of desired entity type
     *
     * @return EntityRepository The repository responsible for the given object type
     */
    public function getRepository($objectType)
    {
        $entityClass = 'Paustian\\RatingsModule\\Entity\\' . ucfirst($objectType) . 'Entity';

        /** @var EntityRepository $repository */
        $repository = $this->getEntityManager()->getRepository($entityClass);
        $repository->setCollectionFilterHelper($this->collectionFilterHelper);

        return $repository;
    }

    /**
     * Creates a new ratingSystem instance.
     *
     * @return RatingSystemEntity The newly created entity instance
     */
    public function createRatingSystem()
    {
        $entity = new RatingSystemEntity();

        $this->entityInitialiser->initRatingSystem($entity);

        return $entity;
    }

    /**
     * Creates a new rating instance.
     *
     * @return RatingEntity The newly created entity instance
     */
    public function createRating()
    {
        $entity = new RatingEntity();

        $this->entityInitialiser->initRating($entity);

        return $entity;
    }

    /**
     * Returns the identifier field's name for a given object type.
     *
     * @param string $objectType The object type to be treated
     *
     * @return string Primary identifier field name
     */
    public function getIdField($objectType = '')
    {
        if (empty($objectType)) {
            throw new InvalidArgumentException('Invalid object type received.');
        }
        $entityClass = 'PaustianRatingsModule:' . ucfirst($objectType) . 'Entity';
    
        $meta = $this->getEntityManager()->getClassMetadata($entityClass);
    
        return $meta->getSingleIdentifierFieldName();
    }
    
    /**
     * Returns the entity manager.
     *
     * @return EntityManagerInterface
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }
    
    /**
     * Returns the entity initialiser.
     *
     * @return EntityInitialiser
     */
    public function getEntityInitialiser()
    {
        return $this->entityInitialiser;
    }
}
