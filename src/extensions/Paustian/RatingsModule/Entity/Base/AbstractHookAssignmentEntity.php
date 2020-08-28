<?php

/**
 * Ratings.
 *
 * @copyright Timothy Paustian (Paustian)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Timothy Paustian <tdpaustian@gmail.com>.
 *
 * @see https://www.microbiologytext.com/
 * @see https://ziku.la
 *
 * @version Generated by ModuleStudio 1.5.0 (https://modulestudio.de).
 */

declare(strict_types=1);

namespace Paustian\RatingsModule\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Zikula\Bundle\CoreBundle\Doctrine\EntityAccess;

/**
 * Entity base class for hooked object assignments.
 *
 * The following annotation marks it as a mapped superclass so subclasses
 * inherit orm properties.
 *
 * @ORM\MappedSuperclass
 */
abstract class AbstractHookAssignmentEntity extends EntityAccess
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", unique=true)
     * @Assert\Type(type="integer")
     * @Assert\NotNull
     * @Assert\LessThan(value=1000000000)
     *
     * @var int
     */
    protected $id = 0;
    
    /**
     * @ORM\Column(length=255)
     * @Assert\NotBlank
     * @Assert\Length(min="0", max="255", allowEmptyString="false")
     *
     * @var string
     */
    protected $subscriberOwner = '';
    
    /**
     * @ORM\Column(length=255)
     * @Assert\NotBlank
     * @Assert\Length(min="0", max="255", allowEmptyString="false")
     *
     * @var string
     */
    protected $subscriberAreaId = '';
    
    /**
     * @ORM\Column(type="integer")
     * @Assert\Type(type="integer")
     * @Assert\NotBlank
     * @Assert\NotEqualTo(value=0)
     * @Assert\LessThan(value=100000000000)
     *
     * @var int
     */
    protected $subscriberObjectId = 0;
    
    /**
     * @ORM\Column(type="array")
     * @Assert\NotNull
     * @Assert\Type(type="array")
     *
     * @var array
     */
    protected $subscriberUrl = [];
    
    /**
     * @ORM\Column(length=255)
     * @Assert\NotBlank
     * @Assert\Length(min="0", max="255", allowEmptyString="false")
     *
     * @var string
     */
    protected $assignedEntity = '';
    
    /**
     * @ORM\Column(length=255)
     * @Assert\NotBlank
     * @Assert\Length(min="0", max="255", allowEmptyString="false")
     *
     * @var string
     */
    protected $assignedId = '';
    
    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank
     *
     * @var \DateTime
     */
    protected $updatedDate;
    

    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function setId(?int $id = null): void
    {
        if ((int) $this->id !== $id) {
            $this->id = $id;
        }
    }
    
    public function getSubscriberOwner(): string
    {
        return $this->subscriberOwner;
    }
    
    public function setSubscriberOwner(string $subscriberOwner): void
    {
        if ($this->subscriberOwner !== $subscriberOwner) {
            $this->subscriberOwner = $subscriberOwner ?? '';
        }
    }
    
    public function getSubscriberAreaId(): string
    {
        return $this->subscriberAreaId;
    }
    
    public function setSubscriberAreaId(string $subscriberAreaId): void
    {
        if ($this->subscriberAreaId !== $subscriberAreaId) {
            $this->subscriberAreaId = $subscriberAreaId ?? '';
        }
    }
    
    public function getSubscriberObjectId(): int
    {
        return $this->subscriberObjectId;
    }
    
    public function setSubscriberObjectId(int $subscriberObjectId): void
    {
        if ((int) $this->subscriberObjectId !== $subscriberObjectId) {
            $this->subscriberObjectId = $subscriberObjectId;
        }
    }
    
    public function getSubscriberUrl(): array
    {
        return $this->subscriberUrl;
    }
    
    public function setSubscriberUrl(array $subscriberUrl): void
    {
        if ($this->subscriberUrl !== $subscriberUrl) {
            $this->subscriberUrl = $subscriberUrl ?? [];
        }
    }
    
    public function getAssignedEntity(): string
    {
        return $this->assignedEntity;
    }
    
    public function setAssignedEntity(string $assignedEntity): void
    {
        if ($this->assignedEntity !== $assignedEntity) {
            $this->assignedEntity = $assignedEntity ?? '';
        }
    }
    
    public function getAssignedId(): string
    {
        return $this->assignedId;
    }
    
    public function setAssignedId(string $assignedId): void
    {
        if ($this->assignedId !== $assignedId) {
            $this->assignedId = $assignedId ?? '';
        }
    }
    
    public function getUpdatedDate(): \DateTimeInterface
    {
        return $this->updatedDate;
    }
    
    public function setUpdatedDate(\DateTimeInterface $updatedDate): void
    {
        if ($this->updatedDate !== $updatedDate) {
            if (
                !(null === $updatedDate && empty($updatedDate))
                && !(is_object($updatedDate) && $updatedDate instanceof \DateTimeInterface)
            ) {
                $updatedDate = new \DateTime($updatedDate);
            }
            
            if (null === $updatedDate || empty($updatedDate)) {
                $updatedDate = new \DateTime();
            }
            
            if ($this->updatedDate !== $updatedDate) {
                $this->updatedDate = $updatedDate;
            }
        }
    }
}
