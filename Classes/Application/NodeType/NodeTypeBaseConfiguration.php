<?php declare(strict_types=1);
namespace Sitegeist\NodeEntities\Application\NodeType;

use Neos\ContentRepository\Domain\Model\NodeLabelGeneratorInterface;
use Neos\Utility\Arrays;

#[\Attribute]
final class NodeTypeBaseConfiguration
{
    public function __construct(
        public string $nodeTypeName,
        public ?bool $aggregate = null,
        public ?string $label = null
    ) {}

    public function renderConfiguration(string $className, array $nodeTypeConfiguration): array
    {
        $configuration = [
            'class' => $className
        ];
        if (!is_null($this->aggregate)) {
            $configuration['aggregate'] = $this->aggregate;
        }
        if ($this->label) {
            if (
                class_exists($this->label)
                && in_array(NodeLabelGeneratorInterface::class, class_implements($this->label) ?: [])
            ) {
                $configuration['label']['generatorClass'] = $this->label;
            } else {
                $configuration['label'] = $this->label;
            }
        }
        $configuration = Arrays::arrayMergeRecursiveOverrule($configuration, $nodeTypeConfiguration);

        return [$this->nodeTypeName => $configuration];
    }
}
