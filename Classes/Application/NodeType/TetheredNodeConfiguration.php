<?php declare(strict_types=1);
namespace Sitegeist\NodeEntities\Application\NodeType;

#[\Attribute]
final class TetheredNodeConfiguration implements NodeTypeConfigurationInterface
{
    public function __construct(
        public string $nodeName,
        public string $nodeType,
        public array $constraints
    ) {}

    public function processConfiguration(array $configuration): array
    {
        $configuration['childNodes'][$this->nodeName] = [
            'type' => $this->nodeType,
            'constraints' => [
                'nodeTypes' => $this->constraints
            ]
        ];

        return $configuration;
    }
}
