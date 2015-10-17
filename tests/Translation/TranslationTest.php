<?php

use \Mockery;
use Magister\Services\Translation\Translator;

class TranslationTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testModelCanBeSelected()
    {
        $translator = new Translator();
        $translator->setDictionary(['Magister\Models\Course' => ['description' => 'omschrijving']]);
        $translator = $translator->from('Magister\Models\Course');

        $this->assertEquals('Magister\Models\Course', $translator->getModel());
    }

    public function testTranslationExistsForSpecificModel()
    {
        $translator = new Translator();
        $translator->setDictionary(['Magister\Models\Course' => ['description' => 'omschrijving']]);
        
        $this->assertTrue($translator->from('Magister\Models\Course')->hasTranslation('description'));
    }

    public function testTranslationsCanBeFetchedForSpecificModel()
    {
        $translator = new Translator();
        $translator->setDictionary(['Magister\Models\Course' => ['description' => 'omschrijving']]);

        $this->assertEquals('omschrijving', $translator->from('Magister\Models\Course')->translateForeign('description'));
    }

    public function testTranslationsCanBeFetched()
    {
        $translator = new Translator();
        $translator->setDictionary(['Magister\Models\Course' => ['description' => 'omschrijving']]);

        $this->assertEquals('omschrijving', $translator->translateForeign('description', 'Magister\Models\Course'));
    }
}
