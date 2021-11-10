<?php declare(strict_types=1);
namespace Sitegeist\NodeEntities\Application\NodeType;

#[\Attribute]
final class NodeTypeConstraintsConfiguration implements NodeTypeConfigurationInterface
{
    public function __construct(
        public array $constraints
    ) {}

    public function processConfiguration(array $configuration): array
    {
        $configuration['constraints']['nodeTypes'] = $this->constraints;

        return $configuration;
    }
}
