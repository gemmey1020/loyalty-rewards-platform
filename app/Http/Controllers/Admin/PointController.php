<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Point;
use App\Models\PointDeductionHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PointController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'points' => 'required|integer|min:1',
            'amount' => 'required|numeric|min:0.01', // minimum must be positive
            'cashback' => 'required|numeric|min:0.01',
        ]);

        Point::create($request->only('points', 'amount', 'cashback'));

        return redirect()->back()->with('success', __('message.points_added'));
    }

    public function discount(Request $request, $id)
    {
        // Validate input
        $request->validate([
            'points' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255',
        ]);

        $pointsToDeduct = $request->points;
        $user = User::findOrFail($id);

        // Calculate total available points (net points)
        $totalPoints = $user->userPoints()->sum(DB::raw('points - points_deducted'));
        if ($pointsToDeduct > $totalPoints) {
            return redirect()->back()->withErrors(['error' => __('message.points_insufficient')]);
        }

        // Get user points ordered by creation date (oldest first)
        $userPoints = $user->userPoints()->whereRaw('points > points_deducted')->orderBy('created_at')->get();
        $remainingPointsToDeduct = $pointsToDeduct;

        DB::beginTransaction();

        try {
            foreach ($userPoints as $userPoint) {
                if ($remainingPointsToDeduct <= 0) {
                    break;
                }

                // Calculate available points in this record
                $availablePoints = $userPoint->points - $userPoint->points_deducted;
                if ($availablePoints <= 0) {
                    continue;
                }

                // Calculate points to deduct from this record
                $pointsToDeductFromRecord = min($availablePoints, $remainingPointsToDeduct);

                // Update points_deducted
                $userPoint->points_deducted += $pointsToDeductFromRecord;
                $userPoint->save();

                // Log deduction in history
                PointDeductionHistory::create([
                    'user_id' => $user->id,
                    'invoice_id' => $userPoint->invoice_id,
                    'user_point_id' => $userPoint->id,
                    'points_deducted' => $pointsToDeductFromRecord,
                    'reason' => $request->reason ?? 'Admin deduction',
                    'deducted_by' => Auth::id(),
                ]);

                $remainingPointsToDeduct -= $pointsToDeductFromRecord;
            }

            DB::commit();

            return redirect()->back()->with('success', __('message.points_deducted'));
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors(['error' => __('message.points_error')]);
        }
    }
}
