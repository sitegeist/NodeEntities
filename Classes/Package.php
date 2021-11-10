<?php declare(strict_types=1);
namespace Sitegeist\NodeEntities;

/*
 * This file is part of the Sitegeist.NodeEntities package.
 */

use Neos\Flow\Core\Booting\Sequence;
use Neos\Flow\Core\Booting\Step;
use Neos\Flow\Reflection\ReflectionService;
use Sitegeist\NodeEntities\Infrastructure\ReflectionNodeTypesLoader;
use Neos\Flow\Configuration\ConfigurationManager;
use Neos\Flow\Core\Bootstrap;
use Neos\Flow\Package\Package as BasePackage;

/**
 * The ContentRepository Package
 */
class Package extends BasePackage
{
    /**
     * Invokes custom PHP code directly after the package manager has been initialized.
     *
     * @param Bootstrap $bootstrap The current bootstrap
     * @return void
     */
    public function boot(Bootstrap $bootstrap)
    {
        $dispatcher = $bootstrap->getSignalSlotDispatcher();
        $dispatcher->connect(Sequence::class, 'afterInvokeStep', function (Step $step) use ($bootstrap) {
            if ($step->getIdentifier() === 'neos.flow:resources') {
                $configurationManager = $bootstrap->getObjectManager()->get(ConfigurationManager::class);
                $configurationManager->registerConfigurationType(
                    'NodeTypes',
                    new ReflectionNodeTypesLoader(
                        $bootstrap->getObjectManager()->get(ReflectionService::class)
                    )
                );
            }
        });
    }
}
