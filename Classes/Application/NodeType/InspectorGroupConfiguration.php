<?php declare(strict_types=1);
namespace Sitegeist\NodeEntities\Application\NodeType;

#[\Attribute]
final class InspectorGroupConfiguration implements NodeTypeConfigurationInterface
{
    public function __construct(
        public string $identifier,
        public string $label,
        public int|string $position,
        public string $icon,
        public ?string $tab,
        public ?bool $collapsed
    ) {}

    public function processConfiguration(array $configuration): array
    {
        $groupConfiguration = [
            'label' => $this->label,
            'position' => $this->position,
            'icon' => $this->icon
        ];
        if ($this->tab) {
            $groupConfiguration['tab'] = $this->tab;
        }
        if (!is_null($this->collapsed)) {
            $groupConfiguration['collapsed'] = $this->collapsed;
        }
        $configuration['ui']['inspector']['groups'][$this->identifier] = $groupConfiguration;

        return $configuration;
    }
}
