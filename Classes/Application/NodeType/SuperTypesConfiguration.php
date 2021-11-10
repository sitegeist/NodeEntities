<?php declare(strict_types=1);
namespace Sitegeist\NodeEntities\Application\NodeType;

#[\Attribute]
final class SuperTypesConfiguration implements NodeTypeConfigurationInterface
{
    public function __construct(
        public array $superTypes
    ) {}

    public function processConfiguration(array $configuration): array
    {
        $configuration['superTypes'] = $this->superTypes;

        return $configuration;
    }
}
