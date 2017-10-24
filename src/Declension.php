<?php

namespace Maslauskas\Declension;

/**
 * Class Declension
 * @package Maslauskas\Declension
 */
class Declension
{
    /**
     * @var string
     */
    public $language;

    /**
     * @var mixed
     */
    public $endings = [];

    /**
     * @param null $language
     */
    public function setLanguage($language = null)
    {
        $this->language = $language ?? app()->getLocale();

        if($this->languageExists()) {
            $this->endings = $this->getWordEndings();
        }
    }

    /**
     * Determines whether language file for current locale exists.
     *
     * @return bool
     */
    private function languageExists()
    {
        return file_exists(__DIR__ . "/languages/{$this->language}.php");
    }

    /**
     * Determines whether grammatical case for current locale exists.
     *
     * @param $case
     * @return bool
     */
    private function caseExists($case)
    {
        return array_key_exists($case, $this->endings);
    }

    /**
     * @param $case
     * @param $args
     * @return mixed
     */
    public function __call($case, $args)
    {
        $args = array_flatten(func_get_args());

        return call_user_func_array([$this, "inflect"], $args);
    }

    /**
     * @param $case
     * @param $string
     * @param null $language
     * @return string
     */
    private function inflect($case, $string, $language = null)
    {
        $this->setLanguage($language);

        if(!$this->languageExists() || !$this->caseExists($case)) {
            return $string;
        }

        $endings = $this->getCaseEndings($case);

        return $this->replaceWordEndings($string, $endings);
    }

    /**
     * @param $string
     * @param $endings
     * @return string
     */
    private function replaceWordEndings($string, $endings)
    {
        $words = preg_split('/\s+/', $this->sanitizeString($string));

        $search = array_map(function ($ending) {
            return '/' . $ending . '$/';
        }, array_keys($endings));

        $words = array_map(function($word) use ($search, $endings) {
            return preg_replace($search, $endings, $word);
        }, $words);

        return implode(' ', $words);
    }

    /**
     * @param $word
     * @return string
     */
    private function sanitizeString($word)
    {
        $word = mb_eregi_replace('[^a-ž]|\s+', ' ', $word);
        $word = trim($word);
        $word = mb_convert_case($word, MB_CASE_TITLE);

        return $word;
    }

    /**
     * @param $case
     * @return mixed
     */
    private function getCaseEndings($case)
    {
        return $this->endings[$case];
    }

    /**
     * @return mixed
     */
    private function getWordEndings()
    {
        return require(__DIR__ . "/languages/{$this->language}.php");
    }
}