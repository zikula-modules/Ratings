<?php
/**
 * Ratings.
 *
 * @copyright Timothy Paustian (Paustian)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Timothy Paustian <tdpaustian@gmail.com>.
 * @link https://www.microbiologytext.com/
 * @link https://ziku.la
 * @version Generated by ModuleStudio 1.3.2 (https://modulestudio.de).
 */

namespace Paustian\RatingsModule\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Zikula\Core\Doctrine\EntityAccess;
use Zikula\UsersModule\Entity\UserEntity;
use Paustian\RatingsModule\Traits\StandardFieldsTrait;
use Paustian\RatingsModule\Validator\Constraints as RatingsAssert;

/**
 * Entity class that defines the entity structure and behaviours.
 *
 * This is the base entity class for rating entities.
 * The following annotation marks it as a mapped superclass so subclasses
 * inherit orm properties.
 *
 * @ORM\MappedSuperclass
 */
abstract class AbstractRatingEntity extends EntityAccess
{
    /**
     * Hook standard fields behaviour embedding createdBy, updatedBy, createdDate, updatedDate fields.
     */
    use StandardFieldsTrait;

    /**
     * @var string The tablename this object maps to
     */
    protected $_objectType = 'rating';
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", unique=true)
     * @var integer $id
     */
    protected $id = 0;
    
    /**
     * the current workflow state
     *
     * @ORM\Column(length=20)
     * @Assert\NotBlank()
     * @RatingsAssert\ListEntry(entityName="rating", propertyName="workflowState", multiple=false)
     * @var string $workflowState
     */
    protected $workflowState = 'initial';
    
    /**
     * The name of the module that the rating is linked to
     *
     * @ORM\Column(length=64)
     * @Assert\NotBlank()
     * @Assert\Length(min="0", max="64")
     * @var string $moduleName
     */
    protected $moduleName = '';
    
    /**
     * The id of the object that identifies the module instance this rating is linked to
     *
     * @ORM\Column(type="integer")
     * @Assert\Type(type="integer")
     * @Assert\NotBlank()
     * @Assert\NotEqualTo(value=0)
     * @Assert\LessThan(value=100000000000)
     * @var integer $objectId
     */
    protected $objectId = 0;
    
    /**
     * The rating, based upon a scale for the item
     *
     * @ORM\Column(type="smallint")
     * @Assert\Type(type="integer")
     * @Assert\NotBlank()
     * @Assert\NotEqualTo(value=0)
     * @Assert\LessThan(value=1000)
     * @var integer $rating
     */
    protected $rating = 0;
    
    /**
     * The Id of the user who rated the item. 
     *
     * @ORM\ManyToOne(targetEntity="Zikula\UsersModule\Entity\UserEntity")
     * @ORM\JoinColumn(referencedColumnName="uid")
     * @Assert\NotBlank()
     * @var UserEntity $userId
     */
    protected $userId = null;
    
    /**
     * The system to use for rating. For example 1 to 5 stars, or 1 to 10 number scale. 
     *
     * @ORM\Column(type="integer")
     * @Assert\Type(type="integer")
     * @Assert\NotBlank()
     * @Assert\NotEqualTo(value=0)
     * @Assert\LessThan(value=100000000000)
     * @var integer $ratingSystem
     */
    protected $ratingSystem = 1;
    
    
    /**
     * Unidirectional - Many ratingVal [ratings] have one ratingSystemVal [rating system] (OWNING SIDE).
     *
     * @ORM\ManyToOne(targetEntity="Paustian\RatingsModule\Entity\RatingSystemEntity")
     * @ORM\JoinTable(name="paustian_rating_ratingsystem")
     * @Assert\Type(type="Paustian\RatingsModule\Entity\RatingSystemEntity")
     * @var \Paustian\RatingsModule\Entity\RatingSystemEntity $ratingSystemVal
     */
    protected $ratingSystemVal;
    
    
    /**
     * RatingEntity constructor.
     *
     * Will not be called by Doctrine and can therefore be used
     * for own implementation purposes. It is also possible to add
     * arbitrary arguments as with every other class method.
     */
    public function __construct()
    {
        $this->ratingVal = new ArrayCollection();
    }
    
    /**
     * Returns the _object type.
     *
     * @return string
     */
    public function get_objectType()
    {
        return $this->_objectType;
    }
    
    /**
     * Sets the _object type.
     *
     * @param string $_objectType
     *
     * @return void
     */
    public function set_objectType($_objectType)
    {
        if ($this->_objectType != $_objectType) {
            $this->_objectType = isset($_objectType) ? $_objectType : '';
        }
    }
    
    
    /**
     * Returns the id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Sets the id.
     *
     * @param integer $id
     *
     * @return void
     */
    public function setId($id)
    {
        if (intval($this->id) !== intval($id)) {
            $this->id = intval($id);
        }
    }
    
    /**
     * Returns the workflow state.
     *
     * @return string
     */
    public function getWorkflowState()
    {
        return $this->workflowState;
    }
    
    /**
     * Sets the workflow state.
     *
     * @param string $workflowState
     *
     * @return void
     */
    public function setWorkflowState($workflowState)
    {
        if ($this->workflowState !== $workflowState) {
            $this->workflowState = isset($workflowState) ? $workflowState : '';
        }
    }
    
    /**
     * Returns the module name.
     *
     * @return string
     */
    public function getModuleName()
    {
        return $this->moduleName;
    }
    
    /**
     * Sets the module name.
     *
     * @param string $moduleName
     *
     * @return void
     */
    public function setModuleName($moduleName)
    {
        if ($this->moduleName !== $moduleName) {
            $this->moduleName = isset($moduleName) ? $moduleName : '';
        }
    }
    
    /**
     * Returns the object id.
     *
     * @return integer
     */
    public function getObjectId()
    {
        return $this->objectId;
    }
    
    /**
     * Sets the object id.
     *
     * @param integer $objectId
     *
     * @return void
     */
    public function setObjectId($objectId)
    {
        if (intval($this->objectId) !== intval($objectId)) {
            $this->objectId = intval($objectId);
        }
    }
    
    /**
     * Returns the rating.
     *
     * @return integer
     */
    public function getRating()
    {
        return $this->rating;
    }
    
    /**
     * Sets the rating.
     *
     * @param integer $rating
     *
     * @return void
     */
    public function setRating($rating)
    {
        if (intval($this->rating) !== intval($rating)) {
            $this->rating = intval($rating);
        }
    }
    
    /**
     * Returns the user id.
     *
     * @return UserEntity
     */
    public function getUserId()
    {
        return $this->userId;
    }
    
    /**
     * Sets the user id.
     *
     * @param UserEntity $userId
     *
     * @return void
     */
    public function setUserId($userId)
    {
        if ($this->userId !== $userId) {
            $this->userId = isset($userId) ? $userId : '';
        }
    }
    
    /**
     * Returns the rating system.
     *
     * @return integer
     */
    public function getRatingSystem()
    {
        return $this->ratingSystem;
    }
    
    /**
     * Sets the rating system.
     *
     * @param integer $ratingSystem
     *
     * @return void
     */
    public function setRatingSystem($ratingSystem)
    {
        if (intval($this->ratingSystem) !== intval($ratingSystem)) {
            $this->ratingSystem = intval($ratingSystem);
        }
    }
    
    
    /**
     * Returns the rating system val.
     *
     * @return \Paustian\RatingsModule\Entity\RatingSystemEntity
     */
    public function getRatingSystemVal()
    {
        return $this->ratingSystemVal;
    }
    
    /**
     * Sets the rating system val.
     *
     * @param \Paustian\RatingsModule\Entity\RatingSystemEntity $ratingSystemVal
     *
     * @return void
     */
    public function setRatingSystemVal($ratingSystemVal = null)
    {
        $this->ratingSystemVal = $ratingSystemVal;
    }
    
    
    
    /**
     * Checks whether the userId field contains a valid user reference.
     * This method is used for validation.
     *
     * @Assert\IsTrue(message="This value must be a valid user id.")
     *
     * @return boolean True if data is valid else false
     */
    public function isUserIdUserValid()
    {
        return $this['userId'] instanceof UserEntity;
    }
    
    /**
     * Creates url arguments array for easy creation of display urls.
     *
     * @return array List of resulting arguments
     */
    public function createUrlArgs()
    {
        return [
            'id' => $this->getId()
        ];
    }
    
    /**
     * Returns the primary key.
     *
     * @return integer The identifier
     */
    public function getKey()
    {
        return $this->getId();
    }
    
    /**
     * Returns an array of all related objects that need to be persisted after clone.
     * 
     * @param array $objects Objects that are added to this array
     * 
     * @return array List of entity objects
     */
    public function getRelatedObjectsToPersist(&$objects = [])
    {
        return [];
    }
    
    /**
     * ToString interceptor implementation.
     * This method is useful for debugging purposes.
     *
     * @return string The output string for this entity
     */
    public function __toString()
    {
        return 'Rating ' . $this->getKey() . ': ' . $this->getModuleName();
    }
    
    /**
     * Clone interceptor implementation.
     * This method is for example called by the reuse functionality.
     * Performs a quite simple shallow copy.
     *
     * See also:
     * (1) http://docs.doctrine-project.org/en/latest/cookbook/implementing-wakeup-or-clone.html
     * (2) http://www.php.net/manual/en/language.oop5.cloning.php
     * (3) http://stackoverflow.com/questions/185934/how-do-i-create-a-copy-of-an-object-in-php
     */
    public function __clone()
    {
        // if the entity has no identity do nothing, do NOT throw an exception
        if (!$this->id) {
            return;
        }
    
        // otherwise proceed
    
        // unset identifier
        $this->setId(0);
    
        // reset workflow
        $this->setWorkflowState('initial');
    
        $this->setCreatedBy(null);
        $this->setCreatedDate(null);
        $this->setUpdatedBy(null);
        $this->setUpdatedDate(null);
    
    }
}