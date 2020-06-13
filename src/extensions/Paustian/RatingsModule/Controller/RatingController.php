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

namespace Paustian\RatingsModule\Controller;

use Paustian\RatingsModule\Controller\Base\AbstractRatingController;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Zikula\ThemeModule\Engine\Annotation\Theme;
use Zikula\UsersModule\Api\ApiInterface\CurrentUserApiInterface;
use Paustian\RatingsModule\Entity\RatingEntity;
use Paustian\RatingsModule\Entity\Factory\EntityFactory;
use Paustian\RatingsModule\Form\Handler\Rating\EditHandler;
use Paustian\RatingsModule\Helper\ControllerHelper;
use Paustian\RatingsModule\Helper\EntityDisplayHelper;
use Paustian\RatingsModule\Helper\PermissionHelper;
use Paustian\RatingsModule\Helper\ViewHelper;
use Paustian\RatingsModule\Helper\WorkflowHelper;

/**
 * Rating controller class providing navigation and interaction functionality.
 */
class RatingController extends AbstractRatingController
{
    /**
     *
     * @Route("/admin/ratings",
     *        methods = {"GET"}
     * )
     * @Theme("admin")
     */
    public function adminIndexAction(
        Request $request,
        PermissionHelper $permissionHelper
    ): Response {
        return $this->indexInternal(
            $request,
            $permissionHelper,
            true
        );
    }
    
    /**
     *
     * @Route("/ratings",
     *        methods = {"GET"}
     * )
     */
    public function indexAction(
        Request $request,
        PermissionHelper $permissionHelper
    ): Response {
        return $this->indexInternal(
            $request,
            $permissionHelper,
            false
        );
    }
    
    /**
     *
     * @Route("/admin/ratings/view/{sort}/{sortdir}/{page}/{num}.{_format}",
     *        requirements = {"sortdir" = "asc|desc|ASC|DESC", "page" = "\d+", "num" = "\d+", "_format" = "html"},
     *        defaults = {"sort" = "", "sortdir" = "asc", "page" = 1, "num" = 10, "_format" = "html"},
     *        methods = {"GET"}
     * )
     * @Theme("admin")
     */
    public function adminViewAction(
        Request $request,
        RouterInterface $router,
        PermissionHelper $permissionHelper,
        ControllerHelper $controllerHelper,
        ViewHelper $viewHelper,
        string $sort,
        string $sortdir,
        int $page,
        int $num
    ): Response {
        return $this->viewInternal(
            $request,
            $router,
            $permissionHelper,
            $controllerHelper,
            $viewHelper,
            $sort,
            $sortdir,
            $page,
            $num,
            true
        );
    }
    
    /**
     *
     * @Route("/ratings/view/{sort}/{sortdir}/{page}/{num}.{_format}",
     *        requirements = {"sortdir" = "asc|desc|ASC|DESC", "page" = "\d+", "num" = "\d+", "_format" = "html"},
     *        defaults = {"sort" = "", "sortdir" = "asc", "page" = 1, "num" = 10, "_format" = "html"},
     *        methods = {"GET"}
     * )
     */
    public function viewAction(
        Request $request,
        RouterInterface $router,
        PermissionHelper $permissionHelper,
        ControllerHelper $controllerHelper,
        ViewHelper $viewHelper,
        string $sort,
        string $sortdir,
        int $page,
        int $num
    ): Response {
        return $this->viewInternal(
            $request,
            $router,
            $permissionHelper,
            $controllerHelper,
            $viewHelper,
            $sort,
            $sortdir,
            $page,
            $num,
            false
        );
    }
    
    /**
     *
     * @Route("/admin/rating/{id}.{_format}",
     *        requirements = {"id" = "\d+", "_format" = "html"},
     *        defaults = {"_format" = "html"},
     *        methods = {"GET"}
     * )
     * @Theme("admin")
     */
    public function adminDisplayAction(
        Request $request,
        PermissionHelper $permissionHelper,
        ControllerHelper $controllerHelper,
        ViewHelper $viewHelper,
        EntityFactory $entityFactory,
        RatingEntity $rating = null,
        int $id = 0
    ): Response {
        return $this->displayInternal(
            $request,
            $permissionHelper,
            $controllerHelper,
            $viewHelper,
            $entityFactory,
            $rating,
            $id,
            true
        );
    }
    
    /**
     *
     * @Route("/rating/{id}.{_format}",
     *        requirements = {"id" = "\d+", "_format" = "html"},
     *        defaults = {"_format" = "html"},
     *        methods = {"GET"}
     * )
     */
    public function displayAction(
        Request $request,
        PermissionHelper $permissionHelper,
        ControllerHelper $controllerHelper,
        ViewHelper $viewHelper,
        EntityFactory $entityFactory,
        RatingEntity $rating = null,
        int $id = 0
    ): Response {
        return $this->displayInternal(
            $request,
            $permissionHelper,
            $controllerHelper,
            $viewHelper,
            $entityFactory,
            $rating,
            $id,
            false
        );
    }
    
    /**
     *
     * @Route("/admin/rating/edit/{id}.{_format}",
     *        requirements = {"id" = "\d+", "_format" = "html"},
     *        defaults = {"id" = "0", "_format" = "html"},
     *        methods = {"GET", "POST"}
     * )
     * @Theme("admin")
     */
    public function adminEditAction(
        Request $request,
        PermissionHelper $permissionHelper,
        ControllerHelper $controllerHelper,
        ViewHelper $viewHelper,
        EditHandler $formHandler
    ): Response {
        return $this->editInternal(
            $request,
            $permissionHelper,
            $controllerHelper,
            $viewHelper,
            $formHandler,
            true
        );
    }
    
    /**
     *
     * @Route("/rating/edit/{id}.{_format}",
     *        requirements = {"id" = "\d+", "_format" = "html"},
     *        defaults = {"id" = "0", "_format" = "html"},
     *        methods = {"GET", "POST"}
     * )
     */
    public function editAction(
        Request $request,
        PermissionHelper $permissionHelper,
        ControllerHelper $controllerHelper,
        ViewHelper $viewHelper,
        EditHandler $formHandler
    ): Response {
        return $this->editInternal(
            $request,
            $permissionHelper,
            $controllerHelper,
            $viewHelper,
            $formHandler,
            false
        );
    }
    
    /**
     * Process status changes for multiple items.
     *
     * @Route("/admin/ratings/handleSelectedEntries",
     *        methods = {"POST"}
     * )
     * @Theme("admin")
     */
    public function adminHandleSelectedEntriesAction(
        Request $request,
        LoggerInterface $logger,
        EntityFactory $entityFactory,
        WorkflowHelper $workflowHelper,
        CurrentUserApiInterface $currentUserApi
    ): RedirectResponse {
        return $this->handleSelectedEntriesActionInternal(
            $request,
            $logger,
            $entityFactory,
            $workflowHelper,
            $currentUserApi,
            true
        );
    }
    
    /**
     * Process status changes for multiple items.
     *
     * @Route("/ratings/handleSelectedEntries",
     *        methods = {"POST"}
     * )
     */
    public function handleSelectedEntriesAction(
        Request $request,
        LoggerInterface $logger,
        EntityFactory $entityFactory,
        WorkflowHelper $workflowHelper,
        CurrentUserApiInterface $currentUserApi
    ): RedirectResponse {
        return $this->handleSelectedEntriesActionInternal(
            $request,
            $logger,
            $entityFactory,
            $workflowHelper,
            $currentUserApi,
            false
        );
    }
    
    /**
     *
     * @Route("/rating/handleInlineRedirect/{idPrefix}/{commandName}/{id}",
     *        requirements = {"id" = "\d+"},
     *        defaults = {"commandName" = "", "id" = 0},
     *        methods = {"GET"}
     * )
     */
    public function handleInlineRedirectAction(
        EntityFactory $entityFactory,
        EntityDisplayHelper $entityDisplayHelper,
        string $idPrefix,
        string $commandName,
        int $id = 0
    ): Response
     {
        return parent::handleInlineRedirectAction(
            $entityFactory,
            $entityDisplayHelper,
            $idPrefix,
            $commandName,
            $id
        );
    }
    
    // feel free to add your own controller methods here
}