<?php

namespace Sleepness\UberTranslationAdminBundle\Controller;

use Sleepness\UberTranslationAdminBundle\Form\Model\TranslationModel;
use Sleepness\UberTranslationAdminBundle\Form\Type\TranslationMessageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Controller responsive for managing translations
 *
 * @author Viktor Novikov <viktor.novikov95@gmail.com>
 * @author Alexandr Zhulev <alexandrzhulev@gmail.com>
 */
class TranslationController extends Controller
{
    /**
     * Output all translations
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $messagesFrontend = $this->get('memcached.messages.frontend.catalogue');

        $locale = $request->query->get('locale'); // get parameters for filtering
        $domain = $request->query->get('domain');
        $key    = $request->query->get('key');
        $text   = $request->query->get('text');

        $messages = $messagesFrontend->getAll();

        if (null !== $locale) {
            $messages = $messagesFrontend->buildByLocale($locale);
        }

        if (null !== $key) {
            $messages = $messagesFrontend->buildByKey($key);
        }

        if (null !== $domain) {
            $messages = $messagesFrontend->buildByDomain($domain);
        }

        if (null !== $text) {
            $messages = $messagesFrontend->buildByText($text);
        }

        $paginator = $this->get('knp_paginator'); // paginating results
        $messages = $paginator->paginate($messages, $request->query->get('page', 1), 15);
        $locales = $this->container->getParameter('sleepness_uber_translation.supported_locales');

        return $this->render('SleepnessUberTranslationAdminBundle:Translation:index.html.twig', array(
            'locales'  => $locales,
            'messages' => $messages,
        ));
    }

    /**
     * Edit translation
     *
     * @param Request $request
     * @param $localeKey
     * @param $_domain
     * @param $_key
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function editAction(Request $request, $localeKey, $_domain, $_key)
    {
        $mem = $this->get('uber.memcached');
        $translations = $mem->getItem($localeKey);
        $message = $translations[$_domain][$_key];
        if ($message === null) {
            throw new NotFoundHttpException('You try to edit non existing translation!');
        }
        $model = new TranslationModel($localeKey, $_domain, $_key, $message);
        $form = $this->createForm(new TranslationMessageType(), $model);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $formLocale = $model->getLocale();
            $formDomain = $model->getDomain();
            $formMessage = $model->getTranslation();
            $messagesFrontend = $this->get('memcached.messages.frontend.catalogue');
            $messagesFrontend->replace($_key, $localeKey, $_domain, $formLocale, $formDomain, $formMessage);
            $this->get('session')->getFlashBag()->add('translation_edited', 'edit_success');

            return $this->redirect($this->generateUrl('sleepness_translation_dashboard'));
        }

        return $this->render('SleepnessUberTranslationAdminBundle:Translation:edit.html.twig', array(
            'key'     => $_key,
            'locale'  => $localeKey,
            'domain'  => $_domain,
            'message' => $message,
            'form'    => $form->createView()
        ));
    }

    /**
     * Delete translation from memcache
     *
     * @param $localeKey
     * @param $_domain
     * @param $_key
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function deleteAction($localeKey, $_domain, $_key)
    {
        $mem = $this->get('uber.memcached');
        $translations = $mem->getItem($localeKey);
        if ($translations[$_domain][$_key] === null) {
            throw new NotFoundHttpException('You try to delete non existing translation!');
        }
        unset($translations[$_domain][$_key]);
        $mem->addItem($localeKey, $translations);
        $this->get('session')->getFlashBag()->add('translation_deleted', 'delete_success');

        return $this->redirect($this->generateUrl('sleepness_translation_dashboard'));
    }

    /**
     * Render form for creating new translations
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $model = new TranslationModel();
        $form = $this->createForm(new TranslationMessageType(), $model);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $cache = $this->get('uber.memcached');
            $translations = $cache->getItem($model->getLocale());
            if (null === $translations) {
                $cache->addItem($model->getLocale(), array(
                    $model->getDomain() => array(
                        $model->getKey() => $model->getTranslation(),
                    ),
                ));
            } else {
                if (!isset($translations[$model->getDomain()][$model->getKey()])) {
                    $translations[$model->getDomain()][$model->getKey()] = $model->getTranslation();
                } else {
                    $this->get('session')->getFlashBag()->add('translation_not_created', 'creation_failed');

                    return $this->redirect($this->generateUrl('sleepness_translation_create'));
                }
                $cache->addItem($model->getLocale(), $translations);
            }

            return $this->redirect($this->generateUrl('sleepness_translation_dashboard'));
        }

        return $this->render('SleepnessUberTranslationAdminBundle:Translation:create.html.twig', array('form' => $form->createView()));
    }

    /**
     * Check translation existence in Memcached
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function checkAction(Request $request)
    {
        $key    = $request->query->get('key');
        $domain = $request->query->get('domain');
        $locale = $request->query->get('locale');

        $translations = $this->get('uber.memcached')->getItem($locale);
        $exists = ($translations && array_key_exists($domain, $translations) && array_key_exists($key, $translations[$domain])) ? true : false;

        return new JsonResponse(array('isExists' => $exists));
    }
}
