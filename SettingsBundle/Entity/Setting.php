<?php

namespace Kaliber5\SettingsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Sylius\Component\Attribute\AttributeType\TextAttributeType;
use Sylius\Component\Attribute\Model\Attribute;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Setting
 *
 * @ORM\Entity
 * @ORM\Table
 */
class Setting extends Attribute
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string|null
     *
     * @ORM\Column(unique=true)
     */
    protected $code;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $multiple = true;

    /**
     * @var string|null
     *
     * @ORM\Column()
     */
    protected $name;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @var string|null
     *
     * @ORM\Column()
     */
    protected $type = TextAttributeType::TYPE;

    /**
     * @var string|null
     *
     * @ORM\Column()
     */
    protected $storageType;

    /**
     * @var SettingValue[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="SettingValue", mappedBy="attribute", orphanRemoval=true, cascade={"persist"})
     */
    protected $settingValues;

    /**
     * Setting constructor.
     */
    public function __construct()
    {
        $this->settingValues = new ArrayCollection();
        parent::__construct();
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string|null $code
     */
    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return bool
     */
    public function isMultiple(): bool
    {
        return $this->multiple;
    }

    /**
     * @param bool $multiple
     */
    public function setMultiple(bool $multiple): void
    {
        $this->multiple = $multiple;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string|null
     */
    public function getStorageType(): ?string
    {
        return $this->storageType;
    }

    /**
     * @param string|null $storageType
     */
    public function setStorageType(?string $storageType): void
    {
        $this->storageType = $storageType;
    }

    /**
     * @return SettingValue[]
     */
    public function getSettingValues()
    {
        return $this->settingValues;
    }

    /**
     * @param SettingValue[] $settingValues
     */
    public function setSettingValues($settingValues): void
    {
        $this->settingValues = $settingValues;
    }

    public function addSettingValue(SettingValue $settingValue)
    {
        if (!$this->settingValues->contains($settingValue)) {
            $this->settingValues[] = $settingValue;
            $settingValue->setAttribute($this);
        }
    }

    public function removeSettingValue(SettingValue $settingValue)
    {
        if ($this->settingValues->contains($settingValue)) {
            $this->settingValues->removeElement( $settingValue);
            $settingValue->setAttribute(null);
        }
    }

    /**
     * @return array for sonata admin list view
     */
    public function getValues(): array
    {
        return $this->getSettingValues()->map(function(SettingValue $value) {
            return $value->getValue();
        })->toArray();
    }

    /**
     * @return SettingValue
     */
    public function createSettingValue(): SettingValue
    {
        return new SettingValue();
    }

    public function __toString(): string
    {
        return (string) $this->getName();
    }
}