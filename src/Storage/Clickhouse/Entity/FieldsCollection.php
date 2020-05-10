<?php

declare(strict_types=1);

namespace Noobus\GrootLib\Storage\Clickhouse\Entity;

use Noobus\GrootLib\Storage\Clickhouse\Entity\Field\FieldInterface;

class FieldsCollection
{
    private $fields = [];

    public function add(FieldInterface $field) {
        if ($field->isValid()) {
            $this->fields[$field::name()] = $field->value();
        } else {
            throw new \InvalidArgumentException('Invalid field '.$field::name().' with value '.$field->value());
        }
    }
    
}
