<?php

use \Mockery;
use Magister\Services\Translation\Translator;

class TranslationTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testSetDictionaryForTranslations()
    {
        $translator = new Translator();
        $translator->setDictionary(['CijferStr' => 'mark']);

        $this->assertEquals('mark', $translator->getDictionary()['CijferStr']);
    }

    public function testTranslationsCanBeFetched()
    {
        $translator = new Translator(['CijferStr' => 'mark']);

        $this->assertEquals('mark', $translator->translateForeign('CijferStr'));
    }
}