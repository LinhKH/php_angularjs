<?php

/**
 * CallSystemAuth basic login driver
 *
 * @package     Fuel
 * @subpackage  Auth
 */
class Auth_Login_CallSystemAuth extends \Auth_Login_Driver
{

    /**
     * Load the config and setup the remember-me session if needed
     */
    public static function _init()
    {

        \Config::load('callsystemauth', true);

        // setup the remember-me session object if needed
        if (\Config::get('callsystemauth.remember_me.enabled', false)) {
            static::$remember_me = \Session::forge([
                    'driver' => 'cookie',
                    'cookie' => [
                        'cookie_name' => \Config::get('callsystemauth.remember_me.cookie_name', 'rmcookie'),
                    ],
                    'encrypt_cookie' => true,
                    'expire_on_close' => false,
                    'expiration_time' => \Config::get('callsystemauth.remember_me.expiration', 86400 * 31),
            ]);
        }
    }

    /**
     * @var  Database_Result  when login succeeded
     */
    protected $user = null;

    /**
     * @var  array  value for guest login
     */
    protected static $guest_login = [
        'id' => 0,
        'user_id' => 'guest',
        'group' => '0',
        'login_hash' => false,
        'email' => false,
    ];

    /**
     * @var  array  SimpleAuth class config
     */
    protected $config = [
        'drivers' => ['group' => ['callsystemgroup']],
        'additional_fields' => ['profile_fields'],
    ];

    /**
     * Check for login
     *
     * @return  bool
     */
    protected function perform_check()
    {
        // fetch the user_id and login hash from the session
        $user_id = \Session::get('user_id');
        $login_hash = \Session::get('login_hash');

        // only worth checking if there's both a user_id and login-hash
        if (!empty($user_id) and ! empty($login_hash)) {
            if (is_null($this->user) or ( $this->user['user_id'] != $user_id)) {
                $this->user = \DB::select_array(\Config::get('callsystemauth.table_columns', ['*']))
                        ->where('user_id', '=', $user_id)
                        ->from(\Config::get('callsystemauth.table_name'))
                        ->execute(\Config::get('callsystemauth.db_connection'))->current();
                if (empty($this->user)) {
                    \Session::delete('user_id');
                    \Session::delete('login_hash');
                    return false;
                }
                $this->expendInformation();
            }
            // return true when login was verified, and either the hash matches or multiple logins are allowed
            if ($this->user and ( \Config::get('callsystemauth.multiple_logins', false) or $this->user['login_hash'] === $login_hash)) {
                return true;
            }
        }

        // not logged in, do we have remember-me active and a stored user_id?
        elseif (static::$remember_me and $user_id = static::$remember_me->get('user_id', null)) {
            return $this->force_login($user_id);
        }

        // no valid login when still here, ensure empty session and optionally set guest_login
        $this->user = \Config::get('callsystemauth.guest_login', true) ? static::$guest_login : false;
        \Session::delete('user_id');
        \Session::delete('login_hash');

        return false;
    }

    /**
     * Check the user exists
     *
     * @return  bool
     */
    public function validate_user($user_id = '', $password = '')
    {
        $user_id = trim($user_id) ? : trim(\Input::post(\Config::get('callsystemauth.username_post_key', 'user_id')));
        $password = trim($password) ? : trim(\Input::post(\Config::get('callsystemauth.password_post_key', 'password')));

        if (empty($user_id) or empty($password)) {
            return false;
        }

        $password = $this->hash_password($password);
        $user = \DB::select_array(\Config::get('callsystemauth.table_columns', ['*']))
                ->where('user_id', '=', $user_id)
                ->where('password', '=', $password)
                ->where('active', '=', 0)
                ->from(\Config::get('callsystemauth.table_name'))
                ->execute(\Config::get('callsystemauth.db_connection'))->current();

        return $user ? : false;
    }

    /**
     * Login user
     *
     * @param   string
     * @param   string
     * @return  bool
     */
    public function login($user_id = '', $password = '')
    {
        if (!($this->user = $this->validate_user($user_id, $password))) {
            \Session::delete('user_id');
            \Session::delete('login_hash');
            return false;
        }

        $this->expendInformation();

        // register so Auth::logout() can find us
        Auth::_register_verified($this);

        \Session::set('user_id', $this->user['user_id']);
        \Session::set('login_hash', $this->create_login_hash());
        \Session::set('user_manage_unit_cd', $this->user['manage_unit_cd']);
        \Session::instance()->rotate();

        return true;
    }

    private function expendInformation()
    {
        // get division
        $org = \DB::select('id', \DB::expr('parent_org_cd as use_div_cd'), 'main_sale_item_cd', 'apo_disp_dept_cd', 'unit_cd', 'default_sales_dept_cd')
                ->where('org_cd', '=', $this->user['aff_dept_cd'])
                ->where('org_type_cd', '=', 2)
                ->where('del_flg', '=', 0)
                ->from(\Model_MOrg::table())
                ->execute(\Config::get('callsystemauth.db_connection'))->current();
        $systemRole = \DB::select('*')
                ->where('system_role_cd', '=', $this->user['system_role_cd'])
                ->where('del_flg', '=', 0)
                ->from(\Model_MSysRole::table())
                ->execute(\Config::get('callsystemauth.db_connection'))->current();
        $menuPriv = \DB::select('*')
                ->where('id', '=', $this->user['menu_priv_id'])
                ->where('del_flg', '=', 0)
                ->from(\Model_MMenuPriv::table())
                ->execute(\Config::get('callsystemauth.db_connection'))->current();

        // expend information division_cd department_cd
        $this->user['main_sale_item_cd'] = $org['main_sale_item_cd'];
        $this->user['use_div_cd'] = $org['use_div_cd'];
        $this->user['division_cd'] = $org['use_div_cd'];
        $this->user['use_dept_cd'] = $this->user['aff_dept_cd'];
        $this->user['department_cd'] = $this->user['aff_dept_cd'];
        $this->user['apo_disp_dept_cd'] = $org['apo_disp_dept_cd'];
        $this->user['default_sales_dept_cd'] = $org['default_sales_dept_cd'];
        if (!empty($this->user['default_sales_dept_cd'])) {
            $sales_div_cd = Model_Base_Core::getOne('Model_MOrg', [
                    'from_cache' => true,
                    'select' => ['parent_org_cd'],
                    'where' => [
                        'org_cd' => $this->user['default_sales_dept_cd'],
                        'org_type_cd' => 2,
                        'del_flg' => 0,
                    ]
            ]);
        }
        $this->user['default_sales_div_cd'] = empty($sales_div_cd['parent_org_cd']) ? null : $sales_div_cd['parent_org_cd'];
        $this->user['unit_cd'] = $org['unit_cd'];
        $this->user['use_emp_id'] = $this->user['emp_id'];
        $this->user['sys_role'] = $systemRole;
        $this->user['role_lv_cd'] = $systemRole['role_lv_cd'];
        $this->user['menu_priv'] = $menuPriv;
    }

    /**
     * Force login user
     *
     * @param   string
     * @return  bool
     */
    public function force_login($user_id = '')
    {
        if (empty($user_id)) {
            return false;
        }

        $this->user = \DB::select_array(\Config::get('callsystemauth.table_columns', ['*']))
            ->where_open()
            ->where('id', '=', $user_id)
            ->where_close()
            ->from(\Config::get('callsystemauth.table_name'))
            ->execute(\Config::get('callsystemauth.db_connection'))
            ->current();

        if ($this->user == false) {
            // $this->user = \Config::get('callsystemauth.guest_login', true) ? static::$guest_login : false;
            \Session::delete('user_id');
            \Session::delete('login_hash');
            return false;
        }

        $this->expendInformation();

        \Session::set('user_id', $this->user['user_id']);
        \Session::set('login_hash', $this->create_login_hash());

        // and rotate the session id, we've elevated rights
        \Session::instance()->rotate();

        // register so Auth::logout() can find us
        Auth::_register_verified($this);

        return true;
    }

    /**
     * Logout user
     *
     * @return  bool
     */
    public function logout()
    {
        // $this->user = \Config::get('callsystemauth.guest_login', true) ? static::$guest_login : false;
        \Session::delete('user_id');
        \Session::delete('login_hash');
        \Session::delete('user_manage_unit_cd');
        return true;
    }

    /**
     * Create new user
     *
     * @param   string
     * @param   string
     * @param   string  must contain valid email address
     * @param   int     group id
     * @param   Array
     * @return  bool
     */
    public function create_user($user_id, $password, $system_role_cd = 1)
    {
        $password = trim($password);
        $email = filter_var(trim($email), FILTER_VALIDATE_EMAIL);

        if (empty($user_id) or empty($password) or empty($email)) {
            throw new \SimpleUserUpdateException('user_id or password is not given', 1);
        }

        $same_users = \DB::select_array(\Config::get('callsystemauth.table_columns', ['*']))
            ->where('user_id', '=', $user_id)
            ->from(\Config::get('callsystemauth.table_name'))
            ->execute(\Config::get('callsystemauth.db_connection'));

        if ($same_users->count() > 0) {
            throw new \SimpleUserUpdateException('user_id already exists', 3);
        }

        $user = [
            'user_id' => (string) $user_id,
            'password' => $this->hash_password((string) $password),
            'system_role_cd' => (int) $system_role_cd,
            'last_login' => 0,
            'login_hash' => '',
            'created_at' => \Date::forge()->get_timestamp(),
        ];
        $result = \DB::insert(\Config::get('callsystemauth.table_name'))
            ->set($user)
            ->execute(\Config::get('callsystemauth.db_connection'));

        return ($result[1] > 0) ? $result[0] : false;
    }

    /**
     * Update a user's properties
     * Note: user_id cannot be updated, to update password the old password must be passed as old_password
     *
     * @param   Array  properties to be updated including profile fields
     * @param   string
     * @return  bool
     */
    public function update_user($values, $user_id = null)
    {
        $user_id = $user_id ? : $this->user['user_id'];
        $current_values = \DB::select_array(\Config::get('callsystemauth.table_columns', ['*']))
            ->where('user_id', '=', $user_id)
            ->from(\Config::get('callsystemauth.table_name'))
            ->execute(\Config::get('callsystemauth.db_connection'));

        if (empty($current_values)) {
            throw new \SimpleUserUpdateException('user_id not found', 4);
        }

        $update = [];
        if (array_key_exists('user_id', $values)) {
            throw new \SimpleUserUpdateException('user_id cannot be changed.', 5);
        }
        if (array_key_exists('password', $values)) {
            if (empty($values['old_password'])
                or $current_values->get('password') != $this->hash_password(trim($values['old_password']))) {
                throw new \SimpleUserWrongPassword('Old password is invalid');
            }

            $password = trim(strval($values['password']));
            if ($password === '') {
                throw new \SimpleUserUpdateException('Password can\'t be empty.', 6);
            }
            $update['password'] = $this->hash_password($password);
            unset($values['password']);
        }
        if (array_key_exists('old_password', $values)) {
            unset($values['old_password']);
        }
        if (array_key_exists('system_role_cd', $values)) {
            if (is_numeric($values['system_role_cd'])) {
                $update['system_role_cd'] = (int) $values['system_role_cd'];
            }
            unset($values['system_role_cd']);
        }

        $update['updated_at'] = \Date::forge()->get_timestamp();

        $affected_rows = \DB::update(\Config::get('callsystemauth.table_name'))
            ->set($update)
            ->where('user_id', '=', $user_id)
            ->execute(\Config::get('callsystemauth.db_connection'));

        // Refresh user
        if ($this->user['user_id'] == $user_id) {
            $this->user = \DB::select_array(\Config::get('callsystemauth.table_columns', ['*']))
                    ->where('user_id', '=', $user_id)
                    ->from(\Config::get('callsystemauth.table_name'))
                    ->execute(\Config::get('callsystemauth.db_connection'))->current();
        }

        return $affected_rows > 0;
    }

    /**
     * Change a user's password
     *
     * @param   string
     * @param   string
     * @param   string  user_id or null for current user
     * @return  bool
     */
    public function change_password($old_password, $new_password, $user_id = null)
    {
        try {
            return (bool) $this->update_user(['old_password' => $old_password, 'password' => $new_password], $user_id);
        }
        // Only catch the wrong password exception
        catch (SimpleUserWrongPassword $e) {
            return false;
        }
    }

    /**
     * Generates new random password, sets it for the given user_id and returns the new password.
     * To be used for resetting a user's forgotten password, should be emailed afterwards.
     *
     * @param   string  $user_id
     * @return  string
     */
    public function reset_password($user_id)
    {
        $new_password = \Str::random('alnum', 8);
        $password_hash = $this->hash_password($new_password);

        $affected_rows = \DB::update(\Config::get('callsystemauth.table_name'))
            ->set(['password' => $password_hash])
            ->where('user_id', '=', $user_id)
            ->execute(\Config::get('callsystemauth.db_connection'));

        if (!$affected_rows) {
            throw new \SimpleUserUpdateException('Failed to reset password, user was invalid.', 8);
        }

        return $new_password;
    }

    /**
     * Deletes a given user
     *
     * @param   string
     * @return  bool
     */
    public function delete_user($user_id)
    {
        if (empty($user_id)) {
            throw new \SimpleUserUpdateException('Cannot delete user with empty user_id', 9);
        }

        $affected_rows = \DB::delete(\Config::get('callsystemauth.table_name'))
            ->where('user_id', '=', $user_id)
            ->execute(\Config::get('callsystemauth.db_connection'));

        return $affected_rows > 0;
    }

    /**
     * Creates a temporary hash that will validate the current login
     *
     * @return  string
     */
    public function create_login_hash()
    {
        if (empty($this->user)) {
            throw new \SimpleUserUpdateException('User not logged in, can\'t create login hash.', 10);
        }

        $last_login = \Date::forge()->get_timestamp();
        $login_hash = sha1(\Config::get('callsystemauth.login_hash_salt') . $this->user['user_id'] . $last_login);

        \DB::update(\Config::get('callsystemauth.table_name'))
            ->set(['last_login' => $last_login, 'login_hash' => $login_hash])
            ->where('user_id', '=', $this->user['user_id'])
            ->execute(\Config::get('callsystemauth.db_connection'));

        $this->user['login_hash'] = $login_hash;

        return $login_hash;
    }

    /**
     * Get the user's ID
     *
     * @return  Array  containing this driver's ID & the user's ID
     */
    public function get_user_id()
    {
        if (empty($this->user)) {
            return false;
        }

        return [$this->id, (int) $this->user['id']];
    }

    /**
     * Get the user's groups
     *
     * @return  Array  containing the group driver ID & the user's group ID
     */
    public function get_groups()
    {
        if (empty($this->user)) {
            return false;
        }

        return [['Simplegroup', $this->user['group']]];
    }

    /**
     * Getter for user data
     *
     * @param  string  name of the user field to return
     * @param  mixed  value to return if the field requested does not exist
     *
     * @return  mixed
     */
    public function get($field)
    {
        if ($field == '') {
            return $this->user;
        } else {
            return isset($this->user[$field]) ? $this->user[$field] : '';
        }
    }

    /**
     * Get the user's emp_id
     *
     * @return  string
     */
    public function get_emp_id()
    {
        return $this->get('emp_id', false);
    }

    /**
     * Get the user's emp_nm
     *
     * @return  string
     */
    public function get_emp_nm()
    {
        return $this->get('emp_nm', false);
    }

    /**
     * Get the user's email
     *
     * @return  string
     */
    public function get_email()
    {
        return $this->get('email', false);
    }

    /**
     * Get the user's screen_name
     *
     * @return  string
     */
    public function get_screen_name()
    {
        return false;
    }

    /**
     * Extension of base driver method to default to user group instead of user id
     */
    public function has_access($condition, $driver = null, $user = null)
    {
        if (is_null($user)) {
            $groups = $this->get_groups();
            $user = reset($groups);
        }
        return parent::has_access($condition, $driver, $user);
    }

    /**
     * Extension of base driver because this supports a guest login when switched on
     */
    public function guest_login()
    {
        return \Config::get('callsystemauth.guest_login', true);
    }

}
