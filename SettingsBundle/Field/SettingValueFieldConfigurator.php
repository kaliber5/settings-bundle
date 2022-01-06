<?php

namespace Kaliber5\SettingsBundle\Field;

use EasyCorp\Bundle\EasyAdminBundle\Config\Asset;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldConfiguratorInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\AssetDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FieldDto;
use Kaliber5\SettingsBundle\Entity\Setting;
use Kaliber5\SettingsBundle\Entity\SettingValue;
use Kaliber5\SettingsBundle\Provider\SettingTypeProvider;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class SettingValueFieldConfigurator implements FieldConfiguratorInterface
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


    public function supports(FieldDto $field, EntityDto $entityDto): bool
    {
        return Setting::class === $entityDto->getFqcn() && SettingValue::class === $field->getDoctrineMetadata()->get('targetEntity');
    }

    public function configure(FieldDto $field, EntityDto $entityDto, AdminContext $context): void
    {
        /** @var Setting $setting */
        $setting = $entityDto->getInstance();
        $field->setFormType(CollectionType::class);
        $this->addCssClass($field, 'field-array');
        $this->addJsFiles($field, 'bundles/easyadmin/form-type-collection.js');
        $this->setDefaultColumns($field, 'col-md-7 col-xxl-6');
        $field->setTemplateName('crud/field/array');
        $field->setTemplateName('label/empty');
        $field->setTemplatePath(null);
        $field->setFormTypeOption('entry_type', $this->settingTypeProvider->getFormType($setting->getType()));

        if ($setting->isMultiple()) {
            $field->setFormTypeOptionIfNotSet('allow_add', true);
            $field->setFormTypeOptionIfNotSet('allow_delete', true);
            $field->setFormTypeOptionIfNotSet('delete_empty', true);
            $field->setFormTypeOptionIfNotSet('entry_options.label', false);

        } else {
            $field->setFormTypeOptionIfNotSet('allow_add', false);
            $field->setFormTypeOptionIfNotSet('allow_delete', false);
            $field->setFormTypeOptionIfNotSet('delete_empty', false);
        }
    }

    private function addCssClass(FieldDto $dto, string $cssClass): void
    {
        $dto->setCssClass($dto->getCssClass().' '.$cssClass);
    }

    /**
     * @param string|Asset $pathsOrAssets
     */
    private function addJsFiles(FieldDto $dto, ...$pathsOrAssets): void
    {
        foreach ($pathsOrAssets as $pathOrAsset) {
            if (!\is_string($pathOrAsset) && !($pathOrAsset instanceof Asset)) {
                throw new \RuntimeException(sprintf('The argument passed to %s() can only be a string or a object of type "%s".', __METHOD__, Asset::class));
            }

            if (\is_string($pathOrAsset)) {
                $dto->addJsAsset(new AssetDto($pathOrAsset));
            } else {
                $dto->addJsAsset($pathOrAsset->getAsDto());
            }
        }
    }

    private function setDefaultColumns(FieldDto $dto, $cols): void
    {
        if (!\is_int($cols) && !\is_string($cols)) {
            throw new \InvalidArgumentException(sprintf('The value passed to the "setDefaultColumns()" method of the "%s" field can only be an integer or a string ("%s" was given).', $this->dto->getProperty(), get_debug_type($cols)));
        }

        $dto->setDefaultColumns(\is_int($cols) ? 'col-md-'.$cols : $cols);
    }
}