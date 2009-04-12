<?php

namespace net\divbyzero\URL\Shorten;

/**
 * URL shortener based on the rev="canonical" concept
 *
 * @author    Martin Jansen <martin@divbyzero.net>
 * @copyright Martin Jansen, 2009
 * @link      http://revcanonical.wordpress.com/
 * @package   net.divbyzero.URL.Shorten
 */
class RevCanonical {

    /**
     * Attempts to shorten the given URL
     *
     * @param  string the URL to be shortened
     * @return string the shortened URL or null if the given URL could not be
     *                loaded or if does not provide a canonical URL.
     */
    public function shorten($url) {
        $doc = new \DOMDocument;

        /* The HTML is most likely invalid, so warnings need to be suppressed. */
        $result = @$doc->loadHTMLFile($url);
        if ($result === false) {
            return null;
        }

        $xml = simplexml_import_dom($doc);
        
        $links = $xml->xpath("//link[@rev=\"canonical\"]");
        if (count($links) > 0) {
            $link = $links[0];

            return (string)$links[0]['href'];
        }

        return null;
    }

    /**
     * Attempts to determine the original "long URL" for the given short URL
     *
     * @param  string the short URL
     * @return string the original "long URL" or null if no such URL could be
     *                determined
     */
    public function elongate($url) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

        curl_exec($ch);
        $info = curl_getinfo($ch);

        if ($info['redirect_time'] > 0) {
            return $info['url'];
        }

        return null;
    }
}
