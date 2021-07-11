<?php

class PriceMapper extends Mapper
{
    public function getPrices() {
        $sql = "SELECT t.id, t.title, t.description, t.price
            from prices t ";
        $stmt = $this->db->query($sql);

        $results = [];
        while($row = $stmt->fetch()) {
            $results[] = new PriceEntity($row);
        }
        return $results;
    }

    /**
     * Get one price by its ID
     *
     * @param int $price_id The ID of the price
     * @return PriceEntity  The price
     */
    public function getPriceById($price_id) {
        $sql = "SELECT t.id, t.title, t.description, t.price
            from prices t
            where t.id = :price_id";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute(["price_id" => $price_id]);

        if($result) {
            return new PriceEntity($stmt->fetch());
        }

    }

    public function save(PriceEntity $price) {
        $sql = "insert into prices
            (title, description, price) values
            (:title, :description, :price)";

        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            "title" => $price->getTitle(),
            "description" => $price->getDescription(),
            "price" => $price->getPrice(),
        ]);

        if(!$result) {
            throw new Exception("could not save record");
        }
    }
}
