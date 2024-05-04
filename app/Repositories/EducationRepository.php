<?php
namespace App\Repositories;

use App\Http\Resources\SubjectForClassroom;
use App\Http\Resources\TitleResource;
use App\Models\Classroom;
use App\Models\Explanation;
use App\Models\OrderExplanation;
use App\Models\Subject;
use App\Models\SubjectClass;
use App\Models\Title;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;


class EducationRepository
{
    /**
     * Retrieve all classroom records.
     */
    public function getAllClassroom()
    {
        return Classroom::all();
    }

    /**
     * Retrieve subjects for a specific classroom.
     */
    public function getSubjectsForClassroom($classroomId)
    {
        $subjectClasses = SubjectClass::where('classroom_id', $classroomId)
            ->with('subject:id,name')
            ->get();

        return SubjectForClassroom::collection($subjectClasses);
    }

    /**
     * Retrieve titles for a specific subject class.
     */
    public function getTitlesForSubjectClass($subjectClassId)
    {
        $subjectClass = SubjectClass::findOrFail($subjectClassId);
        $titles = $subjectClass->titles;
        return TitleResource::collection($titles);

    }

    /**
     * Order explanations based on the provided data.
     */
    public function orderExplanations(array $data)
    {
        if (!isset($data['state'])) {
            $data['state'] = 'pending';
        }
        $order = OrderExplanation::create($data);

        return $order;
    }

    /**
     * Find an order explanation by its ID.
     * */
    public function findOrderExplanationById($id)
    {
        return OrderExplanation::find($id);
    }

    /**
     * Update approvals count for a given order explanation.
     */
    public function updateApprovalsCount($orderExplanation)
    {
        if ($orderExplanation->state == 'pending') {
            $orderExplanation->state = 'approved';
        }
        $orderExplanation->approvals++;
        return $orderExplanation->save();
    }


    /**
     * Create a new explanation.
     */
    public function createExplanation($orderId,$request)
    {
        if (!$request) {
            throw new \Exception('لم يتم العثور على الطلب.');
        }

        // **إنشاء شرح جديد**
        $explanation = new Explanation;
        $explanation->order_explanation_id = $orderId;
        $explanation->user_id = Auth::id();
        $explanation->title_id = $request->title_id;
        $explanation->title = 'شرح بدون عنوان';
        $explanation->save();

        return $explanation;
    }

    /**
     * Get all order explanations.
     */
    public function getAllOrderExplanations()
    {
        return OrderExplanation::where('state', 'pending')->orderBy('created_at', 'asc')
            ->union(
                OrderExplanation::where('state', 'approved')->where('approvals', 1)->orderBy('created_at', 'asc')
            )->union(
                OrderExplanation::where('state', 'approved')->where('approvals', 2)->orderBy('created_at', 'asc')
            )->union(
                OrderExplanation::where('state', 'approved')->where('approvals', '<', 3)->orderBy('created_at', 'asc')
            )->get();
    }

    /**
     * Get child order explanations for the specified child ID.
     */
    public function getChildOrderExplanations($loggedInChildId)
    {
        return OrderExplanation::where('user_id', $loggedInChildId)->orderByRaw("state = 'approved' DESC")->get();
    }

    /**
     * Get the details of a specific order explanation by its ID.
     */
    public function OrderExplanationDetails($id)
    {
        return OrderExplanation::findOrFail($id);
    }

    /**
     * Get pending explanations for a specific user.
     */
    public function getUserPendingExplanations($userId)
    {
        return Explanation::where('user_id', $userId)
            ->where('state', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Get all pending explanations.
     */
    public function getAllPendingExplanations()
    {
        return Explanation::where('state', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Get uploaded explanations for a specific user.
     */
    public function getUserUploadedExplanations($userId)
    {
        return Explanation::where('user_id', $userId)
            ->where('state', 'uploaded')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Get all uploaded explanations.
     */
    public function getAllUploadedExplanations()
    {
        return Explanation::where('state', 'uploaded')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Get rejected explanations for a specific user.
     */
    public function getUserRejectedExplanations($userId)
    {
        return Explanation::where('user_id', $userId)
            ->where('state', 'rejected')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Get all rejected explanations.
     */
    public function getAllRejectedExplanations()
    {
        return Explanation::where('state', 'rejected')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Get approved explanations for a specific user.
     */

    public function getUserApprovedExplanations($userId)
    {
        return Explanation::where('user_id', $userId)
            ->where('state', 'approved')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Get all approved explanations.
     */
    public function getAllApprovedExplanations()
    {
        return Explanation::where('state', 'approved')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Get explanations by title ID that are in the approved state.
     */
    public function getExplanationsByTitle($titleId)
    {
        return Explanation::where('title_id', $titleId)
            ->where('state', 'approved')
            ->get();
    }

    /**
     * Approve an explanation by changing its status to 'approved'.
     */
    public function approveExplanation($explanation)
    {
        $explanation = Explanation::findOrFail($explanation);
        $explanation->status = 'approved';
        $explanation->save();
        return $explanation;
    }

    /**
     * Reject an explanation by changing its status to 'rejected'.
     */
    public function rejectedExplanation($explanation)
    {
        $explanation = Explanation::findOrFail($explanation);
        $explanation->status = 'rejected';
        $explanation->save();
        return $explanation;
    }


}
