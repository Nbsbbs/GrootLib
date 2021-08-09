<?php

namespace Nbsbbs\Common\Search;

use Nbsbbs\Common\Language\LanguageInterface;

class TranslatedRequest
{
    /**
     * @var LanguageInterface
     */
    private LanguageInterface $language;

    /**
     * @var string
     */
    private string $translatedQuery;

    /**
     * @var string
     */
    private string $originalQuery;

    /**
     * TranslatedRequest constructor.
     *
     * @param string $originalQuery
     * @param string $translatedQuery
     * @param LanguageInterface $language
     */
    public function __construct(string $originalQuery, string $translatedQuery, LanguageInterface $language)
    {
        $this->originalQuery = $originalQuery;
        $this->translatedQuery = $translatedQuery;
        $this->language = $language;
    }

    /**
     * @return LanguageInterface
     */
    public function getLanguage(): LanguageInterface
    {
        return $this->language;
    }

    /**
     * @return string
     */
    public function getTranslatedQuery(): string
    {
        return $this->translatedQuery;
    }

    /**
     * @return string
     */
    public function getOriginalQuery(): string
    {
        return $this->originalQuery;
    }
}
