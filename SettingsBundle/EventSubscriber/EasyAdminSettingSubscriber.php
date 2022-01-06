<?php

namespace Kaliber5\SettingsBundle\EventSubscriber;

use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Kaliber5\SettingsBundle\Entity\Setting;
use Kaliber5\SettingsBundle\Provider\SettingTypeProvider;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EasyAdminSettingSubscriber implements EventSubscriberInterface
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

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => 'beforeSettingPersisted',
        ];
    }

    public function beforeSettingPersisted(BeforeEntityPersistedEvent $event)
    {
        /** @var Setting $setting */
        $setting = $event->getEntityInstance();

        if (!($setting instanceof Setting)) {
            return;
        }

        if (null !== $setting->getType()) {
            $setting->setStorageType($this->settingTypeProvider->getStorageType($setting->getType()));
        }

        if ($setting->getSettingValues()->isEmpty()) {
            $setting->addSettingValue($setting->createSettingValue());
        }
        // @TODO remove if bug with multiple is fixed
        $setting->setMultiple(false);
    }
}