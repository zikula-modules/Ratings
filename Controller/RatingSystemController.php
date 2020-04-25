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

namespace Paustian\RatingsModule\Controller;

use Paustian\RatingsModule\Controller\Base\AbstractRatingSystemController;

use RuntimeException;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zikula\ThemeModule\Engine\Annotation\Theme;
use Zikula\Component\SortableColumns\Column;
use Zikula\Component\SortableColumns\SortableColumns;

/**
 * Rating system controller class providing navigation and interaction functionality.
 */
class RatingSystemController extends AbstractRatingSystemController
{
    /**
     * @inheritDoc
     *
     * @Route("/admin/ratingSystems",
     *        methods = {"GET"}
     * )
     * @Theme("admin")
     */
    public function adminIndexAction(Request $request)
    {
        return parent::adminIndexAction($request);
    }
    
    /**
     * @inheritDoc
     *
     * @Route("/ratingSystems",
     *        methods = {"GET"}
     * )
     */
    public function indexAction(Request $request)
    {
        return parent::indexAction($request);
    }
    
    /**
     * @inheritDoc
     *
     * @Route("/admin/ratingSystem/edit/{id}.{_format}",
     *        requirements = {"id" = "\d+", "_format" = "html"},
     *        defaults = {"id" = "0", "_format" = "html"},
     *        methods = {"GET", "POST"}
     * )
     * @Theme("admin")
     */
    public function adminEditAction(Request $request)
    {
        return parent::adminEditAction($request);
    }
    
    /**
     * @inheritDoc
     *
     * @Route("/ratingSystem/edit/{id}.{_format}",
     *        requirements = {"id" = "\d+", "_format" = "html"},
     *        defaults = {"id" = "0", "_format" = "html"},
     *        methods = {"GET", "POST"}
     * )
     */
    public function editAction(Request $request)
    {
        return parent::editAction($request);
    }
    
    /**
     * @inheritDoc
     *
     * @Route("/admin/ratingSystems/view/{sort}/{sortdir}/{pos}/{num}.{_format}",
     *        requirements = {"sortdir" = "asc|desc|ASC|DESC", "pos" = "\d+", "num" = "\d+", "_format" = "html|csv|rss|atom|xml|json"},
     *        defaults = {"sort" = "", "sortdir" = "asc", "pos" = 1, "num" = 10, "_format" = "html"},
     *        methods = {"GET"}
     * )
     * @Theme("admin")
     */
    public function adminViewAction(Request $request, $sort, $sortdir, $pos, $num)
    {
        return parent::adminViewAction($request, $sort, $sortdir, $pos, $num);
    }
    
    /**
     * @inheritDoc
     *
     * @Route("/ratingSystems/view/{sort}/{sortdir}/{pos}/{num}.{_format}",
     *        requirements = {"sortdir" = "asc|desc|ASC|DESC", "pos" = "\d+", "num" = "\d+", "_format" = "html|csv|rss|atom|xml|json"},
     *        defaults = {"sort" = "", "sortdir" = "asc", "pos" = 1, "num" = 10, "_format" = "html"},
     *        methods = {"GET"}
     * )
     */
    public function viewAction(Request $request, $sort, $sortdir, $pos, $num)
    {
        return parent::viewAction($request, $sort, $sortdir, $pos, $num);
    }
    
    /**
     * @inheritDoc
     * @Route("/admin/ratingSystems/handleSelectedEntries",
     *        methods = {"POST"}
     * )
     * @Theme("admin")
     */
    public function adminHandleSelectedEntriesAction(Request $request)
    {
        return parent::adminHandleSelectedEntriesAction($request);
    }
    
    /**
     * @inheritDoc
     * @Route("/ratingSystems/handleSelectedEntries",
     *        methods = {"POST"}
     * )
     */
    public function handleSelectedEntriesAction(Request $request)
    {
        return parent::handleSelectedEntriesAction($request);
    }
    
    /**
     * @inheritDoc
     * @Route("/ratingSystem/handleInlineRedirect/{idPrefix}/{commandName}/{id}",
     *        requirements = {"id" = "\d+"},
     *        defaults = {"commandName" = "", "id" = 0},
     *        methods = {"GET"}
     * )
     */
    public function handleInlineRedirectAction($idPrefix, $commandName, $id = 0)
    {
        return parent::handleInlineRedirectAction($idPrefix, $commandName, $id);
    }
    
    // feel free to add your own controller methods here
    /**
     * This method includes the common implementation code for adminView() and view().
     */
    protected function viewInternal(Request $request, $sort, $sortdir, $pos, $num, $isAdmin = false)
    {
        $objectType = 'ratingSystem';
        // permission check
        $permLevel = $isAdmin ? ACCESS_ADMIN : ACCESS_READ;
        $permissionHelper = $this->get('paustian_ratings_module.permission_helper');
        if (!$permissionHelper->hasComponentPermission($objectType, $permLevel)) {
            throw new AccessDeniedException();
        }

        $templateParameters = [
            'routeArea' => $isAdmin ? 'admin' : ''
        ];
        $controllerHelper = $this->get('paustian_ratings_module.controller_helper');
        $viewHelper = $this->get('paustian_ratings_module.view_helper');

        $request->query->set('sort', $sort);
        $request->query->set('sortdir', $sortdir);
        $request->query->set('pos', $pos);

        $sortableColumns = new SortableColumns($this->get('router'), 'paustianratingsmodule_ratingsystem_' . ($isAdmin ? 'admin' : '') . 'view', 'sort', 'sortdir');

        $sortableColumns->addColumns([
            new Column('id'),
            new Column('scaleDim'),
            new Column('iconFa'),
            new Column('iconUrl'),
            new Column('createdBy'),
            new Column('createdDate'),
            new Column('updatedBy'),
            new Column('updatedDate'),
        ]);

        $templateParameters = $controllerHelper->processViewActionParameters($objectType, $sortableColumns, $templateParameters);

        // filter by permissions
        $filteredEntities = [];
        foreach ($templateParameters['items'] as $ratingSystem) {
            if (!$permissionHelper->hasEntityPermission($ratingSystem, $permLevel)) {
                continue;
            }
            $filteredEntities[] = $ratingSystem;
        }
        $templateParameters['items'] = $filteredEntities;

        // fetch and return the appropriate template
        return $viewHelper->processTemplate($objectType, 'view', $templateParameters);
    }
}