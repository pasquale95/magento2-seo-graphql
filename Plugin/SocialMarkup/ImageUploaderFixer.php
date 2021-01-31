<?php
/**
 * @author: Pasquale Convertini <pasqualeconvertini95@gmail.com>
 * @github: @Pasquale95
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE', which is part of this source code package.
 */

namespace Paskel\Seo\Plugin\SocialMarkup;

use Exception;
use Magento\Cms\Model\Page\DataProvider;
use Magento\Framework\Filesystem\DirectoryList;
use Paskel\Seo\Api\Data\SocialMarkupInterface;

/**
 * Class ImageUploaderFixer
 * @package Paskel\Seo\Plugin
 */
class ImageUploaderFixer
{
    /**
     * @var DirectoryList
     */
    private $_dir;

    /**
     * ImageUploaderFixer constructor.
     * @param DirectoryList $dir
     */
    public function __construct(
        DirectoryList $dir
    ) {
        $this->_dir = $dir;
    }

    /**
     * Convert loaded data for ImageUploader into array to let be rendered
     * correctly in the admin preview.
     *
     * @param DataProvider $subject
     * @param array $result
     * @return array
     */
    public function afterGetData(DataProvider $subject, array $result)
    {
        foreach ($result as $page) {
            if (array_key_exists(SocialMarkupInterface::IMAGE_FIELD_DB, $page)
                and !empty($page[SocialMarkupInterface::IMAGE_FIELD_DB])) {
                // The page is a news and it has an image
                $size = 0;
                $imageUrl = $page[SocialMarkupInterface::IMAGE_FIELD_DB];
                try {
                    // if remote url, try to get size using url
                    $size = $this->remote_filesize($imageUrl);
                    if (!$size) {
                        // if the previous failed, try to look under the pub folder
                        $pubPath = $this->_dir->getPath('pub');
                        $size = filesize($pubPath . $imageUrl);
                    }
                } catch (Exception $e) {
                    // silence exception
                }
                $image = [
                    [
                        'name' => pathinfo($imageUrl)['basename'],
                        'type' => 'image/jpeg',
                        'previewType' => "image",
                        'url' => $imageUrl,
                        'size' => $size
                    ]
                ];
                $result[$page['page_id']][SocialMarkupInterface::IMAGE_FIELD_DB] = $image;
            }
        }
        return $result;
    }

    /**
     * Get size of file from URL
     *
     * @param $url
     * @return false|int
     */
    public function remote_filesize($url)
    {
        //move from https to http since otherwise we cannot check Content-Length
        $url = str_replace("https", "http", $url);
        static $regex = '/^Content-Length: *+\K\d++$/im';
        if (!$fp = @fopen($url, 'r')) {
            return false;
        }
        if (
            isset($http_response_header) &&
            preg_match($regex, implode("\n", $http_response_header), $matches)
        ) {
            return (int)$matches[0];
        }
        return strlen(stream_get_contents($fp));
    }
}
