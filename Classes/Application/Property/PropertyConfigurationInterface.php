<?php declare(strict_types=1);
namespace Sitegeist\NodeEntities\Application\Property;

interface PropertyConfigurationInterface
{
    public function processConfiguration(array $configuration): array;
}
