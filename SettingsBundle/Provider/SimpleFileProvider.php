<?php
namespace Kaliber5\SettingsBundle\Provider;

use Kaliber5\SettingsBundle\Entity\SettingValue;

/**
 * Class SimpleFileProvider
 */
class SimpleFileProvider implements FileProviderInterface
{

    /**
     * @var string
     */
    private $settingsUploadPath;

    /**
     * SimpleFileProvider constructor.
     *
     * @param string $settingsUploadPath
     */
    public function __construct(string $settingsUploadPath)
    {
        $this->settingsUploadPath = $settingsUploadPath;
    }

    /**
     * @param SettingValue $settingValue
     */
    public function handleFileUpload(SettingValue $settingValue): void
    {
        $file = $settingValue->getFile();

        if (null === $file) {
            return;
        }

        $file->move($this->settingsUploadPath, $file->getClientOriginalName());
    }
}