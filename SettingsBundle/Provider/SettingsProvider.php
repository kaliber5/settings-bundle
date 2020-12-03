<?php

namespace Kaliber5\SettingsBundle\Provider;

use Doctrine\Persistence\ManagerRegistry;
use Kaliber5\SettingsBundle\Entity\Setting;
use Kaliber5\SettingsBundle\Entity\SettingValue;
use Webmozart\Assert\Assert;

/**
 * Class SettingsProvider
 *
 * @package Kaliber5\SettingsBundle\Provider
 */
class SettingsProvider
{
    /**
     * @var ManagerRegistry
     */
    private $registry;

    /**
     * SettingsProvider constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @param string $code
     *
     * @return mixed|null
     */
    public function getSettingValuesByCode(string $code)
    {
        /** @var Setting $setting */
        $setting = $this->registry->getRepository(Setting::class)->findOneBy(['code' => $code]);

        Assert::notNull($setting, \sprintf('setting with code: %s does not exists', $code));

        if ($setting->isMultiple()) {
            return $setting->getSettingValues()->map(function(SettingValue $value) {
               return $value->getValue();
            })->toArray();
        }

        return $setting->getSettingValues()->first() ? $setting->getSettingValues()->first()->getValue() : null;
    }
}