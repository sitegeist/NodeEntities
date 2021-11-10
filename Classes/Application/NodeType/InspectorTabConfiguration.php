<?php declare(strict_types=1);
namespace Sitegeist\NodeEntities\Application\NodeType;

#[\Attribute]
final class InspectorTabConfiguration implements NodeTypeConfigurationInterface
{
    public function __construct(
        public string $identifier,
        public string $label,
        public int|string $position,
        public string $icon
    ) {}

    public function processConfiguration(array $configuration): array
    {
        $configuration['ui']['inspector']['tabs'][$this->identifier] = [
            'label' => $this->label,
            'position' => $this->position,
            'icon' => $this->icon
        ];

        return $configuration;
    }
}
