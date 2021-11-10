<?php declare(strict_types=1);
namespace Sitegeist\NodeEntities\Application\Property;

#[\Attribute]
final class PresetConfiguration implements PropertyConfigurationInterface
{
    public function __construct(
        public string $preset
    ) {}

    public function processConfiguration(array $configuration): array
    {
        $configuration['options']['preset'] = $this->preset;

        return $configuration;
    }
}
