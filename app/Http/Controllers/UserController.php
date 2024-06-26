<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChildResource;
use App\Http\Resources\PostResource;
use App\Models\ChildProfile;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\ValidationService;

class UserController extends Controller
{
    private $userService;

    /*
    |----------------------------------------------------------------------------------
    | This Controller Contains all the Users Management:
    |  Add Employee -Add Child- Update User - Delete User - View User Information
    |-----------------------------------------------------------------------------------
    */

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Add a new Employee.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addEmployee(Request $request)
    {
        try {
            // Add employee
            $employee = $this->userService->addEmployee($request->all());
            return response()->json($employee);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Add a new Representative.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addRepresentative(Request $request)
    {
        try {
            // Add employee
            $representative = $this->userService->addRepresentative($request->all());
            return response()->json($representative);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Add a new Child.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addChild(Request $request)
    {
        try {
            $child = $this->userService->createChild($request->all());
            //            $childResource = new ChildResource($child);
            return response()->json($child);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Update an existing User.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUser(Request $request, $id)
    {
        try {
            // Update user
            $employee = $this->userService->updateUser($id, $request->all());
            return response()->json($employee);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function updateChild(Request $request, $id)
    {
        try {
            // Update user
            $employee = $this->userService->updateChild($id, $request->all());
            return response()->json($employee);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }


    /**
     * Delete a User.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteUser($id)
    {
        try {
            $this->userService->deleteUser($id);
            return response()->json(['message' => 'User deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Show User Information.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showUserInfo($id)
    {
        try {
            $userInfo = $this->userService->showUserInfo($id);
            return response()->json(['data' => $userInfo]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Show Child Information.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showChildInfo($id)
    {
        try {
            $userInfo = $this->userService->showChildInfo($id);
            return response()->json(['data' => $userInfo]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Retrieve all children information.
     */
    public function getAllChildren()
    {
        try {
            $children = $this->userService->getAllChildren();
            return response()->json($children);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Filter children based on the request parameters (filter_type).
     */

    public function filterChildren(Request $request)
    {
        try {
            $children = $this->userService->filterChildren($request->all());
            return response()->json($children);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function countDonors()
    {
        try {
            $donorCount = $this->userService->getDonorCount();
            return response()->json(['total_records = ' => $donorCount]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function countChilds()
    {
        try {
            $childCount = $this->userService->getChildCount();
            return response()->json(['total_records = ' => $childCount]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
