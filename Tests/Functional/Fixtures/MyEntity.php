<?php declare(strict_types=1);
namespace Sitegeist\NodeEntities\Tests\Functional\Fixtures;

use Neos\ContentRepository\Domain\Model\Node;
use Neos\ContentRepository\Domain\Model\NodeData;
use Neos\ContentRepository\Domain\Service\Context;
use Sitegeist\NodeEntities\Application\NodeType\NodeTypeConstraintsConfiguration;
use Sitegeist\NodeEntities\Application\NodeType\InspectorTabConfiguration;
use Sitegeist\NodeEntities\Application\NodeType\NodeTypeBaseConfiguration;
use Sitegeist\NodeEntities\Application\NodeType\NodeTypeHelpConfiguration;
use Sitegeist\NodeEntities\Application\NodeType\SuperTypesConfiguration;
use Sitegeist\NodeEntities\Application\NodeType\NodeTypeUiBaseConfiguration;
use Sitegeist\NodeEntities\Application\NodeType\InspectorGroupConfiguration;
use Sitegeist\NodeEntities\Application\NodeType\TetheredNodeConfiguration;
use Sitegeist\NodeEntities\Application\Property\Editor\TextFieldEditorConfiguration;
use Sitegeist\NodeEntities\Application\Property\InlineConfiguration;
use Sitegeist\NodeEntities\Application\Property\PresetConfiguration;
use Sitegeist\NodeEntities\Application\Property\PropertyUiBaseConfiguration;
use Sitegeist\NodeEntities\Application\Property\InspectorBaseConfiguration;

#[NodeTypeBaseConfiguration('Acme.Demo:MyEntity', true, '${"Flickr plugin (" + q(node).property("tags") + ")"}')]
#[SuperTypesConfiguration(['Acme.Demo.ExtraMixin' => true, 'Acme.Demo.AnotherExtraMixin' => false])]
#[NodeTypeConstraintsConfiguration([
    'Neos.NodeTypes:Text' => true,
    'Neos.NodeTypes:Image' => false,
    '*' => false
])]
#[TetheredNodeConfiguration('someChild', 'Neos.Neos:ContentCollection', [
    'Neos.NodeTypes:Image' => true,
    '*' => false
])]
#[NodeTypeUiBaseConfiguration('My Entity', 'myIcon', 'Entities', 'start', true)]
#[NodeTypeHelpConfiguration('My help message', 'resource://AcmeCom.Website/NodeTypes/Thumbnails/foo.png')]
#[InspectorTabConfiguration('myTab', 'My tab', 42, 'myTabIcon')]
#[InspectorGroupConfiguration('myGroup', 'My group', 'after myOtherGroup', 'myGroupIcon', 'myTab', false)]
final class MyEntity extends Node
{
    #[PropertyUiBaseConfiguration('My string', 'Help message for my string', true, true, true, true)]
    #[InlineConfiguration('CKeditor5', 'Placeholder for my string', true, [
        'anchor' => true,
        'title' => true,
        'relNofollow' => true,
        'targetBlank' => true
    ], [
        'strong' => true,
        'em' => true,
        'u' => true,
        'sub' => true,
        'sup' => true,
        'del' => true,
        'p' => true,
        'h1' => true,
        'h2' => true,
        'h3' => true,
        'h4' => true,
        'h5' => true,
        'h6' => true,
        'pre' => true,
        'underline' => true,
        'strikethrough' => true,
        'removeFormat' => true,
        'left' => true,
        'right' => true,
        'center' => true,
        'justify' => true,
        'table' => true,
        'ol' => true,
        'ul' => true,
        'a' => true
    ])]
    #[InspectorBaseConfiguration('myGroup', 'end 50')]
    #[TextFieldEditorConfiguration('Enter subtitle here', true, 20, true, true, true, 'My title', true, true)]
    protected string $myString;

    protected ?string $myNullableString;

    protected string $myDefaultString = 'myDefaultValue';

    #[PresetConfiguration('myPreset')]
    protected ?string $myPresetBasedString;

    public function __construct(NodeData $nodeData, Context $context)
    {
        parent::__construct($nodeData, $context);
        $this->myString = $nodeData->getProperty('myString') ?: '';
        $this->myNullableString = $nodeData->getProperty('myNullableString');
        if ($nodeData->getProperty('myDefaultString')) {
            $this->myDefaultString = $nodeData->getProperty('myDefaultString');
        }
    }

    public function getMyString(): string
    {
        return $this->myString;
    }

    public function getMyNullableString(): ?string
    {
        return $this->myNullableString;
    }

    public function getMyDefaultString(): string
    {
        return $this->myDefaultString;
    }
}
