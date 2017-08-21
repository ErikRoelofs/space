<?php

namespace Plu\Converters\Restrictions;

interface RestrictionInterface
{

    public function restrictToJSON($data);

    public function restrictFromJSON($obj, $data);

    public function restrictToDB($data);

    public function restrictFromDB($obj, $data);

}
