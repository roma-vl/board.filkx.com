<?php

namespace App\Enum;

enum PermissionEnum: string
{
    case VIEW_OWN_ADVERTS = 'view.own.advert';
    case MANAGE_OWN_ADVERTS = 'manage.own.advert';
}
