<?php

namespace Kaliber5\SettingsBundle\Controller;

use Doctrine\Common\Collections\Collection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Kaliber5\SettingsBundle\Entity\SettingValue;
use Kaliber5\SettingsBundle\Field\SettingValueField;
use Kaliber5\SettingsBundle\Entity\Setting;
use Kaliber5\SettingsBundle\Provider\SettingTypeProvider;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;

class SettingCrudController extends AbstractCrudController
{
    /**
     * @var SettingTypeProvider
     */
    private $settingTypeProvider;

    /**
     * @param SettingTypeProvider $settingTypeProvider
     */
    public function __construct(SettingTypeProvider $settingTypeProvider)
    {
        $this->settingTypeProvider = $settingTypeProvider;
    }


    public static function getEntityFqcn(): string
    {
        return Setting::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions->add(Crud::PAGE_NEW, Action::SAVE_AND_CONTINUE);
        try {
            $actions->remove(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER);
            $actions->remove(Crud::PAGE_NEW, Action::SAVE_AND_RETURN);
        } catch (\InvalidArgumentException $e) {
            // do nothing...
        }
        return parent::configureActions($actions);
    }

    public function new(AdminContext $context)
    {
        $formOptions = $context->getCrud()->getNewFormOptions();
        $formOptions->setIfNotSet('constraints', [
            new UniqueEntity('code')
        ]);

        return parent::new($context);
    }


    public function configureFields(string $pageName): iterable
    {
        $edit = Crud::PAGE_EDIT === $pageName;

        return [
            TextField::new('name'),
            TextareaField::new('description'),
            TextField::new('code')
                ->setFormTypeOption('disabled', $edit),
            ChoiceField::new('type')
                // @TODO not all types are supported. Start with text type for now
                ->setChoices(['text' => 'text'])
//                ->setChoices(array_combine($this->settingTypeProvider->getSupportedTypes(), $this->settingTypeProvider->getSupportedTypes()))
                ->setFormTypeOption('disabled', $edit),
            // @TDOD theres an bug with multiple fields
//            BooleanField::new('multiple')
//                ->setCustomOption(BooleanField::OPTION_RENDER_AS_SWITCH, false)
//                ->setFormTypeOption('disabled', $edit),
            SettingValueField::new('settingValues')->onlyWhenUpdating(),
        ];
    }

    public function createEditForm(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormInterface
    {
        return $this->createEditFormBuilder($entityDto, $formOptions, $context)->getForm();
    }

    public function createEditFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createEditFormBuilder($entityDto, $formOptions, $context);

        $formBuilder->get('settingValues')->addModelTransformer(new CallbackTransformer(
            function (Collection $settingValues) {
                return $settingValues->map(function(SettingValue $settingValue) {
                    return $settingValue->getValue();
                });
            },
            function ($values) use ($formBuilder) {
                // @TODO this us currently working just with non multiple fields
                /** @var Setting $setting */
                $setting = $formBuilder->getForm()->getData();
                $settingValue = $setting->getSettingValues()->first();
                foreach ($values as $value) {
                    $settingValue->setValue($value);
                }

                return $setting->getSettingValues();
            }
        ));

        return $formBuilder;
    }
}