<?php declare(strict_types=1);
namespace Sitegeist\NodeEntities\Application\Property\Editor;

use Sitegeist\NodeEntities\Application\Property\PropertyConfigurationInterface;

#[\Attribute]
final class TextFieldEditorConfiguration implements PropertyConfigurationInterface
{
    public function __construct(
        public ?string $placeholder = null,
        public ?bool $disabled = null,
        public ?int $maxlength = null,
        public ?bool $readonly = null,
        public ?bool $spellcheck = null,
        public ?bool $required = null,
        public ?string $title = null,
        public ?bool $autocapitalize = null,
        public ?bool $autocorrect = null
    ) {}

    public function processConfiguration(array $configuration): array
    {
        $editorOptions = [];
        if (!is_null($this->placeholder)) {
            $editorOptions['placeholder'] = $this->placeholder;
        }
        if (!is_null($this->disabled)) {
            $editorOptions['disabled'] = $this->disabled;
        }
        if (!is_null($this->maxlength)) {
            $editorOptions['maxlength'] = $this->maxlength;
        }
        if (!is_null($this->readonly)) {
            $editorOptions['readonly'] = $this->readonly;
        }
        if (!is_null($this->spellcheck)) {
            $editorOptions['spellcheck'] = $this->spellcheck;
        }
        if (!is_null($this->required)) {
            $editorOptions['required'] = $this->required;
        }
        if (!is_null($this->title)) {
            $editorOptions['title'] = $this->title;
        }
        if (!is_null($this->autocapitalize)) {
            $editorOptions['autocapitalize'] = $this->autocapitalize;
        }
        if (!is_null($this->autocorrect)) {
            $editorOptions['autocorrect'] = $this->autocorrect;
        }
        $configuration['ui']['inspector']['editor'] = 'Neos.Neos/Inspector/Editors/TextFieldEditor';
        $configuration['ui']['inspector']['editorOptions'] = $editorOptions;

        return $configuration;
    }
}
