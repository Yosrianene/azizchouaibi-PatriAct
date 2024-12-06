<?php

class CivilisationItem
{
    private $id;
    private $civilisation_id;
    private $type;
    private $name;
    private $description;
    private $image;
    private $location;

    public function __construct($id, $civilisation_id, $type, $name, $description, $image, $location)
    {
        $this->id = $id;
        $this->civilisation_id = $civilisation_id;
        $this->type = $type;
        $this->name = $name;
        $this->description = $description;
        $this->image = $image;
        $this->location = $location;
    }

    // Getters and Setters for each property

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getCivilisationId()
    {
        return $this->civilisation_id;
    }

    public function setCivilisationId($civilisation_id)
    {
        $this->civilisation_id = $civilisation_id;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation($location)
    {
        $this->location = $location;
        return $this;
    }
}
?>
