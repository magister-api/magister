<?php

namespace Magister\Services\Translation;

class Translator
{
    /**
     * The dictionary.
     *
     * @var array
     */
    protected $dictionary;

    /**
     * Words that has been translated.
     * 
     * @var array
     */
    protected $natives = [];

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

    /**
     * Get the native that has been translated.
     * 
     * @return array
     */
    public function getNatives()
    {
        return $this->natives;
    }

    /**
     * Set the native words.
     * 
     * @param Array $natives
     */
    public function setNatives(Array $natives)
    {
        $this->natives = $natives;
    }
}