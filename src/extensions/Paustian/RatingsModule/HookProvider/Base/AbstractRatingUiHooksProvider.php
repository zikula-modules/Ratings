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

namespace Paustian\RatingsModule\HookProvider\Base;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Zikula\Bundle\HookBundle\Category\UiHooksCategory;
use Zikula\Bundle\HookBundle\Hook\DisplayHook;
use Zikula\Bundle\HookBundle\Hook\DisplayHookResponse;
use Zikula\Bundle\HookBundle\Hook\Hook;
use Zikula\Bundle\HookBundle\Hook\ProcessHook;
use Zikula\Bundle\HookBundle\Hook\ValidationHook;
use Zikula\Bundle\HookBundle\HookProviderInterface;
use Paustian\RatingsModule\Entity\Factory\EntityFactory;
use Paustian\RatingsModule\Helper\PermissionHelper;

/**
 * Base class for ui hooks provider.
 */
abstract class AbstractRatingUiHooksProvider implements HookProviderInterface
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var EntityFactory
     */
    protected $entityFactory;

    /**
     * @var Environment
     */
    protected $templating;

    /**
     * @var PermissionHelper
     */
    protected $permissionHelper;

    public function __construct(
        TranslatorInterface $translator,
        RequestStack $requestStack,
        EntityFactory $entityFactory,
        Environment $twig,
        PermissionHelper $permissionHelper
    ) {
        $this->translator = $translator;
        $this->requestStack = $requestStack;
        $this->entityFactory = $entityFactory;
        $this->templating = $twig;
        $this->permissionHelper = $permissionHelper;
    }

    public function getOwner(): string
    {
        return 'PaustianRatingsModule';
    }
    
    public function getCategory(): string
    {
        return UiHooksCategory::NAME;
    }
    
    public function getTitle(): string
    {
        return $this->translator->trans('Rating ui hooks provider', [], 'hooks');
    }
    
    public function getAreaName(): string
    {
        return 'provider.paustianratingsmodule.ui_hooks.ratings';
    }

    public function getProviderTypes(): array
    {
        return [
            UiHooksCategory::TYPE_DISPLAY_VIEW => 'view',
            UiHooksCategory::TYPE_FORM_EDIT => 'displayEdit',
            UiHooksCategory::TYPE_VALIDATE_EDIT => 'validateEdit',
            UiHooksCategory::TYPE_PROCESS_EDIT => 'processEdit',
            UiHooksCategory::TYPE_FORM_DELETE => 'displayDelete',
            UiHooksCategory::TYPE_VALIDATE_DELETE => 'validateDelete',
            UiHooksCategory::TYPE_PROCESS_DELETE => 'processDelete',
        ];
    }

    /**
     * Display hook for view/display templates.
     */
    public function view(DisplayHook $hook): void
    {
        $response = $this->renderDisplayHookResponse($hook, 'hookDisplayView');
        $hook->setResponse($response);
    }

    /**
     * Display hook for create/edit forms.
     */
    public function displayEdit(DisplayHook $hook): void
    {
        $response = $this->renderDisplayHookResponse($hook, 'hookDisplayEdit');
        $hook->setResponse($response);
    }

    /**
     * Validate input from an item to be edited.
     */
    public function validateEdit(ValidationHook $hook): bool
    {
        return true;
    }

    /**
     * Perform the final update actions for an edited item.
     */
    public function processEdit(ProcessHook $hook): void
    {
        $url = $hook->getUrl();
        if (null === $url || !is_object($url)) {
            return;
        }
        $url = $url->toArray();

        $entityManager = $this->entityFactory->getEntityManager();

        // update url information for assignments of updated data object
        $qb = $entityManager->createQueryBuilder();
        $qb->select('tbl')
           ->from($this->getHookAssignmentEntity(), 'tbl');
        $qb = $this->addContextFilters($qb, $hook);

        $query = $qb->getQuery();
        $assignments = $query->getResult();

        foreach ($assignments as $assignment) {
            $assignment->setSubscriberUrl($url);
        }

        $entityManager->flush();
    }

    /**
     * Display hook for delete forms.
     */
    public function displayDelete(DisplayHook $hook): void
    {
        $response = $this->renderDisplayHookResponse($hook, 'hookDisplayDelete');
        $hook->setResponse($response);
    }

    /**
     * Validate input from an item to be deleted.
     */
    public function validateDelete(ValidationHook $hook): bool
    {
        return true;
    }

    /**
     * Perform the final delete actions for a deleted item.
     */
    public function processDelete(ProcessHook $hook): void
    {
        // delete assignments of removed data object
        $qb = $this->entityFactory->getEntityManager()->createQueryBuilder();
        $qb->delete($this->getHookAssignmentEntity(), 'tbl');
        $qb = $this->addContextFilters($qb, $hook);

        $query = $qb->getQuery();
        $query->execute();
    }

    /**
     * Returns the entity for hook assignment data.
     */
    protected function getHookAssignmentEntity(): string
    {
        return 'Paustian\RatingsModule\Entity\HookAssignmentEntity';
    }

    /**
     * Adds common hook-based filters to a given query builder.
     */
    protected function addContextFilters(QueryBuilder $qb, Hook $hook): QueryBuilder
    {
        $qb->where('tbl.subscriberOwner = :moduleName')
           ->setParameter('moduleName', $hook->getCaller())
           ->andWhere('tbl.subscriberAreaId = :areaId')
           ->setParameter('areaId', $hook->getAreaId())
           ->andWhere('tbl.subscriberObjectId = :objectId')
           ->setParameter('objectId', $hook->getId())
           ->andWhere('tbl.assignedEntity = :objectType')
           ->setParameter('objectType', 'rating')
       ;

        return $qb;
    }

    /**
     * Returns a list of assigned entities for a given hook context.
     */
    protected function selectAssignedEntities(Hook $hook): array
    {
        list ($assignments, $assignedIds) = $this->selectAssignedIds($hook);
        if (!count($assignedIds)) {
            return [[], []];
        }

        $entities = $this->entityFactory->getRepository('rating')->selectByIdList($assignedIds);

        return [$assignments, $entities];
    }

    /**
     * Returns a list of assigned entity identifiers for a given hook context.
     */
    protected function selectAssignedIds(Hook $hook): array
    {
        $qb = $this->entityFactory->getEntityManager()->createQueryBuilder();
        $qb->select('tbl')
           ->from($this->getHookAssignmentEntity(), 'tbl');
        $qb = $this->addContextFilters($qb, $hook);
        $qb->add('orderBy', 'tbl.updatedDate DESC');

        $query = $qb->getQuery();
        $assignments = $query->getResult();

        $assignedIds = [];
        foreach ($assignments as $assignment) {
            $assignedIds[] = $assignment->getAssignedId();
        }

        return [$assignments, $assignedIds];
    }

    /**
     * Returns the response for a display hook of a given context.
     */
    protected function renderDisplayHookResponse(Hook $hook, string $context): DisplayHookResponse
    {
        list ($assignments, $assignedEntities) = $this->selectAssignedEntities($hook);
        $template = '@PaustianRatingsModule/Rating/includeDisplayItemListMany.html.twig';

        $templateParameters = [
            'items' => $assignedEntities,
            'context' => $context,
            'routeArea' => ''
        ];

        if ('hookDisplayView' === $context) {
            // add context information to template parameters in order to provide means
            // for adding new assignments and removing existing assignments
            $templateParameters['assignments'] = $assignments;
            $templateParameters['subscriberOwner'] = $hook->getCaller();
            $templateParameters['subscriberAreaId'] = $hook->getAreaId();
            $templateParameters['subscriberObjectId'] = $hook->getId();
            $url = method_exists($hook, 'getUrl') ? $hook->getUrl() : null;
            $templateParameters['subscriberUrl'] = (null !== $url && is_object($url)) ? $url->serialize() : serialize([]);
        }
        $templateParameters['permissionHelper'] = $this->permissionHelper;

        $output = $this->templating->render($template, $templateParameters);

        return new DisplayHookResponse($this->getAreaName(), $output);
    }
}
