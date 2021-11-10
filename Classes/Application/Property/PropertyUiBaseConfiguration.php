<?php declare(strict_types=1);
namespace Sitegeist\NodeEntities\Application\Property;

use Neos\Utility\Arrays;

#[\Attribute]
final class PropertyUiBaseConfiguration implements PropertyConfigurationInterface
{
    public function __construct(
        public string $label,
        public ?string $helpMessage,
        public ?bool $reloadIfChanged = null,
        public ?bool $reloadPageIfChanged = null,
        public ?bool $inlineEditable = null,
        public ?bool $showInCreationDialog = null
    ) {}


    public function processConfiguration(array $configuration): array
    {
        $uiConfiguration = [
            'label' => $this->label
        ];
        if ($this->helpMessage) {
            $uiConfiguration['help'] = [
                'message' => $this->helpMessage
            ];
        }
        if (!is_null($this->reloadIfChanged)) {
            $uiConfiguration['reloadIfChanged'] = $this->reloadIfChanged;
        }
        if (!is_null($this->reloadPageIfChanged)) {
            $uiConfiguration['reloadPageIfChanged'] = $this->reloadPageIfChanged;
        }
        if (!is_null($this->inlineEditable)) {
            $uiConfiguration['inlineEditable'] = $this->inlineEditable;
        }
        if (!is_null($this->showInCreationDialog)) {
            $uiConfiguration['showInCreationDialog'] = $this->showInCreationDialog;
        }

        $configuration['ui'] = Arrays::arrayMergeRecursiveOverrule(
            $configuration['ui'] ?? [],
            $uiConfiguration
        );

        return $configuration;
    }
}
