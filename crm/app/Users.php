<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Users extends Model
{
    public static function getUserDetails($empNo, $active)
    {
        $id = 0;
        $result = DB::connection('dbUserMgt')->select('EXEC sp_User_Get ?, ?, ?', [ $id, $empNo, $active ]);

        return $result;
    }

    public static function userRole($usrID)
    {
        $id = 0;
        $roleID = 0;
        $result = DB::connection('dbUserMgt')->select('EXEC sp_User_UserRole_Get ?, ?, ?', [ $id, $roleID, $usrID ]);

        return $result;
    }

    public static function userDetails($empNo)
    {
        $result = DB::connection('dbUserMgt')->select('EXEC sp_User_Get ?, ?, ?, ?', [ 0, $empNo, 1, 0 ]);

        return $result;
    }

    public static function logUserDetails($empID)
    {
        $result = DB::connection('dbSqlSrv')->select('EXEC [10.143.192.90].UserMgt.dbo.sp_User_get ?, ?, ?, ?', [ 0, '', 1, $empID ]);

        return $result;
    }
}
