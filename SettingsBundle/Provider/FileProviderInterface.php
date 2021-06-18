<?php

namespace Kaliber5\SettingsBundle\Provider;

use Kaliber5\SettingsBundle\Entity\SettingValue;

/**
 * Interface FileUploadHandlerInteface
 */
interface FileProviderInterface
{
    /**
     * @param SettingValue $settingValue
     */
    public function handleFileUpload(SettingValue $settingValue): void;
}