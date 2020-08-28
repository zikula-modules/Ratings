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

namespace Paustian\RatingsModule\Twig\Base;

use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Zikula\Bundle\CoreBundle\Doctrine\EntityAccess;
use Zikula\Bundle\CoreBundle\Translation\TranslatorTrait;
use Zikula\ExtensionsModule\Api\ApiInterface\VariableApiInterface;
use Paustian\RatingsModule\Helper\EntityDisplayHelper;
use Paustian\RatingsModule\Helper\ListEntriesHelper;
use Paustian\RatingsModule\Helper\WorkflowHelper;

/**
 * Twig extension base class.
 */
abstract class AbstractTwigExtension extends AbstractExtension
{
    use TranslatorTrait;
    
    /**
     * @var VariableApiInterface
     */
    protected $variableApi;
    
    /**
     * @var EntityDisplayHelper
     */
    protected $entityDisplayHelper;
    
    /**
     * @var WorkflowHelper
     */
    protected $workflowHelper;
    
    /**
     * @var ListEntriesHelper
     */
    protected $listHelper;
    
    public function __construct(
        TranslatorInterface $translator,
        VariableApiInterface $variableApi,
        EntityDisplayHelper $entityDisplayHelper,
        WorkflowHelper $workflowHelper,
        ListEntriesHelper $listHelper
    ) {
        $this->setTranslator($translator);
        $this->variableApi = $variableApi;
        $this->entityDisplayHelper = $entityDisplayHelper;
        $this->workflowHelper = $workflowHelper;
        $this->listHelper = $listHelper;
    }
    
    public function getFunctions()
    {
        return [
            new TwigFunction('paustianratingsmodule_objectTypeSelector', [$this, 'getObjectTypeSelector']),
            new TwigFunction('paustianratingsmodule_templateSelector', [$this, 'getTemplateSelector']),
        ];
    }
    
    public function getFilters()
    {
        return [
            new TwigFilter('paustianratingsmodule_listEntry', [$this, 'getListEntry']),
            new TwigFilter('paustianratingsmodule_formattedTitle', [$this, 'getFormattedEntityTitle']),
            new TwigFilter('paustianratingsmodule_objectState', [$this, 'getObjectState'], ['is_safe' => ['html']]),
        ];
    }
    
    /**
     * The paustianratingsmodule_objectState filter displays the name of a given object's workflow state.
     * Examples:
     *    {{ item.workflowState|paustianratingsmodule_objectState }}        {# with visual feedback #}
     *    {{ item.workflowState|paustianratingsmodule_objectState(false) }} {# no ui feedback #}.
     */
    public function getObjectState(string $state = 'initial', bool $uiFeedback = true): string
    {
        $stateInfo = $this->workflowHelper->getStateInfo($state);
    
        $result = $stateInfo['text'];
        if (true === $uiFeedback) {
            $result = '<span class="badge badge-' . $stateInfo['ui'] . '">' . $result . '</span>';
        }
    
        return $result;
    }
    
    
    /**
     * The paustianratingsmodule_listEntry filter displays the name
     * or names for a given list item.
     * Example:
     *     {{ entity.listField|paustianratingsmodule_listEntry('entityName', 'fieldName') }}.
     */
    public function getListEntry(
        string $value,
        string $objectType = '',
        string $fieldName = '',
        string $delimiter = ', '
    ): string {
        if ((empty($value) && '0' !== $value) || empty($objectType) || empty($fieldName)) {
            return $value;
        }
    
        return $this->listHelper->resolve($value, $objectType, $fieldName, $delimiter);
    }
    
    
    
    
    
    /**
     * The paustianratingsmodule_formattedTitle filter outputs a formatted title for a given entity.
     * Example:
     *     {{ myPost|paustianratingsmodule_formattedTitle }}.
     */
    public function getFormattedEntityTitle(EntityAccess $entity): string
    {
        return $this->entityDisplayHelper->getFormattedTitle($entity);
    }
}
