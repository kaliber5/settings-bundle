<?php

namespace Kaliber5\SettingsBundle\Admin;

use Kaliber5\SettingsBundle\Entity\Setting;
use Kaliber5\SettingsBundle\Entity\SettingValue;
use Kaliber5\SettingsBundle\Provider\SettingTypeProvider;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\Form\Type\CollectionType;
use Sonata\Form\Validator\ErrorElement;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Class SettingAdmin
 *
 * @package Kaliber5\SettingsBundle\Admin
 */
class SettingAdmin extends AbstractAdmin
{
    /**
     * @var string
     */
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

    /**
     * @param Setting $object
     */
    public function preValidate($object)
    {
        if ($object->getType()) {
            $object->setStorageType($this->settingTypeProvider->getStorageType($object->getType()));
        }
    }

    public function validate(ErrorElement $errorElement, $object)
    {
        $errorElement->addConstraint(new UniqueEntity(['fields' => ['code']]));
    }

    public function prePersist($object)
    {
        parent::prePersist($object);
        $this->handleSettingsValueAdmin($object);
    }

    public function preUpdate($object)
    {
        parent::preUpdate($object);
        $this->handleSettingsValueAdmin($object);
    }

    // More or less related to https://github.com/sonata-project/SonataAdminBundle/issues/1502
    protected function handleSettingsValueAdmin($object)
    {
        foreach ($this->getFormFieldDescriptions() as $fieldName => $fieldDescription) {
            // detect embedded Admins
            if ($fieldDescription->getName() === 'settingValues') {
                /** @var AbstractAdmin $associationAdmin */
                $associationAdmin = $fieldDescription->getAssociationAdmin();
                $associationObjectGetter = 'get'.ucfirst($fieldName);
                $associationObjects = $object->$associationObjectGetter();
                foreach ($associationObjects as $associationObject) {
                    $associationAdmin->preUpdate($associationObject);
                }
            }
        }
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $editDisabled = (false === $this->isCurrentRoute('create'));
        $formMapper
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'label.admin.setting.name',
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'label' => 'label.admin.setting.description',
                ]
            )
            ->add(
                'code',
                TextType::class,
                [
                    'label' => 'label.admin.setting.code',
                    'disabled' => $editDisabled,
                ]
            )
            ->add(
                'type',
                ChoiceType::class,
                [
                    'choices' =>   array_combine($this->settingTypeProvider->getSupportedTypes(), $this->settingTypeProvider->getSupportedTypes()),
                    'disabled' => $editDisabled,
                ]
            )
            ->add(
                'multiple',
                CheckboxType::class,
                [
                    'disabled' => $editDisabled,
                    'required' => false,
                ]
            )
        ;

        if (false === $this->isCurrentRoute('create')) {
            if ($this->getSubject() && $this->getSubject()->isMultiple()) {
                $formMapper
                    ->add(
                        'settingValues',
                        CollectionType::class,
                        [
                        ],
                        [
                            'edit' => 'inline',
                            'inline' => 'table'
                        ]
                    )
                ;
            } else {
                if ($this->getSubject() && $this->getSubject()->getSettingValues()->isEmpty()) {
                    $this->getSubject()->addSettingValue($this->getSubject()->createSettingValue());
                }
                $formMapper
                    ->add(
                        'settingValues',
                        CollectionType::class,
                        [
                            'type_options' => [
                                'delete' => false,

                            ],
                            'btn_add' => false,
                        ],
                        [
                            'edit' => 'inline',
                            'inline' => 'table'
                        ]
                    )
                ;
            }

        }
    }


    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name', null, ['label' => 'label.admin.setting.name'])
            ->add('description', null)
            ->add('code', null, ['label' => 'label.admin.setting.code'])
            ->add('type', null)
            ->add('multiple', null)
            ->add('values', 'array')
            ;
    }
}