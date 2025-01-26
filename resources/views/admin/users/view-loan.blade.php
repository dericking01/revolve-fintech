@extends('layouts.admin.base')

@section('content')
<div class="card mb-3">
    <div class="card-body">
        <h5>Loan Details: #00{{ $loan->id }}</h5>
        <p class="fs--1">Issued on {{ \Carbon\Carbon::parse($loan->created_at)->format('F j, Y, g:i A') }}</p>
        <div class="d-flex align-items-center">
            <strong class="me-2">Status:</strong>
            <span class="badge bg-{{ $loan->status == 'completed' ? 'success' : ($loan->status == 'pending' ? 'warning' : 'danger') }}">
                {{ ucfirst($loan->status) }}
            </span>

        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <h5 class="mb-3">Borrower Details</h5>
        <p><strong>Name:</strong> {{ $loan->borrower->name }}</p>
        <p><strong>Email:</strong> <a href="mailto:{{ $loan->borrower->email }}">{{ $loan->borrower->email }}</a></p>
        <p><strong>Phone:</strong> <a href="tel:{{ $loan->borrower->phone }}">{{ $loan->borrower->phone }}</a></p>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h5 class="mb-3">Installments</h5>
        <table class="table data-table table-bordered table-striped fs--1 mb-0">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Installment Amount</th>
                    <th>Paid Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @php $sn = 0; @endphp
                @foreach($loan->installments as $installment)
                    <tr>
                        <td>{{ ++$sn }}</td>
                        <td>Tsh {{ number_format($installment->installed_amount, 2) }}</td>
                        <td>{{ $installment->paid_date ? \Carbon\Carbon::parse($installment->paid_date)->format('d M, Y') : 'Not Paid' }}</td>
                        <td>
                            <span class="badge badge-subtle-{{ $installment->status == 'paid' ? 'success' : ($installment->status == 'late' ? 'warning' : 'danger') }}">
                                {{ ucfirst($installment->status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
