<?php

namespace Kaliber5\SettingsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Attribute\Model\AttributeValue;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
     * @var string
     *
     * @ORM\Column(name="val_longtext", type="text", nullable=true)
     */
    protected $longtext;

    /**
     * @var bool
     *
     * @ORM\Column(name="val_bool", type="boolean", nullable=true)
     */
    protected $boolean;

    /**
     * @var int
     *
     * @ORM\Column(name="val_int", type="integer", nullable=true)
     */
    protected $integer;

    /**
     * @var float
     *
     * @ORM\Column(name="val_float", type="float", nullable=true)
     */
    protected $float;

    /**
     * @var \DateTimeInterface
     *
     * @ORM\Column(name="val_datetime", type="datetime", nullable=true)
     */
    protected $datetime;

    /**
     * @var \DateTimeInterface
     *
     * @ORM\Column(name="val_date", type="date", nullable=true)
     */
    protected $date;

    /**
     * @var array
     *
     * @ORM\Column(name="val_json", type="json", nullable=true)
     */
    protected $json;

    /**
     * @var UploadedFile|null
     */
    private $file;

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

    /**
     * @return UploadedFile|null
     */
    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    /**
     * @param UploadedFile|null $file
     */
    public function setFile(?UploadedFile $file): void
    {
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function getLongtext(): ?string
    {
        return $this->longtext;
    }

    /**
     * @param string $longtext
     */
    public function setLongtext(string $longtext): void
    {
        $this->longtext = $longtext;
    }

    public function __toString(): string
    {
        return "".$this->getValue();
    }
}
