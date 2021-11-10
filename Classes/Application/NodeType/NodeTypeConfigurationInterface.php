<?php declare(strict_types=1);
namespace Sitegeist\NodeEntities\Application\NodeType;

interface NodeTypeConfigurationInterface
{
    public function processConfiguration(array $configuration): array;
}
