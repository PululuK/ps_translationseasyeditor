<?php

namespace PrestaShop\Module\PsTranslationsEasyEditor\Controller\Admin;

use Exception;
use PrestaShop\Module\PsTranslationsEasyEditor\Service\BackwardCompatibilityManager;
use PrestaShop\PrestaShop\Adapter\Shop\Context;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;

class TranslationsController extends FrameworkBundleAdminController
{
    /**
     * {@inheritdoc}
     */
    public function overviewAction()
    {
        $backwardCompatibilityManager = $this->getBackwardCompatibilityManager();

        try {
            return $this->render('@Modules/ps_translationseasyeditor/views/templates/admin/Translation/overview.html.twig', [
                'is_shop_context' => (new Context())->isShopContext(),
                'layoutTitle' => $this->trans('Translations', 'Admin.Navigation.Menu'),
                'layoutHeaderToolbarBtn' => $this->getEasyTranslationEditToolbarButtons(),
                'modifyTranslationsForm' => $this->getModifyTranslationsForm()->createView(),
                'overviewTemplateName' => $backwardCompatibilityManager->getTranslationOverviewTemplateName(),
            ]);
        } catch (Exception $exception) {
            $this->addFlash('error', $this->trans('Module [ps_translationseasyeditor] : An error has occurred: %s', 'Admin.International.Notification', [
                $exception->getMessage()
            ]));

            return $backwardCompatibilityManager->getTranslationsControllerInstance()::overviewAction();
        }
    }

    private function getModifyTranslationsForm()
    {
        $backwardCompatibilityManager = $this->getBackwardCompatibilityManager();
        $currentRequest = $this->get('request_stack')->getCurrentRequest();
        $type = $currentRequest->query->get('type');
        $selected = $currentRequest->query->get('selected');

        $modifyTranslationsForm = $this->get('prestashop.admin.translations_settings.modify_translations.form_handler')->getForm();

        if ($modifyTranslationsForm->has('translation_type')) {
            $modifyTranslationsForm->get('translation_type')->setData($type);
        }

        if ($modifyTranslationsForm->has('language')) {
            $modifyTranslationsForm->get('language')->setData($currentRequest->query->get('lang'));
        }

        $translationsTypeMatch = $backwardCompatibilityManager->translationsTypeMatch();
        if (isset($translationsTypeMatch[$type]) && $modifyTranslationsForm->has($translationsTypeMatch[$type])) {
            $modifyTranslationsForm->get($translationsTypeMatch[$type])->setData($selected);
        }

        return $modifyTranslationsForm;

    }

    /**
     * @return array
     */
    private function getEasyTranslationEditToolbarButtons(): array
    {
        $toolbarButtons['easy_edit'] = [
            'href' => '#',
            'desc' => $this->trans('Modify', 'Admin.Actions'),
            'icon' => 'edit',
            'modal_target' => '#easy_modify_translation_form_modal',
        ];

        return $toolbarButtons;
    }

    private function getBackwardCompatibilityManager(): BackwardCompatibilityManager
    {
        return $this->get('prestashop.module.ps_translationseasyeditor.backward_compatibility_manager');
    }
}
