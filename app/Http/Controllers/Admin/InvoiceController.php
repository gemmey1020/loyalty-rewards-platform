<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceEditHistory;
use App\Models\Point;
use App\Models\User;
use App\Models\UserPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InvoiceController extends Controller
{
    public function store(Request $request)
    {
        try {
            // ✅ 1. Validate incoming data
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'invoice_number' => 'nullable|string|max:255|unique:invoices,invoice_number',
                'invoice_date' => 'required|date',
                'invoice_total' => 'required|numeric|min:1',
            ]);

            DB::beginTransaction();

            // ✅ 2. Create the invoice and force created_at = invoice_date
            $invoice = Invoice::create([
                'user_id' => $validated['user_id'],
                'invoice_number' => $validated['invoice_number'],
                'invoice_date' => $validated['invoice_date'],
                'invoice_total' => $validated['invoice_total'],
            ]);

            // ✅ 3. Find the closest valid point rule before or on the invoice date
            $point = Point::whereDate('created_at', '<=', $invoice->invoice_date)
                ->orderBy('created_at', 'desc')
                ->first();

            if (! $point) {
                throw ValidationException::withMessages([
                    'points' => __('message.points_not_found'),
                ]);
            }

            // ✅ 4. Prepare values
            $recordAmount = $point->amount;
            $recordPoints = $point->points;
            $recordCashback = $point->cashback;

            // 🔵 Calculate earned points
            $pointPercentage = $recordPoints / $recordAmount;
            $earnedPoints = $pointPercentage * $invoice->invoice_total;

            // 🔵 Calculate earned cashback
            $cashbackPercentage = $recordCashback / $recordPoints;
            $earnedCashback = $cashbackPercentage * $earnedPoints;

            // ✅ 5. Create user points
            UserPoint::create([
                'user_id' => $validated['user_id'],
                'invoice_id' => $invoice->id,
                'point_id' => $point->id,
                'points' => $earnedPoints,
                'cashback' => $earnedCashback,
            ]);

            DB::commit();

            return redirect()->route('admin.dashboard')->with('success', __('message.invoice_points_created'));
        } catch (ValidationException $e) {
            DB::rollBack();

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('modal_user_id', $request->user_id);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withErrors(['error' => __('message.unexpected_error')])
                ->withInput();
        }
    }

    public function index()
    {

        $usersCount = User::where('role', UserRole::USER->value)->count();
        $adminsCount = User::where('role', UserRole::ADMIN->value)->count();
        $invoices = Invoice::with('user', 'userPoints')->get();
        $invoicesCount = Invoice::count();

        return view('admin.invoices.index', compact('invoices', 'usersCount', 'adminsCount', 'invoicesCount'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'invoice_number' => 'nullable|string|max:255',
            'invoice_total' => 'required|numeric|min:0',
            'invoice_date' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            $oldData = [
                'invoice_number' => $invoice->invoice_number,
                'invoice_total' => $invoice->invoice_total,
                'invoice_date' => $invoice->invoice_date,
            ];

            $changes = array_diff_assoc(
                $request->only(['invoice_number', 'invoice_total', 'invoice_date']),
                $oldData
            );

            if (! empty($changes)) {
                // Create history record
                InvoiceEditHistory::create([
                    'invoice_id' => $invoice->id,
                    'edited_by' => Auth::id(),
                    'invoice_number' => $oldData['invoice_number'],
                    'invoice_total' => $oldData['invoice_total'],
                    'invoice_date' => $oldData['invoice_date'],
                    'changes' => $changes,
                    'action' => 'updated',
                ]);

                // Update invoice
                $invoice->update($request->only(['invoice_number', 'invoice_total', 'invoice_date']));

                // Recalculate points if invoice_total or date changed
                if (isset($changes['invoice_total']) || isset($changes['invoice_date'])) {
                    $point = Point::where('created_at', '<=', $request->invoice_date)
                        ->orderBy('created_at', 'desc')
                        ->first();

                    if (! $point) {
                        throw ValidationException::withMessages([
                            'invoice_date' => 'No points configuration found for the selected invoice date.',
                        ]);
                    }

                    $earnedPoints = ($request->invoice_total / $point->amount) * $point->points;
                    $earnedCashback = ($request->invoice_total / $point->amount) * $point->cashback;

                    // Update user points for this invoice
                    $userPoint = $invoice->userPoints;
                    if ($userPoint) {
                        $userPoint->update([
                            'point_id' => $point->id,
                            'points' => $earnedPoints,
                            'cashback' => $earnedCashback,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->back()->with('success', __('message.invoice_updated'));
        } catch (ValidationException $e) {
            DB::rollBack();

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withErrors(['error' => __('message.unexpected_error')])
                ->withInput();
        }
    }

    public function destroyInvoice($id)
    {
        $invoice = Invoice::findOrFail($id);

        $invoice->userPoints()->delete();
        $invoice->delete();

        return redirect()->route('admins.invoices.index')->with('success', __('message.invoice_deleted'));
    }
}
