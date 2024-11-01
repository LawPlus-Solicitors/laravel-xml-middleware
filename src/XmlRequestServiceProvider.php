<?php

namespace XmlMiddleware;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;

class XmlRequestServiceProvider extends ServiceProvider
{

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        Request::macro('isXml', function () {
            // Method renamed in Symfony 6.2
            if (method_exists(Request::class, 'getContentTypeFormat')) {
                return strtolower($this->getContentTypeFormat()) === 'xml';
            }
            return strtolower($this->getContentType()) === 'xml';
        });

        Request::macro('xml', function ($assoc = true, $allowCData = false) {
            if (!$this->isXml() || !$content = $this->getContent()) {
                return $assoc ? [] : new \stdClass;
            }
            if (!$allowCData) {
                // Detect and convert ampersands because we couldn’t use CDATA from Proclaim when first implemented
                $content = str_replace('&', '&amp;', $content);
            }
            // Returns the xml input from a request
            $xml = simplexml_load_string($content, TypedXMLElement::class, LIBXML_NOCDATA);
            $json = json_encode($xml);

            return json_decode($json, $assoc);
        });
    }
}
