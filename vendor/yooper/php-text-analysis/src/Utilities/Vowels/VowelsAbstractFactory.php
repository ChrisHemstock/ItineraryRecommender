<?php
declare(strict_types = 1);
namespace TextAnalysis\Utilities\Vowels;

/**
 * Factory wrapper for Vowels
 * @author yooper
 */
abstract class VowelsAbstractFactory 
{
    /**
     * Protected from use 
     */
    protected function __construct(){}
   
    /**
     * Return the correct language vowel checker
     * @param string $language
     * @return \NlpTools\Utils\VowelAbstractFactory
     * @throws \Exception 
     */
    static public function factory($language = 'English')
    {
        $className = "\\".__NAMESPACE__."\\{$language}Vowels";
        if(class_exists($className)) { 
            return new $className();
        }         
        throw new \Exception("Class $className does not exist");
    }

    /**
     * Check if the the letter at the given index is a vowel 
     */
    abstract public function isVowel($word, $index);
            
}