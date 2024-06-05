<?php

namespace PrestaShop\Module\PsTranslationsEasyEditor\Controller\Admin;

use Exception;
use PrestaShop\PrestaShop\Adapter\Shop\Context;
use PrestaShop\PrestaShop\Core\Translation\Storage\Provider\Definition\ProviderDefinitionInterface;
use PrestaShopBundle\Controller\Admin\Improve\International\TranslationsController as TranslationsControllerCore;

class TranslationsController extends TranslationsControllerCore
{
    /**
     * {@inheritdoc}
     */
    public function overviewAction()
    {
        try {
            return $this->render('@Modules/ps_translationseasyeditor/views/templates/admin/Translation/overview.html.twig', [
                'is_shop_context' => (new Context())->isShopContext(),
                'layoutTitle' => $this->trans('Translations', 'Admin.Navigation.Menu'),
                'layoutHeaderToolbarBtn' => $this->getEasyTranslationEditToolbarButtons(),
                'modifyTranslationsForm' => $this->getModifyTranslationsForm()->createView(),
            ]);
        } catch (Exception $exception) {
            $this->addFlash('error', $this->trans('An error has occurred: %s', 'Admin.International.Notification', [
                $exception->getMessage()
            ]));

            return parent::overviewAction();
        }
    }

    private function getModifyTranslationsForm()
    {
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

        $translationsTypeMatch = $this->translationsTypeMatch();
        if (isset($translationsTypeMatch[$type]) && $modifyTranslationsForm->has($translationsTypeMatch[$type])) {
            $modifyTranslationsForm->get($translationsTypeMatch[$type])->setData($selected);
        }

        return $modifyTranslationsForm;

    }

    /**
     * @return array
     */
    private function translationsTypeMatch(): array
    {
        return [
            ProviderDefinitionInterface::TYPE_MODULES => 'module',
            ProviderDefinitionInterface::TYPE_THEMES => 'theme',
            ProviderDefinitionInterface::TYPE_MAILS => 'email_content_type',
        ];
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
}
