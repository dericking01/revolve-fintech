@extends('layouts.admin.base')

@section('content')

<div class="card mb-3">
    <div class="card-body">
        <div class="row">
            <!-- Borrower Details -->
            <div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
                <h4 class="mb-3 fs-0 text-primary">Borrower Details</h4>
                <h5 class="mb-2 text-dark">{{ $borrower->name }}</h5>
                <p class="mb-0 fs--1">
                    <strong>Phone:</strong>
                    <a href="tel:+{{ $borrower->phone }}" class="text-decoration-none">
                        <span class="badge bg-info text-dark">{{ $borrower->phone }}</span>
                    </a>
                </p>
                <p class="mb-0 fs--1">
                    <strong>Email:</strong>
                    <a href="mailto:{{ $borrower->email }}" class="text-decoration-none">
                        <span class="badge bg-secondary">{{ $borrower->email }}</span>
                    </a>
                </p>
                <p class="mb-0 fs--1">
                    <strong>Joined Date:</strong>
                    <span class="badge bg-success">{{ $borrower->created_at->format('d M, Y') }}</span>
                </p>
            </div>

            <!-- Loan Details -->
            <div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
                <h4 class="mb-3 fs-0 text-primary">Loan Details</h4>
                <p class="mb-0 fs--1">
                    <strong>Total Loans:</strong>
                    <span class="badge bg-primary">{{ $borrower->loans->count() }}</span>
                </p>
                <p class="mb-0 fs--1">
                    <strong>Total Amount Loaned:</strong>
                    <span class="badge bg-warning text-dark">
                        Tsh {{ number_format($borrower->loans->sum('loan_amount'), 2) }}
                    </span>
                </p>
                <p class="mb-0 fs--1">
                    <strong>Total Amount Paid:</strong>
                    <span class="badge bg-success">
                        Tsh {{ number_format($borrower->loans->sum('paid_amount'), 2) }}
                    </span>
                </p>
                <p class="mb-0 fs--1">
                    <strong>Total Amount Due:</strong>
                    <span class="badge bg-danger">
                        Tsh {{ number_format($borrower->loans->sum('amount_to_be_paid') - $borrower->loans->sum('paid_amount'), 2) }}
                    </span>
                </p>
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
                        {{-- <td class="align-middle">{{ $loan->start_date->format('d M, Y') }}</td> --}}
                        {{-- <td class="align-middle">{{ $loan->due_date->format('d M, Y') }}</td> --}}
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
