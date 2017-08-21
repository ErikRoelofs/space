<?php

namespace Plu\Converters;


use Plu\Converters\Exception\RestrictedException;
use Plu\Converters\Restrictions\RestrictionInterface;

class RestrictedConverter implements ConverterInterface
{

    /**
     * @var ConverterInterface
     */
    protected $component;

    /**
     * @var RestrictionInterface
     */
    protected $restriction;

    /**
     * RestrictedConverter constructor.
     * @param ConverterInterface $component
     * @param RestrictionInterface $restriction
     */
    public function __construct(ConverterInterface $component, RestrictionInterface $restriction)
    {
        $this->component = $component;
        $this->restriction = $restriction;
    }


    public function toJSON($data)
    {
        if($this->restriction->restrictToJSON($data)) {
            throw new RestrictedException("This conversion is restricted! You have no permission to use it.");
        }
        return $this->component->toJSON($data);
    }

    public function fromJSON($obj, $data)
    {
        if($this->restriction->restrictFromJSON($obj, $data)) {
            throw new RestrictedException("This conversion is restricted! You have no permission to use it.");
        }
        return $this->component->fromJSON($obj, $data);
    }

    public function toDB($data)
    {
        if($this->restriction->restrictToDB($data)) {
            throw new RestrictedException("This conversion is restricted! You have no permission to use it.");
        }
        return $this->component->toDB($data);
    }

    public function fromDB($obj, $data)
    {
        if($this->restriction->restrictFromDB($obj, $data)) {
            throw new RestrictedException("This conversion is restricted! You have no permission to use it.");
        }
        return $this->component->fromDB($obj, $data);
    }

}
