<?php

namespace Kaliber5\SettingsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Attribute\Model\AttributeValue;

/**
 * @ORM\Entity
 * @ORM\Table
 */
class SettingValue extends AttributeValue
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var Setting
     *
     * @ORM\ManyToOne(targetEntity="Setting", inversedBy="settingValues")
     */
    protected $attribute;

    /**
     * @var string
     *
     * @ORM\Column(name="val_text", nullable=true)
     */
    protected $text;

    /**
     * @var bool
     *
     * @ORM\Column(name="val_bool", type="boolean", nullable=true)
     */
    private $boolean;

    /**
     * @var int
     *
     * @ORM\Column(name="val_int", type="integer", nullable=true)
     */
    private $integer;

    /**
     * @var float
     *
     * @ORM\Column(name="val_float", type="float", nullable=true)
     */
    private $float;

    /**
     * @var \DateTimeInterface
     *
     * @ORM\Column(name="val_datetime", type="datetime", nullable=true)
     */
    private $datetime;

    /**
     * @var \DateTimeInterface
     *
     * @ORM\Column(name="val_date", type="date", nullable=true)
     */
    private $date;

    /**
     * @var array
     *
     * @ORM\Column(name="val_json", type="json_array", nullable=true)
     */
    private $json;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    protected function getBoolean(): ?bool
    {
        return $this->boolean;
    }

    protected function setBoolean(?bool $boolean): void
    {
        $this->boolean = $boolean;
    }

    protected function getText(): ?string
    {
        return $this->text;
    }

    protected function setText(?string $text): void
    {
        $this->text = $text;
    }

    protected function getInteger(): ?int
    {
        return $this->integer;
    }

    protected function setInteger(?int $integer): void
    {
        $this->integer = $integer;
    }

    protected function getFloat(): ?float
    {
        return $this->float;
    }

    protected function setFloat(?float $float): void
    {
        $this->float = $float;
    }

    protected function getDatetime(): ?\DateTimeInterface
    {
        return $this->datetime;
    }

    /**
     * @param \DateTimeInterface $datetime
     */
    protected function setDatetime(?\DateTimeInterface $datetime): void
    {
        $this->datetime = $datetime;
    }

    protected function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    protected function setDate(?\DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    protected function getJson(): ?array
    {
        return $this->json;
    }

    protected function setJson(?array $json): void
    {
        $this->json = $json;
    }
}
