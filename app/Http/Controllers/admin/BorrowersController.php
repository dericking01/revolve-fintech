<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Borrower;
use App\Models\Branch;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Hash;

class BorrowersController extends Controller
{
    public function borrowers()
    {
        $borrowers = Borrower::latest()->get();
        $branches = Branch::where('status', 'active')->latest()->get();
        // dd($branches);
        return view('admin.users.listborrowers', compact('borrowers', 'branches'));
    }

    public function storeBorrower(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|regex:/^[a-zA-Z\s]+$/',
            'phone' => [
                'required',
                'numeric',
                'regex:/^0\d{9}$/',
                'digits:10',
                Rule::unique('borrowers', 'phone')->where(function ($query) use ($request) {
                    return $query->where('phone', $request->phone);
                }),
            ],
            'email' => 'nullable|email|unique:borrowers,email',
            'gender' => 'nullable|string',
            'location' => 'nullable|string',
            'status' => 'nullable|string',
            'national_id' => 'required|unique:borrowers,national_id', // Add this line
        ]);

        // Check if national ID already exists
        if (Borrower::where('national_id', $request->national_id)->exists()) {
            Toastr::error('National ID exists already!');
            return redirect()->back()->withInput();
        }

        // Generate a unique borrower ID
        $borrowerId = 'REV-' . date('Y') . '-' . mt_rand(1000, 9999);

        // Ensure the generated borrower ID is unique
        while (Borrower::where('id', $borrowerId)->exists()) {
            $borrowerId = 'DOD-' . date('Y') . '-' . mt_rand(1000, 9999);
        }

        // Extract the last 9 digits of the phone number
        $lastNineDigits = substr($request->phone, -9);

        // Prepend '255' to the extracted digits
        $phoneNumber = '255' . $lastNineDigits;

        // dd($request);
        // Create the borrower with the unique borrower ID
        Borrower::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $phoneNumber,
            'address' => $request->address,
            'national_id' => $request->national_id,
            'employment_status' => $request->employment_status,
            'employment_name' => $request->employment_name,
            'income' => $request->income,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'sponsor_name' => $request->sponsor_name,
            'loanee_id' => $borrowerId,
            'branch_id' => $request->branch_id,
            'status' => 'active',
        ]);

        Toastr::success('Loanee successfully added ✔');
        return back();
    }

    public function updateBorrower(Request $request, $id)
    {

        $borrower = Borrower::findOrFail($id);
        dd($request);

        // Check if email or phone number has changed
        $emailChanged = $request->email !== $borrower->email;
        $phoneChanged = $request->phone !== $borrower->phone;

        // Define validation rules dynamically
        $validationRules = [
            'name' => 'required|regex:/^[a-zA-Z\s]+$/',
            'phone' => [
                'required',
                'numeric',
                'regex:/^0\d{9}$/',
                'digits:10',
                Rule::unique('borrowers', 'phone')->where(function ($query) use ($request) {
                    return $query->where('phone', $request->phone);
                }),
            ],
        ];

        if ($phoneChanged) {
            $validationRules['phone'] .= '|unique:borrowers,phone';
        }

        $this->validate($request, $validationRules);
        // dd($request);
        // dd($request->all());

        // Extract the last 9 digits of the phone number
        $lastNineDigits = substr($request->phone, -9);

        // Prepend '255' to the extracted digits
        $phoneNumber = '255' . $lastNineDigits;

        $borrower->name = $request->name;
        $borrower->email = $request->email;
        $borrower->phone = $phoneNumber;
        $borrower->address = $request->location;
        $borrower->national_id = $request->national_id;
        $borrower->employment_status = $request->employment_status;
        $borrower->employment_name = $request->employment_name;
        $borrower->income = $request->income;
        $borrower->gender = $request->gender;
        $borrower->sponsor_name = $request->sponsor_name;
        $borrower->branch_id = $request->branch_id;
        $borrower->status = $request->status;
        // dd($borrower);
        $borrower->save();

        Toastr::success('Loanee successfully updated!');
        return back();
    }

    public function destroy(Request $request)
    {

        $borrower = Borrower::find($request->id);

        if($borrower->status == 'Active'){
            Toastr::error('You cannot delete active Loanee');
            return back();
        }

        $borrower->delete();
        Toastr::success('Loanee successfully deleted!');
        return back();
    }

    public function viewBorrower($id)
    {
        // Retrieve the borrower by ID
        $borrower = Borrower::with('loans.installments')->findOrFail($id);

        // Pass the borrower and their loans to the view
        return view('admin.users.view-borrower', compact('borrower'));
    }

    public function viewLoan($id)
    {
        $loan = Loan::with(['borrower', 'installments'])->findOrFail($id);
        return view('admin.users.view-loan', compact('loan'));
    }

}
