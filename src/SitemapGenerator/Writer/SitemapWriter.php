<?php
namespace SitemapGenerator\Writer;

use SitemapGenerator\Exception\NotExistsException;
use SitemapGenerator\Exception\NotWritableException;

class SitemapWriter extends SimpleWriter
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
		$directoryToSaveSitemap = \Yii::getAlias($directoryToSaveSitemap);

		parent::setDirectoryToSaveSitemap($directoryToSaveSitemap);
	}
}