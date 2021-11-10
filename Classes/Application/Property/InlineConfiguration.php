<?php declare(strict_types=1);
namespace Sitegeist\NodeEntities\Application\Property;

#[\Attribute]
final class InlineConfiguration implements PropertyConfigurationInterface
{
    public function __construct(
        public ?string $editor = null,
        public ?string $placeholder = null,
        public ?bool $autoparagraph = null,
        public ?array $linking = null,
        public ?array $formatting = null
    ) {}

    public function processConfiguration(array $configuration): array
    {
        $editorOptions = [];
        if (!is_null($this->editor)) {
            $configuration['ui']['inline']['editor'] = $this->editor;
        }
        if (!is_null($this->placeholder)) {
            $editorOptions['placeholder'] = $this->placeholder;
        }
        if (!is_null($this->autoparagraph)) {
            $editorOptions['autoparagraph'] = $this->autoparagraph;
        }
        if (!is_null($this->linking)) {
            $editorOptions['linking'] = $this->linking;
        }
        if (!is_null($this->formatting)) {
            $editorOptions['formatting'] = $this->formatting;
        }

        $configuration['ui']['inlineEditable'] = true;
        $configuration['ui']['inline']['editorOptions'] = $editorOptions;

        return $configuration;
    }
}
