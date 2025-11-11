<?php

namespace App\Enum;

enum PermissionEnum: string
{
    /**
     * Cabinet
     */
    case VIEW_OWN_ADVERTS = 'view.own.advert';
    case MANAGE_OWN_ADVERTS = 'manage.own.advert';

    case VIEW_OWN_FAVORITE = 'view.own.favorite';
    case MANAGE_OWN_FAVORITE = 'manage.own.favorite';

    /**
     * Admin
     */
}
