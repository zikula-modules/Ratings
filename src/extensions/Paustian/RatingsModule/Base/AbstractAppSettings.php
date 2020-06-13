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
     * The number of divisions in the scale. For example there are five divisions in a 1 to 5 scale, four divisions in a four-star scale
     *
     * @Assert\Type(type="integer")
     * @Assert\NotBlank
     * @Assert\NotEqualTo(value=0)
     * @Assert\LessThan(value=100000000000)
     * @var int $ratingScale
     */
    protected $ratingScale = 5;
    
    /**
     * A font-awesome css string that is used to display ratings. Either this or iconUrls must be specified.
     *
     * @Assert\NotNull
     * @Assert\Length(min="0", max="255", allowEmptyString="true")
     * @var string $iconFa
     */
    protected $iconFa = 'fas fa-star';
    
    /**
     * A font-awesome css string that is used to display ratings. Either this or iconUrls must be specified.
     *
     * @Assert\NotNull
     * @Assert\Length(min="0", max="255", allowEmptyString="true")
     * @var string $halfIconFa
     */
    protected $halfIconFa = 'fas fa-star-half';
    
    /**
     * A font-awesome css string that is used to display ratings. Either this or iconUrls must be specified.
     *
     * @Assert\NotNull
     * @Assert\Length(min="0", max="255", allowEmptyString="true")
     * @var string $emptyIconFa
     */
    protected $emptyIconFa = 'fas fa-star-empty';
    
    /**
     * A url to a rating icon to be used for a rating. Either this or IconFas must be designated.
     *
     * @Assert\NotNull
     * @Assert\Length(min="0", max="255", allowEmptyString="true")
     * @var string $iconUrl
     */
    protected $iconUrl = '';
    
    /**
     * A url to a rating icon to be used for a rating. Either this or IconFas must be designated.
     *
     * @Assert\NotNull
     * @Assert\Length(min="0", max="255", allowEmptyString="true")
     * @var string $halfIconUrl
     */
    protected $halfIconUrl = '';
    
    /**
     * A url to a rating icon to be used for a rating. Either this or IconFas must be designated.
     *
     * @Assert\NotNull
     * @Assert\Length(min="0", max="255", allowEmptyString="true")
     * @var string $emptyIconUrl
     */
    protected $emptyIconUrl = '';
    
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
    
    public function getRatingScale(): int
    {
        return $this->ratingScale;
    }
    
    public function setRatingScale(int $ratingScale): void
    {
        if ((int)$this->ratingScale !== $ratingScale) {
            $this->ratingScale = $ratingScale;
        }
    }
    
    public function getIconFa(): string
    {
        return $this->iconFa;
    }
    
    public function setIconFa(string $iconFa): void
    {
        if ($this->iconFa !== $iconFa) {
            $this->iconFa = $iconFa ?? '';
        }
    }
    
    public function getHalfIconFa(): string
    {
        return $this->halfIconFa;
    }
    
    public function setHalfIconFa(string $halfIconFa): void
    {
        if ($this->halfIconFa !== $halfIconFa) {
            $this->halfIconFa = $halfIconFa ?? '';
        }
    }
    
    public function getEmptyIconFa(): string
    {
        return $this->emptyIconFa;
    }
    
    public function setEmptyIconFa(string $emptyIconFa): void
    {
        if ($this->emptyIconFa !== $emptyIconFa) {
            $this->emptyIconFa = $emptyIconFa ?? '';
        }
    }
    
    public function getIconUrl(): string
    {
        return $this->iconUrl;
    }
    
    public function setIconUrl(string $iconUrl): void
    {
        if ($this->iconUrl !== $iconUrl) {
            $this->iconUrl = $iconUrl ?? '';
        }
    }
    
    public function getHalfIconUrl(): string
    {
        return $this->halfIconUrl;
    }
    
    public function setHalfIconUrl(string $halfIconUrl): void
    {
        if ($this->halfIconUrl !== $halfIconUrl) {
            $this->halfIconUrl = $halfIconUrl ?? '';
        }
    }
    
    public function getEmptyIconUrl(): string
    {
        return $this->emptyIconUrl;
    }
    
    public function setEmptyIconUrl(string $emptyIconUrl): void
    {
        if ($this->emptyIconUrl !== $emptyIconUrl) {
            $this->emptyIconUrl = $emptyIconUrl ?? '';
        }
    }
    
    public function getRatingEntriesPerPage(): int
    {
        return $this->ratingEntriesPerPage;
    }
    
    public function setRatingEntriesPerPage(int $ratingEntriesPerPage): void
    {
        if ((int)$this->ratingEntriesPerPage !== $ratingEntriesPerPage) {
            $this->ratingEntriesPerPage = $ratingEntriesPerPage;
        }
    }
    
    public function getLinkOwnRatingsOnAccountPage(): bool
    {
        return $this->linkOwnRatingsOnAccountPage;
    }
    
    public function setLinkOwnRatingsOnAccountPage(bool $linkOwnRatingsOnAccountPage): void
    {
        if ((bool)$this->linkOwnRatingsOnAccountPage !== $linkOwnRatingsOnAccountPage) {
            $this->linkOwnRatingsOnAccountPage = $linkOwnRatingsOnAccountPage;
        }
    }
    
    public function getShowOnlyOwnEntries(): bool
    {
        return $this->showOnlyOwnEntries;
    }
    
    public function setShowOnlyOwnEntries(bool $showOnlyOwnEntries): void
    {
        if ((bool)$this->showOnlyOwnEntries !== $showOnlyOwnEntries) {
            $this->showOnlyOwnEntries = $showOnlyOwnEntries;
        }
    }
    
    public function getAllowModerationSpecificCreatorForRating(): bool
    {
        return $this->allowModerationSpecificCreatorForRating;
    }
    
    public function setAllowModerationSpecificCreatorForRating(bool $allowModerationSpecificCreatorForRating): void
    {
        if ((bool)$this->allowModerationSpecificCreatorForRating !== $allowModerationSpecificCreatorForRating) {
            $this->allowModerationSpecificCreatorForRating = $allowModerationSpecificCreatorForRating;
        }
    }
    
    public function getAllowModerationSpecificCreationDateForRating(): bool
    {
        return $this->allowModerationSpecificCreationDateForRating;
    }
    
    public function setAllowModerationSpecificCreationDateForRating(bool $allowModerationSpecificCreationDateForRating): void
    {
        if ((bool)$this->allowModerationSpecificCreationDateForRating !== $allowModerationSpecificCreationDateForRating) {
            $this->allowModerationSpecificCreationDateForRating = $allowModerationSpecificCreationDateForRating;
        }
    }
    
    /**
     * Loads module variables from the database.
     */
    protected function load(): void
    {
        $moduleVars = $this->variableApi->getAll('PaustianRatingsModule');
    
        if (isset($moduleVars['ratingScale'])) {
            $this->setRatingScale($moduleVars['ratingScale']);
        }
        if (isset($moduleVars['iconFa'])) {
            $this->setIconFa($moduleVars['iconFa']);
        }
        if (isset($moduleVars['halfIconFa'])) {
            $this->setHalfIconFa($moduleVars['halfIconFa']);
        }
        if (isset($moduleVars['emptyIconFa'])) {
            $this->setEmptyIconFa($moduleVars['emptyIconFa']);
        }
        if (isset($moduleVars['iconUrl'])) {
            $this->setIconUrl($moduleVars['iconUrl']);
        }
        if (isset($moduleVars['halfIconUrl'])) {
            $this->setHalfIconUrl($moduleVars['halfIconUrl']);
        }
        if (isset($moduleVars['emptyIconUrl'])) {
            $this->setEmptyIconUrl($moduleVars['emptyIconUrl']);
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
    public function save(): void
    {
        $this->variableApi->set('PaustianRatingsModule', 'ratingScale', $this->getRatingScale());
        $this->variableApi->set('PaustianRatingsModule', 'iconFa', $this->getIconFa());
        $this->variableApi->set('PaustianRatingsModule', 'halfIconFa', $this->getHalfIconFa());
        $this->variableApi->set('PaustianRatingsModule', 'emptyIconFa', $this->getEmptyIconFa());
        $this->variableApi->set('PaustianRatingsModule', 'iconUrl', $this->getIconUrl());
        $this->variableApi->set('PaustianRatingsModule', 'halfIconUrl', $this->getHalfIconUrl());
        $this->variableApi->set('PaustianRatingsModule', 'emptyIconUrl', $this->getEmptyIconUrl());
        $this->variableApi->set('PaustianRatingsModule', 'ratingEntriesPerPage', $this->getRatingEntriesPerPage());
        $this->variableApi->set('PaustianRatingsModule', 'linkOwnRatingsOnAccountPage', $this->getLinkOwnRatingsOnAccountPage());
        $this->variableApi->set('PaustianRatingsModule', 'showOnlyOwnEntries', $this->getShowOnlyOwnEntries());
        $this->variableApi->set('PaustianRatingsModule', 'allowModerationSpecificCreatorForRating', $this->getAllowModerationSpecificCreatorForRating());
        $this->variableApi->set('PaustianRatingsModule', 'allowModerationSpecificCreationDateForRating', $this->getAllowModerationSpecificCreationDateForRating());
    }
}
