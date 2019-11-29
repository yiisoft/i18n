<?php

namespace Yiisoft\I18n\Event;

class MissingTranslationEvent
{
    /**
     * @var string
     */
    private $category;
    /**
     * @var string
     */
    private $language;
    /**
     * @var string
     */
    private $message;

    public function __construct(string $category, string $language, string $message)
    {
        $this->category = $category;
        $this->language = $language;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
