<?php

namespace Kaliber5\SettingsBundle\Provider;

use Sylius\Component\Attribute\AttributeType\CheckboxAttributeType;
use Sylius\Component\Attribute\AttributeType\IntegerAttributeType;
use Sylius\Component\Attribute\AttributeType\TextAttributeType;
use Sylius\Component\Attribute\Model\AttributeValueInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
     * type file
     */
    const FILE_TYPE = 'file';

    /**
     * type file
     */
    const FLOAT_TYPE = 'float';

    /**
     * type longtext
     */
    const LONGTEXT_TYPE = 'longtext';

    const STORAGE_LONGTEXT = 'longtext';

    /**
     * available types
     */
    const TYPES = [
        TextAttributeType::TYPE,
        self::LONGTEXT_TYPE,
        self::EMAIL_TYPE,
        CheckboxAttributeType::TYPE,
        self::FILE_TYPE,
        IntegerAttributeType::TYPE,
        self::FLOAT_TYPE,
    ];

    /**
     * storage types for available types
     */
    const STORAGE_TYPES = [
        TextAttributeType::TYPE => AttributeValueInterface::STORAGE_TEXT,
        self::EMAIL_TYPE => AttributeValueInterface::STORAGE_TEXT,
        self::FILE_TYPE => AttributeValueInterface::STORAGE_TEXT,
        CheckboxAttributeType::TYPE => AttributeValueInterface::STORAGE_BOOLEAN,
        IntegerAttributeType::TYPE => AttributeValueInterface::STORAGE_INTEGER,
        self::FLOAT_TYPE => AttributeValueInterface::STORAGE_FLOAT,
        self::LONGTEXT_TYPE => self::STORAGE_LONGTEXT,
    ];

    /**
     * form types for available types
     */
    const FORM_TYPES = [
        TextAttributeType::TYPE => TextType::class,
        self::LONGTEXT_TYPE => TextareaType::class,
        self::EMAIL_TYPE => EmailType::class,
        self::FILE_TYPE => FileType::class,
        CheckboxAttributeType::TYPE => CheckboxType::class,
        IntegerAttributeType::TYPE => IntegerType::class,
        self::FLOAT_TYPE => NumberType::class,
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