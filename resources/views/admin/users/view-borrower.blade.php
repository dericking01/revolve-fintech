@extends('layouts.admin.base')

@section('content')
<div class="card shadow-lg border-0 mb-4">
    <div class="card-body p-4">
        <div class="row g-4">
            <!-- Borrower Details -->
            <div class="col-md-6 col-lg-4">
                <h4 class="text-primary fw-bold">Borrower's Details</h4>
                <div class="p-3 bg-light rounded shadow-sm">
                    <h5 class="text-dark fw-bold">{{ $borrower->name }}</h5>
                    <p class="mb-1"><strong>Phone:</strong> <a href="tel:+{{ $borrower->phone }}" class="text-decoration-none text-info">{{ $borrower->phone }}</a></p>
                    <p class="mb-1"><strong>Email:</strong> <a href="mailto:{{ $borrower->email }}" class="text-decoration-none text-secondary">{{ $borrower->email }}</a></p>
                    <p class="mb-1"><strong>Address:</strong> <span class="text-muted">{{ $borrower->address }}</span></p>
                    <p class="mb-1"><strong>Joined:</strong> <span class="badge bg-success">{{ $borrower->created_at->format('d M, Y') }}</span></p>
                </div>
            </div>

            <!-- Additional Details -->
            <div class="col-md-6 col-lg-4">
                <h4 class="text-primary fw-bold">Additional Details</h4>
                <div class="p-3 bg-light rounded shadow-sm">
                    <p class="mb-1"><strong>National ID:</strong> <span class="text-info">{{ $borrower->national_id }}</span></p>
                    <p class="mb-1"><strong>Employment:</strong> <span class="text-muted">{{ $borrower->employment_status }}</span></p>
                    <p class="mb-1"><strong>Occupation:</strong> <span class="text-muted">{{ $borrower->employment_name }}</span></p>
                    <p class="mb-1"><strong>Income:</strong> <span class="text-muted">Tsh {{ number_format($borrower->income, 2) }}</span></p>
                    <p class="mb-1"><strong>DOB:</strong> <span class="text-muted">{{ $borrower->date_of_birth }}</span></p>
                    <p class="mb-1"><strong>Gender:</strong> <span class="text-muted">{{ $borrower->gender }}</span></p>
                    <p class="mb-1"><strong>Branch:</strong> <span class="text-muted">{{ $borrower->branch->branch_name }}</span></p>
                    <p class="mb-1"><strong>Loanee ID:</strong> <span class="text-muted">{{ $borrower->loanee_id }}</span></p>
                    <p class="mb-0"><strong>Status:</strong>
                        <span class="badge {{ $borrower->status == 'active' ? 'bg-success' : ($borrower->status == 'inactive' ? 'bg-danger' : 'bg-secondary') }}">
                            {{ strtoupper($borrower->status) }}
                        </span>
                    </p>
                </div>
            </div>

            <!-- Loan Details -->
            <div class="col-md-12 col-lg-4">
                <h4 class="text-primary fw-bold">Loan Details</h4>
                <div class="p-3 bg-light rounded shadow-sm">
                    <p class="mb-1"><strong>Total Loans:</strong> <span class="badge bg-primary">{{ $borrower->loans->count() }}</span></p>
                    <p class="mb-1"><strong>Amount Loaned:</strong> <span class="badge bg-warning text-dark">Tsh {{ number_format($borrower->loans->sum('loan_amount'), 2) }}</span></p>
                    <p class="mb-1"><strong>Amount Paid:</strong> <span class="badge bg-success">Tsh {{ number_format($borrower->loans->sum('paid_amount'), 2) }}</span></p>
                    <p class="mb-0"><strong>Amount Due:</strong> <span class="badge bg-danger">Tsh {{ number_format($borrower->loans->sum('amount_to_be_paid') - $borrower->loans->sum('paid_amount'), 2) }}</span></p>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="card">
    <div class="card-header bg-light">
        <div class="row align-items-center">
            <div class="col">
                <h5 class="mb-0">Borrower's Loan History</h5>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive scrollbar">
            <table class="table data-table table-bordered table-striped fs--1 mb-0">
                <thead class="bg-200 text-900">
                    <tr>
                        <th class="sort pe-1 align-middle white-space-nowrap">S/N</th>
                        <th class="sort pe-1 align-middle white-space-nowrap">Loan Amount</th>
                        <th class="sort pe-1 align-middle white-space-nowrap">Amount Paid</th>
                        <th class="sort pe-1 align-middle white-space-nowrap">Amount Due</th>
                        <th class="sort pe-1 align-middle white-space-nowrap">Start Date</th>
                        <th class="sort pe-1 align-middle white-space-nowrap">Due Date</th>
                        <th class="sort pe-1 align-middle white-space-nowrap">Status</th>
                        <th class="sort pe-1 align-middle white-space-nowrap">Action</th>
                    </tr>
                </thead>

                <tbody class="list">
                    @php $sn = 0; @endphp
                    @foreach($borrower->loans as $loan)
                    <tr>
                        <td class="sn">{{ ++$sn }}</td>
                        <td class="align-middle">Tsh {{ number_format($loan->loan_amount, 2) }}</td>
                        <td class="align-middle">Tsh {{ number_format($loan->paid_amount, 2) }}</td>
                        <td class="align-middle">Tsh {{ number_format($loan->amount_to_be_paid - $loan->paid_amount, 2) }}</td>
                        <td class="align-middle">{{ \Carbon\Carbon::parse($loan->start_date)->format('d M, Y') }}</td>
                        <td class="align-middle">{{ \Carbon\Carbon::parse($loan->due_date)->format('d M, Y') }}</td>
                        <td class="align-middle">
                            <span class="badge badge-subtle-{{ $loan->status == 'paid' ? 'success' : ($loan->status == 'ongoing' ? 'warning' : 'danger') }}">
                                {{ ucfirst($loan->status) }}
                            </span>
                        </td>
                        <td class="align-middle">
                            <a href="{{ route('borrower.view-loan', $loan->id) }}" class="btn btn-primary btn-sm">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
