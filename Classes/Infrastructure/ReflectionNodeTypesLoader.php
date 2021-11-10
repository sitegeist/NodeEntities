<?php declare(strict_types=1);
namespace Sitegeist\NodeEntities\Infrastructure;

use Neos\ContentRepository\Configuration\NodeTypesLoader;
use Neos\ContentRepository\Domain\Projection\Content\NodeInterface;
use Neos\Flow\Configuration\Exception as ConfigurationException;
use Neos\Flow\Configuration\Exception\ParseErrorException;
use Neos\Flow\Configuration\Loader\LoaderInterface;
use Neos\Flow\Configuration\Source\YamlSource;
use Neos\Flow\Core\ApplicationContext;
use Neos\Flow\Package\PackageInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Reflection\ReflectionService;
use Neos\Utility\Arrays;
use Sitegeist\NodeEntities\Application\NodeType\NodeTypeBaseConfiguration;
use Sitegeist\NodeEntities\Application\NodeType\NodeTypeConfigurationInterface;
use Sitegeist\NodeEntities\Application\Property\PropertyConfigurationInterface;

#[Flow\Scope("singleton")]
class ReflectionNodeTypesLoader implements LoaderInterface
{
    private ReflectionService $reflectionService;

    public function __construct(
        ReflectionService $reflectionService
    ) {
        $this->reflectionService = $reflectionService;
    }

    /**
     * @param array<mixed,PackageInterface> $packages
     * @param ApplicationContext $context
     * @return array
     * @throws ConfigurationException
     * @throws ParseErrorException
     * @throws \ReflectionException
     */
    public function load(array $packages, ApplicationContext $context): array
    {
        $configuration = (new NodeTypesLoader(new YamlSource()))->load($packages, $context);

        foreach ($this->reflectionService->getAllImplementationClassNamesForInterface(NodeInterface::class) as $className) {
            if ($className !== 'Neos\ContentRepository\Domain\Model\Node') {
                $reflection = new \ReflectionClass($className);
                $nodeTypeBaseConfiguration = null;
                foreach ($reflection->getAttributes(NodeTypeBaseConfiguration::class) as $attribute) {
                    $nodeTypeBaseConfiguration = $attribute->newInstance();
                    break;
                }
                if (!$nodeTypeBaseConfiguration instanceof NodeTypeBaseConfiguration) {
                    continue;
                }
                $nodeTypeConfiguration = $this->extractNodeTypeConfigFromReflectionClass($reflection);
                $nodeTypeConfiguration['class'] = $className;
                $propertiesConfiguration = [];
                foreach ($reflection->getParentClass()->getTraits() as $trait) {
                    $propertiesConfiguration = Arrays::arrayMergeRecursiveOverrule(
                        $propertiesConfiguration,
                        $this->extractPropertiesFromReflectionClass($trait)
                    );
                }

                $propertiesConfiguration = Arrays::arrayMergeRecursiveOverrule(
                    $propertiesConfiguration,
                    $this->extractPropertiesFromReflectionClass($reflection)
                );
                $nodeTypeConfiguration['properties'] = $propertiesConfiguration;

                $configuration = Arrays::arrayMergeRecursiveOverrule(
                    $configuration,
                    $nodeTypeBaseConfiguration->renderConfiguration($className, $nodeTypeConfiguration)
                );
            }
        }

        return $configuration;
    }

    private function extractNodeTypeConfigFromReflectionClass(\ReflectionClass $reflection): array
    {
        $nodeTypeConfiguration = [];
        if ($reflection->getParentClass()) {
            foreach ($reflection->getParentClass()->getTraits() as $trait) {
                $nodeTypeConfiguration = Arrays::arrayMergeRecursiveOverrule(
                    $nodeTypeConfiguration,
                    $this->extractNodeTypeConfigFromReflectionClass($trait)
                );
            }
        }
        foreach ($reflection->getAttributes() as $attribute) {
            $configurationProcessor = $attribute->newInstance();
            if ($configurationProcessor instanceof NodeTypeConfigurationInterface) {
                $nodeTypeConfiguration = $configurationProcessor->processConfiguration($nodeTypeConfiguration);
            }
        }

        return $nodeTypeConfiguration;
    }

    private function extractPropertiesFromReflectionClass(\ReflectionClass $reflection): array
    {
        $properties = [];
        foreach ($reflection->getProperties() as $property) {
            if ($property->getType()) {
                $propertyConfiguration = [
                    'type' => $property->getType()->getName()
                ];
                if ($property->hasDefaultValue()) {
                    $propertyConfiguration['defaultValue'] = $property->getDefaultValue();
                }
                if (!$property->getType()->allowsNull()) {
                    $propertyConfiguration['validation']['Neos.Neos/Validation/NotEmptyValidator'] = [];
                    if (!$property->hasDefaultValue()) {
                        $propertyConfiguration['ui']['showInCreationDialog'] = true;
                    }
                }

                foreach ($property->getAttributes() as $attribute) {
                    $configurationProcessor = $attribute->newInstance();
                    if ($configurationProcessor instanceof PropertyConfigurationInterface) {
                        $propertyConfiguration = $configurationProcessor->processConfiguration($propertyConfiguration);
                    }
                }
                $properties[$property->getName()] = $propertyConfiguration;
            }
        }

        return $properties;
    }
}
