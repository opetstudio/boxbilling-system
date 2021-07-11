<?php

class PriceEntity
{
    protected $id;
    protected $title;
    protected $description;
    protected $price;

    /**
     * Accept an array of data matching properties of this class
     * and create the class
     *
     * @param array $data The data to use to create
     */
    public function __construct(array $data) {
        // no id if we're creating
        if(isset($data['id'])) {
            $this->id = $data['id'];
        }

        $this->title = $data['title'];
        $this->description = $data['description'];
        $this->price = $data['price'];
    }

    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getShortDescription() {
        return substr($this->description, 0, 20);
    }

    public function getPrice() {
        return $this->price;
    }
}
