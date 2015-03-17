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
    private $translation;

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
} 