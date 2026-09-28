<?php

namespace App\Helpers;

class AuthHelper
{
    public static function id()
    {
        return $_SESSION['user_id'] ?? null;
    }

    public static function user()
    {
        return $_SESSION['user'] ?? null;
    }

    public static function activeProfileId()
    {
        if (isset($_SESSION['active_profile_id'])) {
            return $_SESSION['active_profile_id'];
        }
        
        // Fallback: get the first profile of the user
        $profileModel = new \App\Models\CandidateProfile();
        $profiles = $profileModel->where('user_id', self::id());
        if (count($profiles) > 0) {
            $_SESSION['active_profile_id'] = $profiles[0]->id;
            return $profiles[0]->id;
        } else {
            // Create a default profile
            $user = self::user();
            if ($user) {
                $id = $profileModel->create([
                    'user_id' => $user->id,
                    'full_name' => $user->name,
                    'email' => $user->email
                ]);
                $_SESSION['active_profile_id'] = $id;
                return $id;
            }
        }
        return null;
    }

    public static function check()
    {
        return isset($_SESSION['user_id']);
    }

    public static function login($user)
    {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user'] = $user;
    }

    public static function logout()
    {
        session_unset();
        session_destroy();
    }
}
