<?php

namespace Kaliber5\SettingsBundle\Admin;

use Kaliber5\SettingsBundle\Entity\SettingValue;
use Kaliber5\SettingsBundle\Provider\SettingTypeProvider;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Class SettingValueAdmin
 *
 * @package Kaliber5\SettingsBundle\Admin
 */
class SettingValueAdmin extends AbstractAdmin
{
    protected $translationDomain = 'Kaliber5SettingsBundle';

    /**
     * @var SettingTypeProvider
     */
    private $settingTypeProvider;

    /**
     * @param SettingTypeProvider $settingTypeProvider
     */
    public function setSettingTypeProvider(SettingTypeProvider $settingTypeProvider): void
    {
        $this->settingTypeProvider = $settingTypeProvider;
    }

    public function getNewInstance()
    {
        /** @var SettingValue $settingValue */
        $settingValue = parent::getNewInstance();
        if ($this->hasParentFieldDescription() && $this->getParentFieldDescription()->getAdmin()) {
            $settingValue->setAttribute($this->getParentFieldDescription()->getAdmin()->getSubject());
        }
        return $settingValue;
    }


    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {

        $valueFormType = $this->getSubject() ? $this->settingTypeProvider->getFormType($this->getSubject()->getType()): TextType::class;
        $formMapper
            ->add(
                'value',
                $valueFormType,
                [
                    'label' => 'label.admin.setting_value.value',
                ]
            )
        ;
    }


    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('value', null, ['label' => 'label.admin.name']);
    }
}