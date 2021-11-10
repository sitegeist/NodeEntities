<?php declare(strict_types=1);
namespace Sitegeist\NodeEntities\Application\NodeType;

use Neos\Utility\Arrays;

#[\Attribute]
final class NodeTypeUiBaseConfiguration implements NodeTypeConfigurationInterface
{
    public function __construct(
        public string $label,
        public string $icon,
        public ?string $group = null,
        public string|int|null $position = null,
        public ?bool $inlineEditable = null
    ) {}

    public function processConfiguration(array $configuration): array
    {
        $uiConfiguration = [
            'label' => $this->label,
            'icon' => $this->icon
        ];
        if ($uiConfiguration) {
            if ($this->group) {
                $uiConfiguration['group'] = $this->group;
            }
            if (!is_null($this->position)) {
                $uiConfiguration['position'] = $this->position;
            }
            if (!is_null($this->inlineEditable)) {
                $uiConfiguration['inlineEditable'] = $this->inlineEditable;
            }
        }
        $configuration['ui'] = Arrays::arrayMergeRecursiveOverrule(
            $configuration['ui'] ?? [],
            $uiConfiguration
        );

        return $configuration;
    }
}
