<?php
namespace App\Repositories;

use App\Models\ChildProfile;
use App\Models\User;
use App\Http\Resources\ChildResource;
use Illuminate\Support\Facades\DB;


class ChildRepository
{
    /**
     * Finds a child by user ID.
     */
    public function findChildByUserId($userId)
    {
        try {
            return ChildProfile::where('user_id', $userId)->first();
        } catch (\Exception $e) {
            throw new \Exception('خطأ في العثور على الطفل: ' . $e->getMessage());
        }
    }

    /**
     * Finds a child by user ID.
     */
    public function findChildById($Id)
    {
        try {
            return ChildProfile::where('id', $Id)->first();
        } catch (\Exception $e) {
            throw new \Exception('خطأ في العثور على الطفل: ' . $e->getMessage());
        }
    }


    /**
     * Creates a new child.
     */
    public function create(array $data)
    {
        try {
            return ChildProfile::create($data);
        } catch (\Exception $e) {
            throw new \Exception('خطأ في إنشاء الطفل: ' . $e->getMessage());
        }
    }

    /**
     * Updates an existing child.
     */
    public function update(ChildProfile $user, array $data)
    {
        try {

            $user->update($data);
            return $user;
        } catch (\Exception $e) {
            throw new \Exception('خطأ في تحديث  الطفل: ' . $e->getMessage());
        }
    }

    /**
     * Deletes a child by ID.
     */
    public function delete($childId)
    {
        try {

            ChildProfile::destroy($childId);
            return true;
        } catch (\Exception $e) {
            throw new \Exception('خطأ في حذف الطفل: ' . $e->getMessage());
        }
    }

    /**
     * Retrieve all children information.
     */

    public function getAllChildren()
    {
        try {
            $children = ChildProfile::with('user')->get();
            return ChildResource::collection($children);
        } catch (\Exception $e) {
            throw new \Exception('خطأ في جلب جميع الأطفال : ' . $e->getMessage());
        }
    }

    /**
     * Filter children based on the age.
     */
    public function filterChildrenByAge($age)
    {
        $today = now();
        $birthdate = $today->subYears($age)->startOfYear();
        $endDate = $birthdate->copy()->addYear();
        $children = ChildProfile::whereBetween('birthdate', [$birthdate, $endDate])->get();

        return ChildResource::collection($children);
    }

    /**
     * Filter children based on the disease type.
     */
    public function filterChildrenByDisease($disease)
    {
        return ChildProfile::with('user')
            ->where('disease_type', 'like', '%' . $disease . '%')
            ->get();
    }


}

