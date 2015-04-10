<?php

namespace Sleepness\UberTranslationAdminBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Model class for wrapping message translations
 *
 * @author Viktor Novikov <viktor.novikov95@gmail.com>
 */
class TranslationModel
{
    /**
     * @Assert\NotBlank()
     */
    private $locale;

    /**
     * @Assert\NotBlank()
     */
    private $domain;

    /**
     * @Assert\NotBlank()
     */
    private $translation;

    /**
     * @Assert\NotBlank()
     */
    private $key;

    /**
     * TranslationModel constructor
     *
     * @param string $locale
     * @param string $domain
     * @param string $key
     * @param string $translation
     */
    public function __construct($locale = null, $domain = null, $key = null, $translation = null)
    {
        $this->locale = $locale;
        $this->domain = $domain;
        $this->key = $key;
        $this->translation = $translation;
    }

    /**
     * @param $locale
     * @return $this
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param $domain
     * @return $this
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    /**
     * @return mixed
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param $translation
     * @return $this
     */
    public function setTranslation($translation)
    {
        $this->translation = $translation;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTranslation()
    {
        return $this->translation;
    }

    /**
     * @param $key
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }
} 
