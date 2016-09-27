<?php
namespace SitemapGenerator\Tests\Writer;

use SitemapGenerator\Exception\NotExistsException;
use SitemapGenerator\Tests\TestCase;
use SitemapGenerator\Writer\YiiBasedWriter;
use yii\helpers\FileHelper;

class YiiBasedWriterTest extends TestCase
{
    /**
     * Settings up application, create temp directory, define @temp alias
     */
    public function setUp()
    {
        parent::setUp();

        FileHelper::createDirectory(TEMP_DIR);
        \Yii::setAlias('@temp', TEMP_DIR);

        $this->mockApplication();
    }

    /**
     * Destroy application, remove temp directory
     */
    public function tearDown()
    {
        parent::tearDown();

        FileHelper::removeDirectory(TEMP_DIR);
    }

    /**
     * Test write file by predefined alias
     */
    public function testWriteByAlias()
    {
        $content = '<?xml version="1.0" encoding="UTF-8"?><abc>43435</abc>';

        $writer = new YiiBasedWriter('@temp');
        $writer->write('new.xml', $content);

        $this->assertXmlStringEqualsXmlFile(TEMP_DIR.'/new.xml', $content);
    }

    /**
     * Test write content into file by unknown alias
     */
    public function testWriteByUnknownAlias()
    {
        $this->setExpectedException(NotExistsException::class);

        $content = '<?xml version="1.0" encoding="UTF-8"?><abc>43435</abc>';

        $writer = new YiiBasedWriter('@temp2');
        $writer->write('new.xml', $content);
    }

    /**
     * Test write content into file by real path
     */
    public function testWriteByRealPath()
    {
        $content = '<?xml version="1.0" encoding="UTF-8"?><abc>43435</abc>';

        $writer = new YiiBasedWriter(TEMP_DIR);
        $writer->write('new.xml', $content);

        $this->assertXmlStringEqualsXmlFile(TEMP_DIR.'/new.xml', $content);
    }
}