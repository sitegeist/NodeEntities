<?php declare(strict_types=1);
namespace Sitegeist\NodeEntities\Application\NodeType;

#[\Attribute]
final class NodeTypeHelpConfiguration implements NodeTypeConfigurationInterface
{
    public function __construct(
        public string $message,
        public ?string $thumbnail
    ) {}

    public function processConfiguration(array $configuration): array
    {
        $configuration['ui']['help'] = [
            'message' => $this->message
        ];
        if ($this->thumbnail) {
            $configuration['ui']['help']['thumbnail'] = $this->thumbnail;
        }

        return $configuration;
    }
}
