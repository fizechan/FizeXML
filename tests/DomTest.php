<?php

namespace app\controller;

use fize\xml\Dom;
use fize\xml\SimpleXml;
use DOMDocument;

/**
 * 测试DOM类
 */
class ControllerFizeXmlDom
{

	public function actionImportSimplexml()
    {
        $sxe = SimpleXml::loadString('<books><book><title>blah</title></book></books>');

        if ($sxe === false) {
            echo 'Error while parsing the document';
            exit;
        }

        $dom_sxe = Dom::importSimplexml($sxe);
        if (!$dom_sxe) {
            echo 'Error while converting XML';
            exit;
        }

        $dom = new DOMDocument('1.0');
        $dom_sxe = $dom->importNode($dom_sxe, true);
        $dom->appendChild($dom_sxe);

        echo $dom->saveXML();
	}
}
