<?php

namespace Magister\Services\Translation;

use Magister\Services\Translation\TranslationNotFoundException;

class Translator
{
    /**
     * The dictionary.
     *
     * @var array
     */
    protected $dictionary;

    /**
     * Constructor.
     */
    public function __construct(Array $dictionary = [])
    {
        $this->setDictionary($dictionary);
    }
   
    /**
     * Return the translation for the given foreign.
     * 
     * @param  string $foreign
     * @return string $native
     */
    public function translateForeign($foreign)
    {
        if (!$this->translationExistsFor($foreign)) {
            throw new TranslationNotFoundException('The foreign word that had to be translated could not be found in the dictionary!');
        }
        return $this->getTranslationFor($foreign);
    }

    /**
     * Determine if a translation for a given foreign exists.
     * 
     * @param  string $foreign
     * @return boolean
     */
    public function translationExistsFor($foreign)
    {
        return array_key_exists($foreign, $this->getDictionary());
    }

    /**
     * Grab translation from the dictionary.
     * 
     * @param  string $foreign
     * @return string $native
     */
    protected function getTranslationFor($foreign)
    {
        return $this->getDictionary()[$foreign];
    }

    /**
     * Set the dictionary for the translator.
     *
     * @param Array $dictionary
     */
    public function setDictionary(Array $dictionary)
    {
        $this->dictionary = $dictionary;
    }

    /**
     * Get the Translator's dictionary.
     *
     * @return Array $dictionary
     */
    public function getDictionary()
    {
        return $this->dictionary;
    }
}