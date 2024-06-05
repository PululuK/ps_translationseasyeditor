<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

if (file_exists(__DIR__.'/vendor/autoload.php')) {
    require_once __DIR__.'/vendor/autoload.php';
}

class Ps_translationseasyeditor extends Module
{
    public function __construct()
    {
        $this->name = 'ps_translationseasyeditor';
        $this->tab = 'i18n_localization';
        $this->version = '1.0.0';
        $this->author = 'PululuK';
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = $this->trans('Translations easy editor');
        $this->description = $this->trans('Translations easy editor');

        $this->ps_versions_compliancy = ['min' => '1.7', 'max' => _PS_VERSION_];
    }

    public function isUsingNewTranslationSystem(): bool
    {
        return true;
    }
}
