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

namespace Paustian\RatingsModule\Base;

use Symfony\Component\Validator\Constraints as Assert;
use Zikula\ExtensionsModule\Api\ApiInterface\VariableApiInterface;

/**
 * Application settings class for handling module variables.
 */
abstract class AbstractAppSettings
{
    /**
     * @var VariableApiInterface
     */
    protected $variableApi;
    
    /**
     * The amount of rating systems shown per page
     *
     * @Assert\Type(type="integer")
     * @Assert\NotBlank()
     * @Assert\NotEqualTo(value=0)
     * @Assert\LessThan(value=100000000000)
     * @var integer $ratingSystemEntriesPerPage
     */
    protected $ratingSystemEntriesPerPage = 10;
    
    /**
     * Whether to add a link to rating systems of the current user on his account page
     *
     * @Assert\NotNull()
     * @Assert\Type(type="bool")
     * @var boolean $linkOwnRatingSystemsOnAccountPage
     */
    protected $linkOwnRatingSystemsOnAccountPage = true;
    
    /**
     * The amount of ratings shown per page
     *
     * @Assert\Type(type="integer")
     * @Assert\NotBlank()
     * @Assert\NotEqualTo(value=0)
     * @Assert\LessThan(value=100000000000)
     * @var integer $ratingEntriesPerPage
     */
    protected $ratingEntriesPerPage = 10;
    
    /**
     * Whether to add a link to ratings of the current user on his account page
     *
     * @Assert\NotNull()
     * @Assert\Type(type="bool")
     * @var boolean $linkOwnRatingsOnAccountPage
     */
    protected $linkOwnRatingsOnAccountPage = true;
    
    /**
     * Whether only own entries should be shown on view pages by default or not
     *
     * @Assert\NotNull()
     * @Assert\Type(type="bool")
     * @var boolean $showOnlyOwnEntries
     */
    protected $showOnlyOwnEntries = false;
    
    /**
     * Whether to allow moderators choosing a user which will be set as creator.
     *
     * @Assert\NotNull()
     * @Assert\Type(type="bool")
     * @var boolean $allowModerationSpecificCreatorForRatingSystem
     */
    protected $allowModerationSpecificCreatorForRatingSystem = false;
    
    /**
     * Whether to allow moderators choosing a custom creation date.
     *
     * @Assert\NotNull()
     * @Assert\Type(type="bool")
     * @var boolean $allowModerationSpecificCreationDateForRatingSystem
     */
    protected $allowModerationSpecificCreationDateForRatingSystem = false;
    
    /**
     * Whether to allow moderators choosing a user which will be set as creator.
     *
     * @Assert\NotNull()
     * @Assert\Type(type="bool")
     * @var boolean $allowModerationSpecificCreatorForRating
     */
    protected $allowModerationSpecificCreatorForRating = false;
    
    /**
     * Whether to allow moderators choosing a custom creation date.
     *
     * @Assert\NotNull()
     * @Assert\Type(type="bool")
     * @var boolean $allowModerationSpecificCreationDateForRating
     */
    protected $allowModerationSpecificCreationDateForRating = false;
    
    
    /**
     * AppSettings constructor.
     *
     * @param VariableApiInterface $variableApi VariableApi service instance
     */
    public function __construct(
        VariableApiInterface $variableApi
    ) {
        $this->variableApi = $variableApi;
    
        $this->load();
    }
    
    /**
     * Returns the rating system entries per page.
     *
     * @return integer
     */
    public function getRatingSystemEntriesPerPage()
    {
        return $this->ratingSystemEntriesPerPage;
    }
    
    /**
     * Sets the rating system entries per page.
     *
     * @param integer $ratingSystemEntriesPerPage
     *
     * @return void
     */
    public function setRatingSystemEntriesPerPage($ratingSystemEntriesPerPage)
    {
        if (intval($this->ratingSystemEntriesPerPage) !== intval($ratingSystemEntriesPerPage)) {
            $this->ratingSystemEntriesPerPage = intval($ratingSystemEntriesPerPage);
        }
    }
    
    /**
     * Returns the link own rating systems on account page.
     *
     * @return boolean
     */
    public function getLinkOwnRatingSystemsOnAccountPage()
    {
        return $this->linkOwnRatingSystemsOnAccountPage;
    }
    
    /**
     * Sets the link own rating systems on account page.
     *
     * @param boolean $linkOwnRatingSystemsOnAccountPage
     *
     * @return void
     */
    public function setLinkOwnRatingSystemsOnAccountPage($linkOwnRatingSystemsOnAccountPage)
    {
        if (boolval($this->linkOwnRatingSystemsOnAccountPage) !== boolval($linkOwnRatingSystemsOnAccountPage)) {
            $this->linkOwnRatingSystemsOnAccountPage = boolval($linkOwnRatingSystemsOnAccountPage);
        }
    }
    
    /**
     * Returns the rating entries per page.
     *
     * @return integer
     */
    public function getRatingEntriesPerPage()
    {
        return $this->ratingEntriesPerPage;
    }
    
    /**
     * Sets the rating entries per page.
     *
     * @param integer $ratingEntriesPerPage
     *
     * @return void
     */
    public function setRatingEntriesPerPage($ratingEntriesPerPage)
    {
        if (intval($this->ratingEntriesPerPage) !== intval($ratingEntriesPerPage)) {
            $this->ratingEntriesPerPage = intval($ratingEntriesPerPage);
        }
    }
    
    /**
     * Returns the link own ratings on account page.
     *
     * @return boolean
     */
    public function getLinkOwnRatingsOnAccountPage()
    {
        return $this->linkOwnRatingsOnAccountPage;
    }
    
    /**
     * Sets the link own ratings on account page.
     *
     * @param boolean $linkOwnRatingsOnAccountPage
     *
     * @return void
     */
    public function setLinkOwnRatingsOnAccountPage($linkOwnRatingsOnAccountPage)
    {
        if (boolval($this->linkOwnRatingsOnAccountPage) !== boolval($linkOwnRatingsOnAccountPage)) {
            $this->linkOwnRatingsOnAccountPage = boolval($linkOwnRatingsOnAccountPage);
        }
    }
    
    /**
     * Returns the show only own entries.
     *
     * @return boolean
     */
    public function getShowOnlyOwnEntries()
    {
        return $this->showOnlyOwnEntries;
    }
    
    /**
     * Sets the show only own entries.
     *
     * @param boolean $showOnlyOwnEntries
     *
     * @return void
     */
    public function setShowOnlyOwnEntries($showOnlyOwnEntries)
    {
        if (boolval($this->showOnlyOwnEntries) !== boolval($showOnlyOwnEntries)) {
            $this->showOnlyOwnEntries = boolval($showOnlyOwnEntries);
        }
    }
    
    /**
     * Returns the allow moderation specific creator for rating system.
     *
     * @return boolean
     */
    public function getAllowModerationSpecificCreatorForRatingSystem()
    {
        return $this->allowModerationSpecificCreatorForRatingSystem;
    }
    
    /**
     * Sets the allow moderation specific creator for rating system.
     *
     * @param boolean $allowModerationSpecificCreatorForRatingSystem
     *
     * @return void
     */
    public function setAllowModerationSpecificCreatorForRatingSystem($allowModerationSpecificCreatorForRatingSystem)
    {
        if (boolval($this->allowModerationSpecificCreatorForRatingSystem) !== boolval($allowModerationSpecificCreatorForRatingSystem)) {
            $this->allowModerationSpecificCreatorForRatingSystem = boolval($allowModerationSpecificCreatorForRatingSystem);
        }
    }
    
    /**
     * Returns the allow moderation specific creation date for rating system.
     *
     * @return boolean
     */
    public function getAllowModerationSpecificCreationDateForRatingSystem()
    {
        return $this->allowModerationSpecificCreationDateForRatingSystem;
    }
    
    /**
     * Sets the allow moderation specific creation date for rating system.
     *
     * @param boolean $allowModerationSpecificCreationDateForRatingSystem
     *
     * @return void
     */
    public function setAllowModerationSpecificCreationDateForRatingSystem($allowModerationSpecificCreationDateForRatingSystem)
    {
        if (boolval($this->allowModerationSpecificCreationDateForRatingSystem) !== boolval($allowModerationSpecificCreationDateForRatingSystem)) {
            $this->allowModerationSpecificCreationDateForRatingSystem = boolval($allowModerationSpecificCreationDateForRatingSystem);
        }
    }
    
    /**
     * Returns the allow moderation specific creator for rating.
     *
     * @return boolean
     */
    public function getAllowModerationSpecificCreatorForRating()
    {
        return $this->allowModerationSpecificCreatorForRating;
    }
    
    /**
     * Sets the allow moderation specific creator for rating.
     *
     * @param boolean $allowModerationSpecificCreatorForRating
     *
     * @return void
     */
    public function setAllowModerationSpecificCreatorForRating($allowModerationSpecificCreatorForRating)
    {
        if (boolval($this->allowModerationSpecificCreatorForRating) !== boolval($allowModerationSpecificCreatorForRating)) {
            $this->allowModerationSpecificCreatorForRating = boolval($allowModerationSpecificCreatorForRating);
        }
    }
    
    /**
     * Returns the allow moderation specific creation date for rating.
     *
     * @return boolean
     */
    public function getAllowModerationSpecificCreationDateForRating()
    {
        return $this->allowModerationSpecificCreationDateForRating;
    }
    
    /**
     * Sets the allow moderation specific creation date for rating.
     *
     * @param boolean $allowModerationSpecificCreationDateForRating
     *
     * @return void
     */
    public function setAllowModerationSpecificCreationDateForRating($allowModerationSpecificCreationDateForRating)
    {
        if (boolval($this->allowModerationSpecificCreationDateForRating) !== boolval($allowModerationSpecificCreationDateForRating)) {
            $this->allowModerationSpecificCreationDateForRating = boolval($allowModerationSpecificCreationDateForRating);
        }
    }
    
    
    /**
     * Loads module variables from the database.
     */
    protected function load()
    {
        $moduleVars = $this->variableApi->getAll('PaustianRatingsModule');
    
        if (isset($moduleVars['ratingSystemEntriesPerPage'])) {
            $this->setRatingSystemEntriesPerPage($moduleVars['ratingSystemEntriesPerPage']);
        }
        if (isset($moduleVars['linkOwnRatingSystemsOnAccountPage'])) {
            $this->setLinkOwnRatingSystemsOnAccountPage($moduleVars['linkOwnRatingSystemsOnAccountPage']);
        }
        if (isset($moduleVars['ratingEntriesPerPage'])) {
            $this->setRatingEntriesPerPage($moduleVars['ratingEntriesPerPage']);
        }
        if (isset($moduleVars['linkOwnRatingsOnAccountPage'])) {
            $this->setLinkOwnRatingsOnAccountPage($moduleVars['linkOwnRatingsOnAccountPage']);
        }
        if (isset($moduleVars['showOnlyOwnEntries'])) {
            $this->setShowOnlyOwnEntries($moduleVars['showOnlyOwnEntries']);
        }
        if (isset($moduleVars['allowModerationSpecificCreatorForRatingSystem'])) {
            $this->setAllowModerationSpecificCreatorForRatingSystem($moduleVars['allowModerationSpecificCreatorForRatingSystem']);
        }
        if (isset($moduleVars['allowModerationSpecificCreationDateForRatingSystem'])) {
            $this->setAllowModerationSpecificCreationDateForRatingSystem($moduleVars['allowModerationSpecificCreationDateForRatingSystem']);
        }
        if (isset($moduleVars['allowModerationSpecificCreatorForRating'])) {
            $this->setAllowModerationSpecificCreatorForRating($moduleVars['allowModerationSpecificCreatorForRating']);
        }
        if (isset($moduleVars['allowModerationSpecificCreationDateForRating'])) {
            $this->setAllowModerationSpecificCreationDateForRating($moduleVars['allowModerationSpecificCreationDateForRating']);
        }
    }
    
    /**
     * Saves module variables into the database.
     */
    public function save()
    {
        $this->variableApi->set('PaustianRatingsModule', 'ratingSystemEntriesPerPage', $this->getRatingSystemEntriesPerPage());
        $this->variableApi->set('PaustianRatingsModule', 'linkOwnRatingSystemsOnAccountPage', $this->getLinkOwnRatingSystemsOnAccountPage());
        $this->variableApi->set('PaustianRatingsModule', 'ratingEntriesPerPage', $this->getRatingEntriesPerPage());
        $this->variableApi->set('PaustianRatingsModule', 'linkOwnRatingsOnAccountPage', $this->getLinkOwnRatingsOnAccountPage());
        $this->variableApi->set('PaustianRatingsModule', 'showOnlyOwnEntries', $this->getShowOnlyOwnEntries());
        $this->variableApi->set('PaustianRatingsModule', 'allowModerationSpecificCreatorForRatingSystem', $this->getAllowModerationSpecificCreatorForRatingSystem());
        $this->variableApi->set('PaustianRatingsModule', 'allowModerationSpecificCreationDateForRatingSystem', $this->getAllowModerationSpecificCreationDateForRatingSystem());
        $this->variableApi->set('PaustianRatingsModule', 'allowModerationSpecificCreatorForRating', $this->getAllowModerationSpecificCreatorForRating());
        $this->variableApi->set('PaustianRatingsModule', 'allowModerationSpecificCreationDateForRating', $this->getAllowModerationSpecificCreationDateForRating());
    }
}