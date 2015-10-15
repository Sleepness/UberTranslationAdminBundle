<?php

namespace Sleepness\UberTranslationAdminBundle\Frontend;

use Sleepness\UberTranslationBundle\Cache\UberMemcached;

/**
 * Prepare messages for output from memcached
 *
 * @author Viktor Novikov <viktor.novikov95@gmail.com>
 */
class MemcachedMessagesFrontend implements MessagesFrontendInterface
{
    /**
     * @var array
     */
    private $preparedTranslations = array();

    /**
     * @var UberMemcached
     */
    private $memcached;

    /**
     * @var array
     */
    private $supportedLocales;

    /**
     * @param UberMemcached $memcached
     * @param $supportedLocales
     */
    public function __construct(UberMemcached $memcached, $supportedLocales)
    {
        $this->memcached = $memcached;
        $this->supportedLocales = $supportedLocales;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareTranslations($domain, $keyYml, $message, $locale)
    {
        $this->preparedTranslations[] = array(
            'domain'       => $domain,
            'keyYml'       => $keyYml,
            'messageProps' => array(
                'messageText' => $message,
                'locale'      => $locale,
            ),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildByLocale($locale)
    {
        if (!preg_match('/^[a-z]{2}_[a-zA-Z]{2}$|[a-z]{2}/', $locale)) {
            return $this->preparedTranslations;
        }
        $translations = $this->memcached->getItem($locale);
        if ($translations) {
            foreach ($translations as $memcacheDomain => $messages) {
                foreach ($messages as $ymlKey => $value) {
                    $this->prepareTranslations($memcacheDomain, $ymlKey, $value, $locale);
                }
            }
        }

        return $this->preparedTranslations;
    }

    /**
     * {@inheritdoc}
     */
    public function buildByDomain($domain)
    {
        $locales = $this->supportedLocales;
        foreach ($locales as $key => $locale) {
            if (!preg_match('/^[a-z]{2}_[a-zA-Z]{2}$|[a-z]{2}/', $locale)) {
                continue;
            }
            $translations = $this->memcached->getItem($locale);
            if ($translations) {
                foreach ($translations as $memcacheDomain => $messages) {
                    if ($domain == $memcacheDomain) {
                        foreach ($messages as $ymlKey => $value) {
                            $this->prepareTranslations($domain, $ymlKey, $value, $locale);
                        }
                    }
                }
            }
        }

        return $this->preparedTranslations;
    }

    /**
     * {@inheritdoc}
     */
    public function buildByKey($keyYml)
    {
        $locales = $this->supportedLocales;
        foreach ($locales as $key => $locale) {
            if (!preg_match('/^[a-z]{2}_[a-zA-Z]{2}$|[a-z]{2}/', $locale)) {
                continue;
            }
            $translations = $this->memcached->getItem($locale);
            if ($translations) {
                foreach ($translations as $memcacheDomain => $messages) {
                    foreach ($messages as $ymlKey => $value) {
                        if ($ymlKey == $keyYml) {
                            $this->prepareTranslations($memcacheDomain, $keyYml, $value, $locale);
                        }
                    }
                }
            }
        }

        return $this->preparedTranslations;
    }

    /**
     * {@inheritdoc}
     */
    public function buildByText($text)
    {
        $locales = $this->supportedLocales;
        foreach ($locales as $key => $locale) {
            if (!preg_match('/^[a-z]{2}_[a-zA-Z]{2}$|[a-z]{2}/', $locale)) {
                continue;
            }
            $translations = $this->memcached->getItem($locale);
            if ($translations) {
                foreach ($translations as $memcacheDomain => $messages) {
                    foreach ($messages as $ymlKey => $value) {
                        if (stripos($value, $text) !== false) {
                            $this->prepareTranslations($memcacheDomain, $ymlKey, $value, $locale);
                        }
                    }
                }
            }
        }

        return $this->preparedTranslations;
    }

    /**
     * {@inheritdoc}
     */
    public function getAll()
    {
        $locales = $this->supportedLocales;
        foreach ($locales as $key => $locale) {
            if (!preg_match('/^[a-z]{2}_[a-zA-Z]{2}$|[a-z]{2}/', $locale)) {
                continue;
            }
            $translations = $this->memcached->getItem($locale);
            if ($translations) {
                foreach ($translations as $memcacheDomain => $messages) {
                    foreach ($messages as $ymlKey => $value) {
                        $this->prepareTranslations($memcacheDomain, $ymlKey, $value, $locale);
                    }
                }
            }
        }

        return $this->preparedTranslations;
    }

    /**
     * Replace translation by given properties
     *
     * @param $_key        - defined translation key
     * @param $_locale     - defined translation locale
     * @param $_domain     - defined translation domain
     * @param $formLocale  - changed translation locale
     * @param $formDomain  - changed translation domain
     * @param $formMessage - changed translation message
     */
    public function replace($_key, $_locale, $_domain, $formLocale, $formDomain, $formMessage)
    {
        $translations = $this->memcached->getItem($_locale);
        unset($translations[$_domain][$_key]);
        if ($formLocale != $_locale) {
            $this->memcached->addItem($_locale, $translations);
            $translations = $this->memcached->getItem($formLocale);
        }
        $translations[$formDomain][$_key] = $formMessage;
        $this->memcached->addItem($formLocale, $translations);
    }
}
