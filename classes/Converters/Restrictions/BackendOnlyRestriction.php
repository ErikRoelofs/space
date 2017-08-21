<?php

namespace Plu\Converters\Restrictions;


class BackendOnlyRestriction implements RestrictionInterface
{
    public function restrictToJSON($data)
    {
        return true;
    }

    public function restrictFromJSON($obj, $data)
    {
        return true;
    }

    public function restrictToDB($data)
    {
        return false;
    }

    public function restrictFromDB($obj, $data)
    {
        return false;
    }

}
