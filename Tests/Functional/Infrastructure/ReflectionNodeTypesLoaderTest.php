<?php declare(strict_types=1);
namespace Sitegeist\NodeEntities\Tests\Functional\Infrastructure;

use Neos\Flow\Core\ApplicationContext;
use Neos\Flow\Package\PackageManager;
use Neos\Flow\Reflection\ReflectionService;
use Neos\Flow\Tests\FunctionalTestCase;
use PHPUnit\Framework\Assert;
use Sitegeist\NodeEntities\Infrastructure\ReflectionNodeTypesLoader;

final class ReflectionNodeTypesLoaderTest extends FunctionalTestCase
{
    public function testLoad(): void
    {
        /** @var ReflectionService $reflectionService */
        $reflectionService = $this->objectManager->get(ReflectionService::class);
        $subject = new ReflectionNodeTypesLoader($reflectionService);

        /** @var PackageManager $packageManager */
        $packageManager = $this->objectManager->get(PackageManager::class);

        Assert::assertEquals(
            [
                'Acme.Demo:MyEntity' => [
                    'class' => 'Sitegeist\NodeEntities\Tests\Functional\Fixtures\MyEntity',
                    'aggregate' => true,
                    'label' => '${"Flickr plugin (" + q(node).property("tags") + ")"}',
                    'superTypes' => [
                        'Acme.Demo.ExtraMixin' => true,
                        'Acme.Demo.AnotherExtraMixin' => false
                    ],
                    'constraints' => [
                        'nodeTypes' => [
                            '*' => false,
                            'Neos.NodeTypes:Text' => true,
                            'Neos.NodeTypes:Image' => false
                        ]
                    ],
                    'childNodes' => [
                        'someChild'=> [
                            'type' => 'Neos.Neos:ContentCollection',
                            'constraints' => [
                                'nodeTypes' => [
                                    'Neos.NodeTypes:Image' => true,
                                    '*' => false
                                ]
                            ]
                        ]
                    ],
                    'ui' => [
                        'label' => 'My Entity',
                        'icon' => 'myIcon',
                        'group' => 'Entities',
                        'position' => 'start',
                        'inlineEditable' => true,
                        'help' => [
                            'message' => 'My help message',
                            'thumbnail' => 'resource://AcmeCom.Website/NodeTypes/Thumbnails/foo.png'
                        ],
                        'inspector' => [
                            'tabs' => [
                                'myTab' => [
                                    'label' => 'My tab',
                                    'position' => 42,
                                    'icon' => 'myTabIcon'
                                ]
                            ],
                            'groups' => [
                                'myGroup' => [
                                    'label' => 'My group',
                                    'position' => 'after myOtherGroup',
                                    'icon' => 'myGroupIcon',
                                    'tab' => 'myTab',
                                    'collapsed' => false
                                ]
                            ],
                        ]
                    ],
                    'properties' => [
                        'myString' => [
                            'type' => 'string',
                            'ui' => [
                                'label' => 'My string',
                                'help' => [
                                    'message' => 'Help message for my string'
                                ],
                                'reloadIfChanged' => true,
                                'reloadPageIfChanged' => true,
                                'showInCreationDialog' => true,
                                'inlineEditable' => true,
                                'inline' => [
                                    'editor' => 'CKeditor5',
                                    'editorOptions' => [
                                        'placeholder' => 'Placeholder for my string',
                                        'autoparagraph' => true,
                                        'linking' => [
                                            'anchor' => true,
                                            'title' => true,
                                            'relNofollow' => true,
                                            'targetBlank' => true
                                        ],
                                        'formatting' => [
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
                                       ]
                                    ]
                                ],
                                'inspector' => [
                                    'group' => 'myGroup',
                                    'position' => 'end 50',
                                    'editor' => 'Neos.Neos/Inspector/Editors/TextFieldEditor',
                                    'editorOptions' => [
                                        'placeholder' => 'Enter subtitle here',
                                        'disabled' => true,
                                        'maxlength' => 20,
                                        'readonly' => true,
                                        'spellcheck' => true,
                                        'required' => true,
                                        'title' => 'My title',
                                        'autocapitalize' => true,
                                        'autocorrect' => true
                                    ]
                                ]
                            ],
                            'validation' => [
                                'Neos.Neos/Validation/NotEmptyValidator' => []
                            ]
                        ],
                        'myNullableString' => [
                            'type' => 'string'
                        ],
                        'myDefaultString' => [
                            'type' => 'string',
                            'defaultValue' => 'myDefaultValue',
                            'validation' => [
                                'Neos.Neos/Validation/NotEmptyValidator' => []
                            ]
                        ],
                        'myPresetBasedString' => [
                            'type' => 'string',
                            'options' => [
                                'preset' => 'myPreset'
                            ]
                        ]
                    ]
                ]
            ],
            $subject->load(
                [$packageManager->getPackage('Sitegeist.NodeEntities')],
                new ApplicationContext('Testing')
            )
        );
    }
}
