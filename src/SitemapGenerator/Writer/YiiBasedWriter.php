<?php
namespace SitemapGenerator\Writer;

use SitemapGenerator\Exception\NotExistsException;
use SitemapGenerator\Exception\NotWritableException;
use yii\base\InvalidParamException;
use yii\helpers\FileHelper;

class YiiBasedWriter extends SimpleWriter
{
	/**
	 * Set directory path, where files be saved.
	 * Convert yii2 alias to absolute path.
	 *
	 * @param string $directoryToSaveSitemap
	 *
	 * @throws NotExistsException
	 * @throws NotWritableException
	 */
	public function setDirectoryToSaveSitemap($directoryToSaveSitemap)
	{
		try {
			$directoryToSaveSitemap = \Yii::getAlias($directoryToSaveSitemap);

			parent::setDirectoryToSaveSitemap($directoryToSaveSitemap);
		} catch (InvalidParamException $e) {
			throw new NotExistsException($directoryToSaveSitemap, 'Alias not found', 0, $e);
		}
	}

	/**
	 * Create directory to save sitemap
	 *
	 * @param string $directoryToSaveSitemap
	 *
	 * @return boolean
	 */
	protected function createDirectory($directoryToSaveSitemap)
	{
		return FileHelper::createDirectory($directoryToSaveSitemap, 0765, true);
	}
}