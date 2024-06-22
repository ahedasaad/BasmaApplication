<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    /**
     * Creates a new user.
     */

    public function create(array $data)
    {
        try {
            return User::create($data);
        } catch (\Exception $e) {
            throw new \Exception('خطأ في إنشاء  المستخدم: ' . $e->getMessage());
        }
    }

    /**
     * Finds a user by ID.
     */
    public function findUserById($id)
    {
        return User::find($id);
    }

    /**
     * Updates an existing user.
     */
    public function update(User $user, array $data)
    {
        try {
            $user->update($data);
            return $user;
        } catch (\Exception $e) {
            throw new \Exception('خطأ في تحديث  المستخدم: ' . $e->getMessage());
        }
    }

    /**
     * Deletes a user by ID.
     */
    public function delete($id)
    {
        try{

            User::destroy($id);
            return true;
        } catch (\Exception $e) {
            throw new \Exception('خطأ في حذف  المستخدم: ' . $e->getMessage());
        }
    }

    /**
     * Returns user information as an array.
     */
    public function getUserInfo($user)
    {
        return $user->toArray();
    }

    public function countDonor()
    {
        $donorCount = User::where('account_type', 'donor')->count();
        return $donorCount;
    }

    public function countChild()
    {
        $childCount = User::where('account_type', 'child')->count();
        return $childCount;
    }

}
