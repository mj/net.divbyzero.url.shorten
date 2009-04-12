<?php

namespace net\divbyzero\URL\Shorten;

error_reporting(E_ALL);

require_once "PHPUnit/Framework.php";
require_once "RevCanonical.php";

/**
 * Unit test for {@link RevCanonical}
 *
 * @author    Martin Jansen <martin@divbyzero.net>
 * @copyright Martin Jansen, 2009
 * @package   net.divbyzero.URL.Shorten
 * @license http://www.opensource.org/licenses/bsd-license.php BSD
 */
class RevCanonicalTest extends \PHPUnit_Framework_TestCase {
    
    public function testShorten() {
        $shortener = new RevCanonical;

        $shortURL = $shortener->shorten("http://www.flickr.com/photos/martinjansen/3162582895/");
        $this->assertEquals("http://flic.kr/p/5Pt4Hi", $shortURL);

        $shortURL = $shortener->shorten("http://example.invalid/");
        $this->assertNull($shortURL);

        $shortURL = $shortener->shorten("http://example.com/");
        $this->assertNull($shortURL);
    }
    
    public function testEnlongate() {
        $shortener = new RevCanonical;

        $longURL = $shortener->elongate("http://flic.kr/p/5Pt4Hi");
        $this->assertEquals("http://www.flickr.com/photos/martinjansen/3162582895/", $longURL);

        $longURL = $shortener->elongate("http://example.com/");
        $this->assertNull($longURL);

        $longURL = $shortener->elongate("http://example.invalid/");
        $this->assertNull($longURL);
    }
}
