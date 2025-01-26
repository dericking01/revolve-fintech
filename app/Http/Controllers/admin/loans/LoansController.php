<?php

namespace App\Http\Controllers\admin\loans;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Borrower;
use App\Models\Staff;
use Illuminate\Http\Request;
use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Brian2694\Toastr\Facades\Toastr;


class LoansController extends Controller
{
    public function LoanForm()
    {
        $borrowers = Borrower::where('status', 'active')->latest()->get();
        $staffs = Staff::where('status', 'active')->latest()->get();
        $admins = Admin::latest()->get();

        // Add a `type` attribute to distinguish between staffs and admins
        $staffs->map(function ($staff) {
            $staff->type = 'staff';
            return $staff;
        });

        $admins->map(function ($admin) {
            $admin->type = 'admin';
            return $admin;
        });

        // Merge staffs and admins into a single collection
        // $users = $staffs->merge($admins);
        $users = $admins->concat($staffs);

        return view('admin.loans.create-loan', compact('borrowers', 'users'));
    }


    public function storeLoan(Request $request)
    {
        // dd($request);
        // Validate the incoming request
        $validated = $request->validate([
            'borrower_id' => 'required|exists:borrowers,id',
            'created_by_id' => 'required|integer',
            'application_fee' => 'required|numeric|min:0',
            'loan_amount' => 'required|numeric|min:0',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'term' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'amount_to_be_paid' => 'required|numeric|min:0',
        ]);

        // Check if the selected user exists in Admins or Staff
        $creator = Admin::find($request->created_by_id) ?? Staff::find($request->created_by_id);

        if (!$creator) {
            return back()->withErrors(['created_by_id' => 'Invalid creator selected.']);
        }

        // Determine the fully qualified model name dynamically
        $createdByType = get_class($creator);

        // Calculate the due_date based on term
        $startDate = Carbon::parse($validated['start_date']); // Parse the start_date
        $dueDate = (clone $startDate)->addMonths($validated['term']); // Clone before modifying

        // Create the loan
        $loan = new Loan();
        $loan->borrower_id = $validated['borrower_id'];
        $loan->created_by_id = $validated['created_by_id'];
        $loan->created_by_type = $createdByType; // Fully qualified class name
        $loan->application_fee = $validated['application_fee'];
        $loan->loan_amount = $validated['loan_amount'];
        $loan->interest_rate = $validated['interest_rate'];
        $loan->term = $validated['term'];
        $loan->start_date = $startDate->toDateTimeString(); // Save as a formatted string
        $loan->due_date = $dueDate->toDateTimeString(); // Save as a formatted string
        $loan->amount_to_be_paid = $validated['amount_to_be_paid'];
        // dd($loan);
        $loan->save();

        Toastr::success('Loan created successfully!');
        return back();
    }

    public function updateLoan(Request $request, $id)
    {
        $loan = Loan::find($id);
        // dd($request);
        // Validate the incoming request
        $validated = $request->validate([
            'loan_amount' => 'required|numeric|min:0',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'term' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'amount_to_be_paid' => 'required|numeric|min:0',
        ]);

        // Calculate the due_date based on term
        $startDate = Carbon::parse($validated['start_date']); // Parse the start_date
        $dueDate = (clone $startDate)->addMonths($validated['term']); // Clone before modifying

        // Create the loan
        $loan->loan_amount = $validated['loan_amount'];
        $loan->interest_rate = $validated['interest_rate'];
        $loan->term = $validated['term'];
        $loan->start_date = $startDate->toDateTimeString(); // Save as a formatted string
        $loan->due_date = $dueDate->toDateTimeString(); // Save as a formatted string
        $loan->amount_to_be_paid = $validated['amount_to_be_paid'];
        // dd($loan);
        $loan->save();

        Toastr::success('Loan created successfully!');
        return back();
    }

    public function deleteLoan($id)
    {
        $loan = Loan::findOrFail($id); // Find loan or fail if not found

        if ($loan->status == 'pending') {
            $loan->delete(); // Delete the loan if its status is 'pending'
            Toastr::success('Loan deleted successfully!');
        } else {
            Toastr::error('Cannot delete this loan because it is not in "pending" status.');
        }

        return back();
    }

    public function pendingLoansindex()
    {
        $loans = Loan::where('status', 'pending')->latest()->get();
        return view('admin.loans.pending-loans', compact('loans'));
    }


}
