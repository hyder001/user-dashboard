<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function dashboard(){

        $users = $this->getUserData();
        
        return view('user.dashboard',compact('users'));
    }

    public function getUserData()
    {
        $users = json_decode(file_get_contents(storage_path() . "/users.json"), true);
        $logs = json_decode(file_get_contents(storage_path() . "/logs.json"), true);

        return array_map(function ($user) use ($logs) {
            $userId = $user['id'];

            $userLogs = array_filter($logs, function ($var) use ($userId) {
                return $var['user_id'] == $userId;
            });

            $countValues = array_count_values(array_column($userLogs, 'type'));

            return array_merge($user, [
                'impression' => $countValues['impression'] ?? 0,
                'conversion' => $countValues['conversion'] ?? 0,
                'revenue' => number_format((float) array_sum(array_column($userLogs, 'revenue')), 2, '.', ''),
                'revenuelogs' => array_column($userLogs, 'revenue'),
                'timelogs' => array_column($userLogs, 'time'),
                'duration' => $this->calculateDuration($userLogs),
            ]);
        }, $users);
    }

    public function calculateDuration($userLogs)
    {
        array_walk($userLogs, function (&$v) {
            unset($v['type']);
            unset($v['user_id']);
        });

        usort($userLogs, function ($a, $b) {
            return $a['time'] <=> $b['time'];
        });

        return date('n/d', strtotime($userLogs[0]['time'])) . " - " . date('n/d', strtotime(end($userLogs)['time']));
    }

}
