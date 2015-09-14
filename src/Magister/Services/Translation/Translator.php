<?php

namespace Magister\Services\Translation;

class Translator
{
    /**
     * The dictionary.
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