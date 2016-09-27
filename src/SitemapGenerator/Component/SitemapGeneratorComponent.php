<?php
namespace SitemapGenerator\Component;

use SitemapGenerator\Extractor\CompositeDataExtractor;
use SitemapGenerator\Extractor\DataExtractorInterface;
use SitemapGenerator\Factory\GeneratorFactoryInterface;
use SitemapGenerator\Factory\SimpleGeneratorFactory;
use yii\base\Component;

class SitemapGeneratorComponent extends Component
{
	/**
	 * Directory to save sitemap files
	 *
	 * @var string
	 */
	public $directoryToSaveSitemap;
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
	 * Initialize component
	 */
	public function init()
	{
		\Yii::$container->set(CompositeDataExtractor::class, function($c, $params){
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
		$factory = $this->createGeneratorFactory();
		$extractor = $this->createExtractor($this->extractor);
		$generator = $factory->createGenerator($this->directoryToSaveSitemap);

		return $generator->generate($this->fileName, $extractor);
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