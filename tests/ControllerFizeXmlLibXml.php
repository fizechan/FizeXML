<?php

namespace app\controller;

use fize\xml\LibXml;
use fize\xml\SimpleXml;
use fize\xml\Dom;
use DOMDocument;

/**
 * LibXML测试
 */
class ControllerFizeXmlLibXml
{

	public function actionClearErrors()
    {
        $sxe = SimpleXml::loadString('<books111><book><title>blah</title></book></books111>');

        if ($sxe === false) {
            echo 'Error while parsing the document';

            $errors = LibXml::getErrors();
            var_dump($errors);

            LibXml::clearErrors();

            echo '***********************************************************\r\n';

            $errors = LibXml::getErrors();  //由于clearErrors，$errors为空
            var_dump($errors);
        }

        $dom_sxe = Dom::importSimplexml($sxe);
        if (!$dom_sxe) {
            echo 'Error while converting XML';
            exit;
        }

        $dom = new DOMDocument('1.0');
        $dom_sxe = $dom->importNode($dom_sxe, true);
        $dom_sxe = $dom->appendChild($dom_sxe);

        echo $dom->saveXML();
	}


    public function actionDisableEntityLoader()
    {
        $sxe = SimpleXml::loadString('<books><book><title>blah</title></book></books>');
        $dom_sxe = Dom::importSimplexml($sxe);
        $dom = new DOMDocument('1.0');
        $dom_sxe = $dom->importNode($dom_sxe, true);
        $dom->appendChild($dom_sxe);
        echo $dom->saveXML();

        LibXml::disableEntityLoader();

        //禁用后将报错
        $sxe = SimpleXml::loadString('<books111><book><title>blah</title></book></books111>');
        $dom_sxe = Dom::importSimplexml($sxe);
        $dom = new DOMDocument('1.0');
        $dom_sxe = $dom->importNode($dom_sxe, true);
        $dom->appendChild($dom_sxe);
        echo $dom->saveXML();
        $errors = LibXml::getErrors();
        var_dump($errors);
    }
}
