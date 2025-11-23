<?php

namespace App\Http\Controllers;
use App\Models\Payments as PaymentsModel;;
use App\Http\Requests\PaymentRequest;
use App\Models\Enrollments;
use Illuminate\Support\Facades\DB;
use Exception;

class Payments extends Controller
{
    public function store(PaymentRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            // Create the payment record
            $payment = PaymentsModel::create([
                'enrollment_id' => $validated['enrollment_id'],
                'amount_paid' => $validated['amount_paid'],
                'payment_method_id' => $validated['payment_method_id'],
                'transaction_reference' => $validated['transaction_reference'] ?? null,
                'payment_date' => now(),
            ]);

            // Fetch the enrollment
            $enrollment = Enrollments::find($validated['enrollment_id']);
            if (!$enrollment) {
                throw new Exception('Enrollment not found.');
            }

            // Calculate total paid so far (sum of all payments for this enrollment)
            $totalPaidSoFar = DB::table('payments')
                ->where('enrollment_id', $validated['enrollment_id'])
                ->sum('amount_paid');

            // Fetch total_price from the enrollment
            $totalPrice = $enrollment->total_price;

            // Compare and update payment_status_id if fully paid
            if ($totalPaidSoFar >= $totalPrice) {
                $enrollment->update(['payment_status_id' => 2]); // 2 = PaidFull
            }

            DB::commit();

            return response()->json([
                'message' => 'Payment recorded successfully.',
                'data' => $payment,
                'total_paid' => $totalPaidSoFar,
                'total_price' => $totalPrice,
                'payment_status_id' => $enrollment->fresh()->payment_status_id,
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to record payment.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
