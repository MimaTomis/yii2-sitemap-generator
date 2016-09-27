<?php
namespace SitemapGenerator\Tests\Component;

use SitemapGenerator\Component\SitemapGeneratorComponent;
use SitemapGenerator\Entity\SitemapItem;
use SitemapGenerator\Extractor\DataExtractorInterface;
use SitemapGenerator\Factory\MultipleGeneratorFactory;
use SitemapGenerator\Tests\TestCase;
use yii\helpers\FileHelper;

class SitemapGeneratorComponentTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->mockApplication();

        \Yii::setAlias('@temp', TEMP_DIR);
        FileHelper::createDirectory(TEMP_DIR);
    }

    public function tearDown()
    {
        parent::tearDown();

        FileHelper::removeDirectory(TEMP_DIR);
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function testCreateComponent()
    {
        $directoryToSave = '@temp';
        $fileName = 'test-file.name';
        $extractor = 'app\Extractor';
        $generator = MultipleGeneratorFactory::class;

        \Yii::$app->set('sitemapGenerator', [
            'class' => SitemapGeneratorComponent::class,
            'directoryToSaveSitemap' => $directoryToSave,
            'fileName' => $fileName,
            'extractor' => $extractor,
            'generatorFactory' => $generator
        ]);

        /** @var SitemapGeneratorComponent $sitemapGenerator */
        $sitemapGenerator = \Yii::$app->get("sitemapGenerator");

        $this->assertInstanceOf(SitemapGeneratorComponent::class, $sitemapGenerator);
        $this->assertEquals($directoryToSave, $sitemapGenerator->directoryToSaveSitemap);
        $this->assertEquals($fileName, $sitemapGenerator->fileName);
        $this->assertEquals($extractor, $sitemapGenerator->extractor);
        $this->assertEquals($generator, $sitemapGenerator->generatorFactory);
    }

    /**
     * Test generate sitemap
     *
     * @dataProvider generatorDataProvider
     *
     * @param string $file
     * @param array $items
     */
    public function testGenerate($file, array $items)
    {
        $mockExtractor = $this->getMock(DataExtractorInterface::class);
        $mockExtractor->expects($this->once())
            ->method('extractData')
            ->willReturn(new \ArrayIterator($items));

        \Yii::$container->set('test\Extractor', function() use($mockExtractor){
            return $mockExtractor;
        });

        \Yii::$app->set('sitemapGenerator', [
            'class' => SitemapGeneratorComponent::class,
            'directoryToSaveSitemap' => TEMP_DIR,
            'fileName' => $file,
            'extractor' => 'test\Extractor'
        ]);

        /** @var SitemapGeneratorComponent $sitemapGenerator */
        $sitemapGenerator = \Yii::$app->get("sitemapGenerator");
        $sitemapFile = $sitemapGenerator->generate();

        $this->assertXmlFileEqualsXmlFile(FIXTURES_DIR.'/'.$file, $sitemapFile);
    }

    public function generatorDataProvider()
    {
        return [
            [
                'new1.xml',
                [
                    new SitemapItem('http://test.com/abc'),
                    new SitemapItem('http://test2.com/cde')
                ]
            ],
            [
                'new2.xml',
                [
                    new SitemapItem('http://test4.com/efg'),
                    new SitemapItem('http://test3.com/por'),
                    new SitemapItem('http://test5.com/los')
                ]
            ]
        ];
    }
}