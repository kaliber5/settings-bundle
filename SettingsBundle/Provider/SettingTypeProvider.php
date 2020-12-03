<?php

namespace Kaliber5\SettingsBundle\Provider;

use Sylius\Component\Attribute\AttributeType\CheckboxAttributeType;
use Sylius\Component\Attribute\AttributeType\TextAttributeType;
use Sylius\Component\Attribute\Model\AttributeValueInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Class SettingTypeProvider
 *
 * @package Kaliber5\SettingsBundle\Provider
 */
class SettingTypeProvider
{
    /**
     * type email
     */
    const EMAIL_TYPE = 'email';

    /**
     * available types
     */
    const TYPES = [
        TextAttributeType::TYPE,
        self::EMAIL_TYPE,
        CheckboxAttributeType::TYPE,
    ];

    /**
     * storage types for available types
     */
    const STORAGE_TYPES = [
        TextAttributeType::TYPE => AttributeValueInterface::STORAGE_TEXT,
        self::EMAIL_TYPE => AttributeValueInterface::STORAGE_TEXT,
        CheckboxAttributeType::TYPE => AttributeValueInterface::STORAGE_BOOLEAN,
    ];

    /**
     * form types for available types
     */
    const FORM_TYPES = [
        TextAttributeType::TYPE => TextType::class,
        self::EMAIL_TYPE => EmailType::class,
        CheckboxAttributeType::TYPE => CheckboxType::class,
    ];

    /**
     * @return array
     */
    public function getSupportedTypes()
    {
        return self::TYPES;
    }

    /**
     * @param string $type
     *
     * @return string|null
     */
    public function getStorageType(string $type): ?string
    {
        return self::STORAGE_TYPES[$type] ?? null;
    }

    /**
     * @param string $type
     *
     * @return string|null
     */
    public function getFormType(string $type): ?string
    {
        return self::FORM_TYPES[$type] ?? null;
    }
}