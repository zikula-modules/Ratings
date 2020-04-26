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
     * @Assert\NotBlank
     * @Assert\NotEqualTo(value=0)
     * @Assert\LessThan(value=100000000000)
     * @var int $ratingSystemEntriesPerPage
     */
    protected $ratingSystemEntriesPerPage = 10;
    
    /**
     * Whether to add a link to rating systems of the current user on his account page
     *
     * @Assert\NotNull
     * @Assert\Type(type="bool")
     * @var bool $linkOwnRatingSystemsOnAccountPage
     */
    protected $linkOwnRatingSystemsOnAccountPage = true;
    
    /**
     * The amount of ratings shown per page
     *
     * @Assert\Type(type="integer")
     * @Assert\NotBlank
     * @Assert\NotEqualTo(value=0)
     * @Assert\LessThan(value=100000000000)
     * @var int $ratingEntriesPerPage
     */
    protected $ratingEntriesPerPage = 10;
    
    /**
     * Whether to add a link to ratings of the current user on his account page
     *
     * @Assert\NotNull
     * @Assert\Type(type="bool")
     * @var bool $linkOwnRatingsOnAccountPage
     */
    protected $linkOwnRatingsOnAccountPage = true;
    
    /**
     * Whether only own entries should be shown on view pages by default or not
     *
     * @Assert\NotNull
     * @Assert\Type(type="bool")
     * @var bool $showOnlyOwnEntries
     */
    protected $showOnlyOwnEntries = false;
    
    /**
     * Whether to allow moderators choosing a user which will be set as creator.
     *
     * @Assert\NotNull
     * @Assert\Type(type="bool")
     * @var bool $allowModerationSpecificCreatorForRatingSystem
     */
    protected $allowModerationSpecificCreatorForRatingSystem = false;
    
    /**
     * Whether to allow moderators choosing a custom creation date.
     *
     * @Assert\NotNull
     * @Assert\Type(type="bool")
     * @var bool $allowModerationSpecificCreationDateForRatingSystem
     */
    protected $allowModerationSpecificCreationDateForRatingSystem = false;
    
    /**
     * Whether to allow moderators choosing a user which will be set as creator.
     *
     * @Assert\NotNull
     * @Assert\Type(type="bool")
     * @var bool $allowModerationSpecificCreatorForRating
     */
    protected $allowModerationSpecificCreatorForRating = false;
    
    /**
     * Whether to allow moderators choosing a custom creation date.
     *
     * @Assert\NotNull
     * @Assert\Type(type="bool")
     * @var bool $allowModerationSpecificCreationDateForRating
     */
    protected $allowModerationSpecificCreationDateForRating = false;
    
    
    public function __construct(
        VariableApiInterface $variableApi
    ) {
        $this->variableApi = $variableApi;
    
        $this->load();
    }
    
    /**
     * Returns the rating system entries per page.
     *
     * @return int
     */
    public function getRatingSystemEntriesPerPage()
    {
        return $this->ratingSystemEntriesPerPage;
    }
    
    /**
     * Sets the rating system entries per page.
     *
     * @param int $ratingSystemEntriesPerPage
     *
     * @return void
     */
    public function setRatingSystemEntriesPerPage($ratingSystemEntriesPerPage)
    {
        if ((int)$this->ratingSystemEntriesPerPage !== (int)$ratingSystemEntriesPerPage) {
            $this->ratingSystemEntriesPerPage = (int)$ratingSystemEntriesPerPage;
        }
    }
    
    /**
     * Returns the link own rating systems on account page.
     *
     * @return bool
     */
    public function getLinkOwnRatingSystemsOnAccountPage()
    {
        return $this->linkOwnRatingSystemsOnAccountPage;
    }
    
    /**
     * Sets the link own rating systems on account page.
     *
     * @param bool $linkOwnRatingSystemsOnAccountPage
     *
     * @return void
     */
    public function setLinkOwnRatingSystemsOnAccountPage($linkOwnRatingSystemsOnAccountPage)
    {
        if ((bool)$this->linkOwnRatingSystemsOnAccountPage !== (bool)$linkOwnRatingSystemsOnAccountPage) {
            $this->linkOwnRatingSystemsOnAccountPage = (bool)$linkOwnRatingSystemsOnAccountPage;
        }
    }
    
    /**
     * Returns the rating entries per page.
     *
     * @return int
     */
    public function getRatingEntriesPerPage()
    {
        return $this->ratingEntriesPerPage;
    }
    
    /**
     * Sets the rating entries per page.
     *
     * @param int $ratingEntriesPerPage
     *
     * @return void
     */
    public function setRatingEntriesPerPage($ratingEntriesPerPage)
    {
        if ((int)$this->ratingEntriesPerPage !== (int)$ratingEntriesPerPage) {
            $this->ratingEntriesPerPage = (int)$ratingEntriesPerPage;
        }
    }
    
    /**
     * Returns the link own ratings on account page.
     *
     * @return bool
     */
    public function getLinkOwnRatingsOnAccountPage()
    {
        return $this->linkOwnRatingsOnAccountPage;
    }
    
    /**
     * Sets the link own ratings on account page.
     *
     * @param bool $linkOwnRatingsOnAccountPage
     *
     * @return void
     */
    public function setLinkOwnRatingsOnAccountPage($linkOwnRatingsOnAccountPage)
    {
        if ((bool)$this->linkOwnRatingsOnAccountPage !== (bool)$linkOwnRatingsOnAccountPage) {
            $this->linkOwnRatingsOnAccountPage = (bool)$linkOwnRatingsOnAccountPage;
        }
    }
    
    /**
     * Returns the show only own entries.
     *
     * @return bool
     */
    public function getShowOnlyOwnEntries()
    {
        return $this->showOnlyOwnEntries;
    }
    
    /**
     * Sets the show only own entries.
     *
     * @param bool $showOnlyOwnEntries
     *
     * @return void
     */
    public function setShowOnlyOwnEntries($showOnlyOwnEntries)
    {
        if ((bool)$this->showOnlyOwnEntries !== (bool)$showOnlyOwnEntries) {
            $this->showOnlyOwnEntries = (bool)$showOnlyOwnEntries;
        }
    }
    
    /**
     * Returns the allow moderation specific creator for rating system.
     *
     * @return bool
     */
    public function getAllowModerationSpecificCreatorForRatingSystem()
    {
        return $this->allowModerationSpecificCreatorForRatingSystem;
    }
    
    /**
     * Sets the allow moderation specific creator for rating system.
     *
     * @param bool $allowModerationSpecificCreatorForRatingSystem
     *
     * @return void
     */
    public function setAllowModerationSpecificCreatorForRatingSystem($allowModerationSpecificCreatorForRatingSystem)
    {
        if ((bool)$this->allowModerationSpecificCreatorForRatingSystem !== (bool)$allowModerationSpecificCreatorForRatingSystem) {
            $this->allowModerationSpecificCreatorForRatingSystem = (bool)$allowModerationSpecificCreatorForRatingSystem;
        }
    }
    
    /**
     * Returns the allow moderation specific creation date for rating system.
     *
     * @return bool
     */
    public function getAllowModerationSpecificCreationDateForRatingSystem()
    {
        return $this->allowModerationSpecificCreationDateForRatingSystem;
    }
    
    /**
     * Sets the allow moderation specific creation date for rating system.
     *
     * @param bool $allowModerationSpecificCreationDateForRatingSystem
     *
     * @return void
     */
    public function setAllowModerationSpecificCreationDateForRatingSystem($allowModerationSpecificCreationDateForRatingSystem)
    {
        if ((bool)$this->allowModerationSpecificCreationDateForRatingSystem !== (bool)$allowModerationSpecificCreationDateForRatingSystem) {
            $this->allowModerationSpecificCreationDateForRatingSystem = (bool)$allowModerationSpecificCreationDateForRatingSystem;
        }
    }
    
    /**
     * Returns the allow moderation specific creator for rating.
     *
     * @return bool
     */
    public function getAllowModerationSpecificCreatorForRating()
    {
        return $this->allowModerationSpecificCreatorForRating;
    }
    
    /**
     * Sets the allow moderation specific creator for rating.
     *
     * @param bool $allowModerationSpecificCreatorForRating
     *
     * @return void
     */
    public function setAllowModerationSpecificCreatorForRating($allowModerationSpecificCreatorForRating)
    {
        if ((bool)$this->allowModerationSpecificCreatorForRating !== (bool)$allowModerationSpecificCreatorForRating) {
            $this->allowModerationSpecificCreatorForRating = (bool)$allowModerationSpecificCreatorForRating;
        }
    }
    
    /**
     * Returns the allow moderation specific creation date for rating.
     *
     * @return bool
     */
    public function getAllowModerationSpecificCreationDateForRating()
    {
        return $this->allowModerationSpecificCreationDateForRating;
    }
    
    /**
     * Sets the allow moderation specific creation date for rating.
     *
     * @param bool $allowModerationSpecificCreationDateForRating
     *
     * @return void
     */
    public function setAllowModerationSpecificCreationDateForRating($allowModerationSpecificCreationDateForRating)
    {
        if ((bool)$this->allowModerationSpecificCreationDateForRating !== (bool)$allowModerationSpecificCreationDateForRating) {
            $this->allowModerationSpecificCreationDateForRating = (bool)$allowModerationSpecificCreationDateForRating;
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
