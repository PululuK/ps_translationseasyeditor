<?php

namespace PrestaShop\Module\PsTranslationsEasyEditor\Service;

use PrestaShop\PrestaShop\Core\Translation\Storage\Provider\Definition\ProviderDefinitionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class BackwardCompatibilityManager
{
    public function isPrestashopV8(): bool
    {
        return version_compare(_PS_VERSION_, '8', '>=');
    }

    /**
     * @return array
     */
    public function translationsTypeMatch(): array
    {
        if ($this->isPrestashopV8()) {
            return [
                ProviderDefinitionInterface::TYPE_MODULES => 'module',
                ProviderDefinitionInterface::TYPE_THEMES => 'theme',
                ProviderDefinitionInterface::TYPE_MAILS => 'email_content_type',
            ];
        } else {
            return [
                'modules' => 'module',
                'themes' => 'theme',
                'mails' => 'email_content_type',
            ];
        }
    }

    public function getTranslationOverviewTemplateName(): string
    {
        if ($this->isPrestashopV8()) {
            return '@PrestaShop/Admin/Improve/International/Translations/overview.html.twig';
        }

        return '@PrestaShop/Admin/Translations/overview.html.twig';
    }

    public function getTranslationsControllerInstance(): AbstractController
    {
        if ($this->isPrestashopV8()) {
            return new \PrestaShopBundle\Controller\Admin\Improve\International\TranslationsController();
        }

        return new \PrestaShopBundle\Controller\Admin\TranslationsController();
    }
}
