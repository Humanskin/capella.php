<?php


namespace Capella;

require 'Uploader.php';
require 'CapellaImage.php';
require 'CapellaException.php';

/**
 * Class Capella
 * @package Capella
 *
 * Base class for working with Capella API.
 *
 * @example
 *
 * use \Capella\Capella;
 *
 * $image = Capella::upload('my-nice-picture.jpg');
 * $url = $image->crop(100, 100)->url();
 *
 * $otherImage = Capella::image('a123bcd-ef45-6789-1234-a5678b91cd2e');
 * $otherUrl = $otherImage->pixelize(50)->url();
 *
 */
class Capella
{
    private static $uploader = null;

    private function __construct() {}

    /**
     * Upload image to capella.pics
     *
     * Return CapellaImage class to apply filters
     *
     * @param string $path - path to image. Can be path to local file or external url
     * @return CapellaImage
     * @throws CapellaException
     */
    public static function upload($path)
    {
        if (is_null(self::$uploader)) {
            self::$uploader = new Uploader();
        }

        $response = self::$uploader->upload($path);

        if (!$response || !$response->success) {
            $errormsg = 'Upload to capella.pics failed.';

            if ($response && $response->message) {
                $errormsg .= ' Reason: ' . $response->message;
            }

            throw new CapellaException($errormsg);
        }

        return new CapellaImage($response->id);
    }

    /**
     * Return CapellaImage class with passed image $id to apply filters
     *
     * @param string $id - image id
     * @return CapellaImage
     */
    public static function image($id)
    {
        return new CapellaImage($id);
    }
}