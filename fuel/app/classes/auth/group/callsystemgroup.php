<?php

class Auth_Group_CallSystemGroup extends \Auth_Group_Driver
{

    protected static $_valid_groups = [];

    public static function _init()
    {
        static::$_valid_groups = array_keys(\Config::get('callsystemauth.groups', []));
    }

    protected $config = [
        'drivers' => ['acl' => ['callsystemacl']],
    ];

    public function groups()
    {
        return static::$_valid_groups;
    }

    public function member($group, $user = null)
    {
        if ($user === null) {
            $groups = \Auth::instance()->get_groups();
        } else {
            $groups = \Auth::instance($user[0])->get_groups();
        }

        if (!$groups || !in_array((int) $group, static::$_valid_groups)) {
            return false;
        }

        return in_array([$this->id, $group], $groups);
    }

    public function get_name($group = null)
    {
        if ($group === null) {
            if (!$login = \Auth::instance() or ! is_array($groups = $login->get_groups())) {
                return false;
            }
            $group = isset($groups[0][1]) ? $groups[0][1] : null;
        }

        return \Config::get('callsystemauth.groups.' . $group . '.name', null);
    }

    public function get_roles($group = null)
    {
        // When group is empty, attempt to get groups from a current login
        if ($group === null) {
            if (!$login = \Auth::instance()
                or ! is_array($groups = $login->get_groups())
                or ! isset($groups[0][1])) {
                return [];
            }
            $group = $groups[0][1];
        } elseif (!in_array((int) $group, static::$_valid_groups)) {
            return [];
        }

        $groups = \Config::get('callsystemauth.groups');
        return $groups[(int) $group]['roles'];
    }

}
