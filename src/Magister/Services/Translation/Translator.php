<?php

namespace Magister\Services\Translation;

use Magister\Services\Translation\TranslationNotFoundException;
use \InvalidArgumentException;

class Translator
{
    /**
     * The dictionary.
     *
     * @var array
     */
    protected $dictionary;

    /**
     * The given model.
     * 
     * @var string
     */
    protected $model;

    /**
     * Create new translator instance.
     */
    public function __construct(array $dictionary = [])
    {
        $this->setDictionary($dictionary);
    }
   
   /**
    * Determine which words should be Translatable by defining the model
    * 
    * @param  string $model
    * @return \Magister\Services\Translation\Translator
    * @throws InvalidArgumentException
    */
    public function from($model)
    {
        if (isset($this->getDictionary()[$model])) {
            $this->model = $model;
        
            return $this;
        }
        
        throw new InvalidArgumentException(sprintf('Could not find translations for the model: "%s" in the dictionary', $model));
    }

    /**
     * Determine if a translation for a given foreign exists.
     * 
     * @param  string $foreign
     * @return boolean
     */
    public function hasTranslation($foreign, $model = null)
    {
        $model = $this->getModel($model);

        return array_key_exists($foreign, $this->getDictionary()[$model]);
    }

    /**
     * Return the translation for the given foreign.
     * 
     * @param  string $foreign
     * @return string
     * @throws \Magister\Services\Translation\TranslationNotFoundException
     */
    public function translateForeign($foreign, $model = null)
    {
        $model = $this->getModel($model);

        if (!$this->hasTranslation($foreign, $model)) {
            throw new TranslationNotFoundException('The foreign word that had to be translated could not be found in the dictionary!');
        }

        return $this->getTranslationFor($foreign, $model);
    }

    /**
     * Grab translation from the dictionary.
     * 
     * @param  string $foreign
     * @return string $native
     */
    protected function getTranslationFor($foreign, $model = null)
    {
        $model = $this->getModel($model);

        return $this->getDictionary()[$model][$foreign];
    }

    /**
     * Get the corresponding model.
     * 
     * @param  string $model
     * @return string
     */
    public function getModel($model = null)
    {
        if (!is_null($this->model)) {
            return $this->model;
        }

        return $model;
    }

    /**
     * Set the dictionary for the translator.
     *
     * @param Array $dictionary
     */
    public function setDictionary(array $dictionary)
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
