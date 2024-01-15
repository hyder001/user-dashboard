<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function dashboard(){

        $users = $this->getUserData();
        
        return view('user.dashboard',compact('users'));
    }

    public function getUserData(){
        $users = json_decode(file_get_contents(storage_path() . "/users.json"), true);
        $logs = json_decode(file_get_contents(storage_path() . "/logs.json"), true);

        foreach ($users as $key => $user) {
            
            $user_id = $user['id'];

            $userLogs = array_filter($logs, function ($var) use ($user_id) { return ($var['user_id'] == $user_id ); });
            $countValues = array_count_values( array_column($userLogs, 'type'));

            $users[$key]['impression'] = $countValues['impression'];
            $users[$key]['conversion'] = $countValues['conversion'];
            $users[$key]['revenue'] = number_format((float)array_sum(array_column($userLogs, 'revenue')), 2, '.', '');

            array_walk($userLogs, function (&$v){ unset($v['type']); unset($v['user_id']); });
            usort($userLogs, function($a, $b) { return $a['time'] <=> $b['time']; });
             
            $users[$key]['revenuelogs']=array_column($userLogs, 'revenue');
            $users[$key]['timelogs']=array_column($userLogs, 'time');

            $users[$key]['duration'] = date('n/d',strtotime($users[$key]['timelogs'][0])) ." - ". date('n/d',strtotime(end($users[$key]['timelogs'])));
        }

        return $users;

    }
}
