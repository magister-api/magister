<?php

use \Mockery;

class TranslationTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testSetDictionaryForTranslations()
    {
        $translator = new Magister\Services\Translation\Translator();

        $translator->setDictionary(['CijferStr' => 'mark']);

        $this->assertEquals('mark', $translator->getDictionary()['CijferStr']);
    }
}