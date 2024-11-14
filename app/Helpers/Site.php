<?php

use Illuminate\Support\Carbon;

if (! function_exists('initial_letter')) {
    /**
     * Return initial letter form $name
     *
     * @return string $initial_letter
     */
    function initial_letter($name)
    {
        return substr($name, 0, 1);
    }
}

if (! function_exists('convert_date')) {
    function convert_date($date, $format = 'D MMMM Y')
    {
        $carbonDate = Carbon::parse($date);
        $formattedDate = $carbonDate->isoFormat($format);

        return $formattedDate;
    }
}

if (! function_exists('get_list_permission')) {
    function get_list_permission($permission, $type = null)
    {
        $keywordsToExclude = ['read', 'update', 'delete', 'create'];

        $parts = explode(' ', $permission);
        $filteredParts = array_diff($parts, $keywordsToExclude);

        return implode($type ? ' ' : '_', $filteredParts);
    }
}

if (! function_exists('get_permission_filter')) {
    function get_permission_filter($permissions)
    {
        $permissions = $permissions->pluck('name');
        $translatedPermissions = [];

        $permissionType = [];
        for ($key = 0; $key < count($permissions); $key++) {
            $permissionArray = explode(' ', $permissions[$key]);
            $permission_name = $permissionArray[1];

            if (count($permissionArray) > 2) {
                $permission_name = $permissionArray[1].' '.$permissionArray[2];
            }

            if ($key < count($permissions) - 1) {
                $permissionNext = explode(' ', $permissions[$key + 1]);

                if (in_array('read', $permissionArray) && (in_array('create', $permissionNext) || in_array('update', $permissionNext) || in_array('delete', $permissionNext))) {
                    $type = '';
                    array_push($permissionType, $permission_name);

                    if (in_array('create', $permissionNext)) {
                        $type .= 'and Edit';
                    }
                    if (in_array('update', $permissionNext)) {
                        $type .= 'and Edit';
                    }
                    if (in_array('delete', $permissionNext)) {
                        $type .= 'and Edit';
                    }

                    $translatedPermissions[] = 'View '.$type.' '.ucfirst($permission_name);
                } elseif (! in_array($permission_name, $permissionType) && (in_array('create', $permissionArray) || in_array('update', $permissionArray) || in_array('delete', $permissionArray))) {
                    $translatedPermissions[] = 'Edit '.ucfirst($permission_name).' only';
                } elseif (! in_array($permission_name, $permissionType) && in_array('read', $permissionArray)) {
                    $translatedPermissions[] = 'View '.ucfirst($permission_name).' only';
                }
            } else {
                $permissionPrevious = $key > 1 ? explode(' ', $permissions[$key - 1]) : $permissionArray;

                if ($permission_name != $permissionPrevious[1] || $key < 3) {
                    $type = '';

                    if (in_array('read', $permissionArray)) {
                        $type = 'View';
                    }
                    if (in_array('create', $permissionArray) || in_array('update', $permissionArray) || in_array('delete', $permissionArray)) {
                        $type = 'Edit';
                    }

                    $translatedPermissions[] = $type.' '.ucfirst(end($permissionArray)).' only';
                }
            }
        }

        return $translatedPermissions;
    }
}
