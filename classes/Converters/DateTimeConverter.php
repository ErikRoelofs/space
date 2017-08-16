<?php

namespace Plu\Converters;


class DateTimeConverter implements ConverterInterface
{
    public function toJSON($data)
    {
        if (!$data) {
            return null;
        }
        /**
         * @var \DateTime $data ;
         */
        return $data->format('c');
    }

    public function fromJSON($obj, $data)
    {
        if(!$data){
            return null;
        }
        return \DateTime::createFromFormat('c', $data);
    }

    public function toDB($data)
    {
        if(!$data) {
            return null;
        }
        /**
         * @var \DateTime $data
         */
        return $data->format('Y-m-d H:i:s');
    }

    public function fromDB($obj, $data)
    {

        if(!$data) {
            return null;
        }
        return new \DateTime($data);
    }

}
