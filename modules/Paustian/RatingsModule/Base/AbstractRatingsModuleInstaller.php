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

use Exception;
use Zikula\Core\AbstractExtensionInstaller;
use Paustian\RatingsModule\Entity\RatingEntity;
use Paustian\RatingsModule\Entity\HookAssignmentEntity;

/**
 * Installer base class.
 */
abstract class AbstractRatingsModuleInstaller extends AbstractExtensionInstaller
{
    /**
     * @var string[]
     */
    protected $entities = [
        RatingEntity::class,
        HookAssignmentEntity::class
    ];

    public function install()
    {
        $logger = $this->container->get('logger');
    
        // create all tables from according entity definitions
        try {
            $this->schemaTool->create($this->entities);
        } catch (Exception $exception) {
            $this->addFlash('error', $this->__('Doctrine Exception') . ': ' . $exception->getMessage());
            $logger->error(
                '{app}: Could not create the database tables during installation. Error details: {errorMessage}.',
                ['app' => 'PaustianRatingsModule', 'errorMessage' => $exception->getMessage()]
            );
    
            throw $exception;
        }
    
        // set up all our vars with initial values
        $this->setVar('ratingScale', 5);
        $this->setVar('iconFa', 'fa-star');
        $this->setVar('halfIconFa', 'fa-star-half');
        $this->setVar('emptyIconFa', 'fa-star-empty');
        $this->setVar('iconUrl', '');
        $this->setVar('halfIconUrl', '');
        $this->setVar('emptyIconUrl', '');
        $this->setVar('ratingEntriesPerPage', 10);
        $this->setVar('linkOwnRatingsOnAccountPage', true);
        $this->setVar('showOnlyOwnEntries', false);
        $this->setVar('allowModerationSpecificCreatorForRating', false);
        $this->setVar('allowModerationSpecificCreationDateForRating', false);
    
        // initialisation successful
        return true;
    }
    
    public function upgrade($oldVersion)
    {
    /*
        $logger = $this->container->get('logger');
    
        // upgrade dependent on old version number
        switch ($oldVersion) {
            case '1.0.0':
                // do something
                // ...
                // update the database schema
                try {
                    $this->schemaTool->update($this->entities);
                } catch (Exception $exception) {
                    $this->addFlash('error', $this->__('Doctrine Exception') . ': ' . $exception->getMessage());
                    $logger->error(
                        '{app}: Could not update the database tables during the upgrade.'
                            . ' Error details: {errorMessage}.',
                        ['app' => 'PaustianRatingsModule', 'errorMessage' => $exception->getMessage()]
                    );
    
                    throw $exception;
                }
        }
    */
    
        // update successful
        return true;
    }
    
    public function uninstall()
    {
        $logger = $this->container->get('logger');
    
        try {
            $this->schemaTool->drop($this->entities);
        } catch (Exception $exception) {
            $this->addFlash('error', $this->__('Doctrine Exception') . ': ' . $exception->getMessage());
            $logger->error(
                '{app}: Could not remove the database tables during uninstallation. Error details: {errorMessage}.',
                ['app' => 'PaustianRatingsModule', 'errorMessage' => $exception->getMessage()]
            );
    
            throw $exception;
        }
    
        // remove all module vars
        $this->delVars();
    
        // uninstallation successful
        return true;
    }
}
