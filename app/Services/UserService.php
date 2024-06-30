<?php

namespace App\Services;

use App\Http\Resources\ChildResource;
use App\Models\ChildProfile;
use App\Repositories\ChildRepository;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    private $userRepository;
    private $childRepository;
    private $validationService;

    /**
     * Create a new instance of the service.
     *
     * @param UserRepository  $userRepository
     * @param ChildRepository $childRepository
     * @param ValidationService $validationService
     */

    public function __construct(UserRepository $userRepository, ChildRepository $childRepository, ValidationService $validationService)
    {
        $this->userRepository = $userRepository;
        $this->childRepository = $childRepository;
        $this->validationService = $validationService;
    }

    /**
     * Add a new employee.
     */
    public function addEmployee(array $data)
    {
        $this->validationService->validateAddUser($data);

        $data['account_type'] = isset($data['account_type']) ? $data['account_type'] : 'employee';
        $data['password'] = Hash::make($data['password']);
        $user = $this->userRepository->create($data);
        //$user->assignRole('employee');

        return $user;
    }



    /**
     * Git All employee.
     */
    public function getAllEmployees()
    {
        return $this->userRepository->getAllEmployees();
    }

    /**
     * Git All Representative.
     */
    public function getAllRepresentative()
    {
        return $this->userRepository->getAllRepresentative();
    }


    /**
     * Add a new Representative.
     */
    public function addRepresentative(array $data)
    {
        $this->validationService->validateAddUser($data);

        $data['account_type'] = isset($data['account_type']) ? $data['account_type'] : 'representative';
        $data['password'] = Hash::make($data['password']);
        $user = $this->userRepository->create($data);
        //$user->assignRole('representative');
        return $user;
    }

    /**
     * Create a new child user.
     */
    //    public function createChild(array $data)
//    {
//        $this->validationService->validateAddChild($data);
//
//        if ($data->hasFile('image')) {
//            $image = $data->file('image');
//            $imageName = $image->getClientOriginalName();
//            $imagePath = $image->storeAs('posts', $imageName, 'public');
//
//            $data['image'] = $imagePath;
//        }
//
//        $data['account_type'] = isset($data['account_type']) ? $data['account_type'] : 'child';
//        $data['password'] = Hash::make($data['password']);
//
//        $user = $this->userRepository->create($data);
//        $childData = array_merge($data, ['user_id' => $user->id]);
//        $child = $this->childRepository->create($childData);
//        $user->child = $child;
//        return $user;
//    }

    public function createChild(array $data)
    {
        $this->validationService->validateAddChild($data);
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            $image = $data['image'];
            $originalName = $image->getClientOriginalName();

            // Clean the original name
            $cleanedName = $this->cleanFileName($originalName);

            // Create a unique name
            $imageName = time() . '_' . $cleanedName;
            $imagePath = $image->storeAs('children_image', $imageName, 'public');

            // Add the image path to the data array
            //$data['image'] = $imagePath;
            $data['image'] = 'app/public/' . $imagePath;
        }

        $data['account_type'] = $data['account_type'] ?? 'child';
        $data['password'] = Hash::make($data['password']);

        $user = $this->userRepository->create($data);
        //$user->assignRole('child');
        $childData = array_merge($data, ['user_id' => $user->id]);
        $child = $this->childRepository->create($childData);
        $user->child = $child;
        $childWithUser = ChildProfile::with('user')->find($child->id);
        return new ChildResource($childWithUser);
    }

    private function cleanFileName($fileName)
    {
        $fileName = preg_replace('/[^A-Za-z0-9\-\_\.]/', '_', $fileName);

        // Return the cleaned file name
        return $fileName;
    }


    /**
     * Update user information.
     */
    public function updateUser($userId, array $data)
    {
        $this->validationService->validateUpdateUser($data, $userId);

        $user = $this->userRepository->findUserById($userId);

        if (!$user) {
            throw new \Exception('المستخدم غير موجود');
        }

        $this->userRepository->update($user, $data);

        if ($user->account_type == 'child') {
            $child = $this->childRepository->findChildByUserId($userId);

            if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
                $image = $data['image'];
                $imagePath = $image->store('children_image', 'public');
                $data['image'] = $imagePath;
            }

            if (!$child) {
                throw new \Exception('الطفل غير موجود');
            }

            $this->childRepository->update($child, $data);

            return new ChildResource($child);
        }
        return $user;
    }


    /**
     * Update child information.
     */
    public function updateChild($childId, array $data)
    {
        $child = $this->childRepository->findChildById($childId);

        if (!$child) {
            throw new \Exception('الطفل غير موجود');
        }
        $userId = $child->user_id;
        $this->validationService->validateUpdateUser($data, $userId);
        $user = $this->userRepository->findUserById($userId);

        if (!$user) {
            throw new \Exception('المستخدم غير موجود');
        }

        $this->userRepository->update($user, $data);

        //    if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
//        $image = $data['image'];
//        $imagePath = $image->store('children_image', 'public');
//        $data['image'] = $imagePath;
//    }

        $this->childRepository->update($child, $data);
        return (new ChildResource($child))->toArrayWithoutImage($child);
        //    return new ChildResource($child);
// return response()->json((new ChildResource($child))->toArrayWithoutImage(request()));
    }



    /**
     * Delete a user and associated child if exists.
     */
    public function deleteUser($id)
    {
        $user = $this->userRepository->findUserById($id);

        if (!$user) {
            throw new \Exception('المستخدم غير موجود');
        }

        if ($user->account_type === 'child') {
            $child = $this->childRepository->findChildByUserId($id);

            if ($child) {
                $this->childRepository->delete($child->id);
            }
        }

        return $this->userRepository->delete($id);
    }

    /**
     * Delete a child and associated child if exists.
     */

    public function deleteChild($childId)
    {
        // ابحث عن الطفل باستخدام معرف الطفل
        $child = $this->childRepository->findChildById($childId);

        if (!$child) {
            throw new \Exception('الطفل غير موجود');
        }

        // ابحث عن المستخدم باستخدام معرف المستخدم المرتبط بالطفل
        $user = $this->userRepository->findUserById($child->user_id);

        if (!$user) {
            throw new \Exception('المستخدم غير موجود');
        }

        // حذف سجل الطفل
        $this->childRepository->delete($childId);

        // حذف سجل المستخدم
        $this->userRepository->delete($user->id);

        return true; // أو يمكنك إعادة رسالة نجاح أو نوع مناسب بحسب الحاجة
    }


    /**
     * Retrieve user information with child details if available.
     */
    public function showUserInfo($id)
    {
        $user = $this->userRepository->findUserById($id);

        if (!$user) {
            throw new \Exception('المستخدم غير موجود');
        }

        $userInfo = $this->userRepository->getUserInfo($user);

        if ($user->account_type == 'child') {
            $childInfo = $this->childRepository->findChildByUserId($user->id);
            $userInfo['child_info'] = $childInfo;
        }

        return $userInfo;
    }

    /**
     * Retrieve user information with child details if available.
     */
    public function showChildInfo($id)
    {
        $child = $this->childRepository->findChildById($id);

        if (!$child) {
            throw new \Exception('المستخدم غير موجود');
        }

        $childInfo = $this->childRepository->getChildInfo($child);

        return new ChildResource($child);
    }

    /**
     * Retrieve all children information.
     */
    public function getAllChildren()
    {
        try {
            return $this->childRepository->getAllChildren();
        } catch (\Exception $e) {
            throw new \Exception('خطأ في جلب جميع الأطفال: ' . $e->getMessage());
        }
    }

    /**
     * Filter children based on the request parameters(filter_type).
     */

    public function filterChildren(array $data)
    {
        $filterType = $data['filter_type'];
        $value = $data['value'];
        switch ($filterType) {
            case 'age':
                return $this->childRepository->filterChildrenByAge($value);
            case 'disease':
                return $this->childRepository->filterChildrenByDisease($value);
            default:
                throw new \InvalidArgumentException('Invalid filter type.');
        }
    }

    public function getDonorCount()
    {
        return $this->userRepository->countDonor();
    }

    public function getChildCount()
    {
        return $this->userRepository->countChild();
    }
}
