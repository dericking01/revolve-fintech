@extends('layouts.admin.base')

@section('content')
<div class="card">
  <div class="card-header bg-light">
    <div class="row align-items-center">
      <div class="col">
        <h5 class="mb-0">Paid Loans ({{ $loans->count() }})</h5>
      </div>
    </div>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table data-table table-bordered table-striped fs--1 mb-0">
        <thead class="bg-200 text-900">
          <tr>
            <th>SN</th>
            <th>Customer</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($loans as $key => $loan)
          <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $loan->borrower->name }}</td>
            <td>{{ number_format($loan->loan_amount) }}</td>
            <td>
              <span class="badge bg-success text-dark">PAID</span>
            </td>
            <td>
              <button class="btn btn-sm btn-success" onclick="window.location.href='{{ route('borrower.view-loan', $loan->id) }}'">View</button>
              <button class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#editLoanModal{{ $loan->id }}">Edit</button>
              <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteLoanModal{{ $loan->id }}">Delete</button>
            </td>
          </tr>

          <!-- Edit Loan Modal -->
          <div class="modal fade" id="editLoanModal{{ $loan->id }}" tabindex="-1" aria-labelledby="editLoanLabel{{ $loan->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('admin.loan.update', $loan->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Loan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <!-- Loan Amount -->
                            <div class="mb-3">
                                <label for="loan_amount">Loan Amount</label>
                                <input type="number" step="0.01" class="form-control" id="loan_amount" name="loan_amount" value="{{ old('loan_amount', $loan->loan_amount) }}" placeholder="Enter loan amount">
                            </div>

                            <!-- Interest Rate -->
                            <div class="mb-3">
                                <label for="interest_rate">Interest Rate (%)</label>
                                <input type="number" step="0.01" class="form-control" id="interest_rate" name="interest_rate" value="{{ old('interest_rate', $loan->interest_rate) }}" placeholder="Enter interest rate">
                            </div>

                            <!-- Loan Term -->
                            <div class="mb-3">
                                <label for="term">Loan Term</label>
                                <select class="form-select" id="term" name="term">
                                    <option value="">Select Terms (Months)...</option>
                                    <option value="1" {{ old('term', $loan->term) == 1 ? 'selected' : '' }}>1</option>
                                    <option value="2" {{ old('term', $loan->term) == 2 ? 'selected' : '' }}>2</option>
                                    <option value="3" {{ old('term', $loan->term) == 3 ? 'selected' : '' }}>3</option>
                                </select>
                            </div>

                            <!-- Start Date -->
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input class="form-control datetimepicker" id="start_date" type="text" name="start_date" value="{{ old('start_date', $loan->start_date->format('d/m/y')) }}" placeholder="d/m/y">
                            </div>

                            <!-- Amount to be Paid -->
                            <div class="mb-3">
                                <label for="amount_to_be_paid">Amount to be Paid</label>
                                <input type="number" step="0.01" class="form-control" id="amount_to_be_paid" name="amount_to_be_paid" value="{{ old('amount_to_be_paid', $loan->amount_to_be_paid) }}" placeholder="Enter amount to be paid">
                            </div>

                            <!-- Status -->
                            <div class="mb-3">
                                <label for="status{{ $loan->id }}" class="form-label">Status</label>
                                <select name="status" class="form-select" id="status{{ $loan->id }}" required>
                                    <option value="pending" {{ old('status', $loan->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="ongoing" {{ old('status', $loan->status) == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Save Changes</button>
                        </div>
                    </div>
                </form>

            </div>
          </div>

          <!-- Delete Loan Modal -->
          <div class="modal fade" id="deleteLoanModal{{ $loan->id }}" tabindex="-1" aria-labelledby="deleteLoanLabel{{ $loan->id }}" aria-hidden="true">
            <div class="modal-dialog">
              <form action="{{ route('admin.loan.delete', $loan->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Delete Loan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <p>Are you sure you want to delete this loan?</p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
