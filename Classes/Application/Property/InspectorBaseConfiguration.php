<?php declare(strict_types=1);
namespace Sitegeist\NodeEntities\Application\Property;

#[\Attribute]
final class InspectorBaseConfiguration implements PropertyConfigurationInterface
{
    public function __construct(
        public string $group,
        public int|string|null $position
    ) {}

    public function processConfiguration(array $configuration): array
    {
        $inspectorConfiguration = [
            'group' => $this->group
        ];
        if (!is_null($this->position)) {
            $inspectorConfiguration['position'] = $this->position;
        }
        $configuration['ui']['inspector'] = $inspectorConfiguration;

        return $configuration;
    }
}
