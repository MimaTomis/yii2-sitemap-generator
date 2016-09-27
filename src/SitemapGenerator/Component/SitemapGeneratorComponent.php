<?php
namespace SitemapGenerator\Component;

use SitemapGenerator\Extractor\CompositeDataExtractor;
use SitemapGenerator\Extractor\DataExtractorInterface;
use SitemapGenerator\Factory\GeneratorFactoryInterface;
use SitemapGenerator\Factory\MultipleGeneratorFactory;
use SitemapGenerator\Factory\SimpleGeneratorFactory;
use yii\base\Component;

/**
 * @property string $directoryToSaveSitemap
 */
class SitemapGeneratorComponent extends Component
{
	/**
	 * Config for generator factory;
	 *
	 * @var string|array
	 */
	public $generatorFactory = SimpleGeneratorFactory::class;
	/**
	 * Name of sitemap file
	 *
	 * @var string
	 */
	public $fileName = 'sitemap.xml';
	/**
	 * Config for data extractor
	 *
	 * @var string|array
	 */
	public $extractor = [
		'class' => CompositeDataExtractor::class,
		'extractors' => []
	];
	/**
	 * Directory to save sitemap files
	 *
	 * @var string
	 */
	protected $directoryToSaveSitemap;

	/**
	 * Initialize component
	 */
	public function init()
	{
		\Yii::$container->set(CompositeDataExtractor::class, function ($c, $params) {
			$compositeExtractor = new CompositeDataExtractor();

			if (!empty($params['extractors'])) {
				foreach ($params['extractors'] as $extractor) {
					$extractor = $this->createExtractor($extractor);
					$compositeExtractor->attachExtractor($extractor);
				}
			}

			return $compositeExtractor;
		});
	}

	/**
	 * Generate sitemap and return path to file
	 *
	 * @return string
	 */
	public function generate()
	{
		$extractor = $this->createExtractor($this->extractor);
		$factory = $this->createGeneratorFactory();
		$generator = $factory->createGenerator($this->directoryToSaveSitemap);

		return $generator->generate($this->fileName, $extractor);
	}

	/**
	 * Get directory to save sitemap. Default directory is yii2 "@webroot".
	 *
	 * @return string
	 */
	public function getDirectoryToSaveSitemap()
	{
		return $this->directoryToSaveSitemap ?: '@webroot';
	}

	/**
	 * Set directory to save sitemap
	 *
	 * @param string $directoryToSaveSitemap
	 */
	public function setDirectoryToSaveSitemap($directoryToSaveSitemap)
	{
		$this->directoryToSaveSitemap = $directoryToSaveSitemap;
	}

	/**
	 * Create generator factory from parameters;
	 *
	 * @return GeneratorFactoryInterface
	 *
	 * @throws \yii\base\InvalidConfigException
	 */
	protected function createGeneratorFactory()
	{
		return \Yii::createObject($this->generatorFactory);
	}

	/**
	 * Create instance of DataExtractorInterface subclass
	 *
	 * @param $extractor
	 *
	 * @return DataExtractorInterface
	 *
	 * @throws \yii\base\InvalidConfigException
	 */
	protected function createExtractor($extractor)
	{
		return \Yii::createObject($extractor);
	}
}