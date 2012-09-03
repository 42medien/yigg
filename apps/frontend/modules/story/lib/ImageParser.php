<?php
class ImageParser {
    /**
     * detects all images embeded in a site
     *
     * @param string $html
     * @return array
     */
    public static function detect($html) {
        if (!preg_match("~^<meta.*http-equiv\s*=\s*(\"|\')\s*Content-Type\s*(\"|\').*\/?>$~", $html)) {
            $html = preg_replace('/<head[^>]*>/i','<head>
                             <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
                            ',$html);
        }

        libxml_use_internal_errors(true);
        $lDoc = new DOMDocument();
        $lDoc->loadHTML($html);

        $lValues = array();

        //get all meta-elements
        $lTags = $lDoc->getElementsByTagName('img');
        //loop the metas
        foreach ($lTags as $lTag) {
            //if attribute name isset make a new entry in an array with key=name and value=content
            if ($lTag->hasAttribute('src')) {
                $lValues[] = $lTag->getAttribute('src');
            }
        }

        return $lValues;
    }

    /**
     * fetches all embeded images
     *
     * @param string $url
     * @return array
     */
    public static function fetch($url, $limit = 5, $flat = true) {
        //get the html as string
        $html = file_get_contents(urldecode($url)); //UrlUtils::getUrlContent(urldecode($url), 'GET');

        $images = self::detect($html);
        $result = array();



        foreach ($images as $image) {
            $image = self::abslink($image, $url);
            $size = @getimagesize($image);
            $size = $size[0] + $size[1];

            // ignore all (stats)images smaller than 5x5
            if ($size >= 10) {
                $result[] = array('size' => $size, 'image' => $image);
            }
        }

        usort($result, array("ImageParser", "sort"));
        //$result = array_unique($result);
        $result = array_slice($result, 0, $limit-1);

        // return only the images and crop the size
        if ($flat) {
            $flat = array();
            foreach ($result as $image) {
                $flat[] = $image['image'];
            }

            $result = $flat;
        }

        return $result;
    }

    /**
     * sorts the array by image size
     *
     * @param array $a
     * @param array $b
     * @return int
     */
    public static function sort($a, $b) {
        if ($a['size'] == $b['size']) {
            return 0;
        }
        return ($a['size'] > $b['size']) ? -1 : 1;
    }

    public static function abslink($imgSrc, $pageUrl)
    {
        $imgInfo = parse_url($imgSrc);
        if (! empty($imgInfo['host'])) {
            //img src is already an absolute URL
            return $imgSrc;
        }
        else {
            $urlInfo = parse_url($pageUrl);
            $base = $urlInfo['scheme'].'://'.$urlInfo['host'];
            if (substr($imgSrc,0,1) == '/') {
                //img src is relative from the root URL
                return $base . $imgSrc;
            }
            else {
                //img src is relative from the current directory
                return
                    $base
                    . substr($urlInfo['path'],0,strrpos($urlInfo['path'],'/'))
                    . '/' . $imgSrc;
            }
        }
    }

}