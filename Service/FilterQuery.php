<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 2/09/17
 * Time: 20:39
 */

namespace AcMarche\LunchBundle\Service;

use AcMarche\SecurityBundle\Entity\User;


class FilterQuery
{

    public function getAllCommerces(User $user)
    {
        if ($user->hasRole('ROLE_LUNCH_ADMIN'))
            return [];

        if ($user->hasRole('ROLE_LUNCH_COMMERCE'))
            return ['user' => $user->getUsername()];

        if ($user->hasRole('ROLE_LUNCH_LOGISTICIEN'))
            return [];

        if ($user->hasRole('ROLE_LUNCH_CLIENT'))
            return [];

        return ['nom' => 'ddddd'];
    }

}