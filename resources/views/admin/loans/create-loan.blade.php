@extends('layouts.admin.base')

@section('content')

<div class="card mb-3">
    <div class="card-header">
      <div class="row flex-between-end">
        <div class="col-auto align-self-center">
          <h5 class="mb-0" id="validation-example">Create a Loan for a Loanee</h5>
        </div>
      </div>
    </div>
    <div class="card-body bg-light">
      <form action="{{ route('admin.storeLoan') }}" method="POST" enctype="multipart/form-data" class="needs-validation">
        @csrf

 <!-- Loanee's Name -->
  <div class="col-md-12">
    <div class="mb-3">
      <label for="organizerSingle2">Loanee's Name</label>
      <select class="form-select js-choice" id="organizerSingle2" size="1" name="borrower_id" data-options='{"removeItemButton":true,"placeholder":true}'>
        <option value="">Select Loanee...</option>
        @foreach ($borrowers as $loanee)
          <option value="{{ $loanee->id }}">{{ $loanee->name }}</option>
        @endforeach
      </select>
    </div>
  </div>

  <!-- Creator/Loan Officer -->

 <div class="col-md-12">
    <div class="mb-3">
    <label for="organizerSingle2">Creator/Loan Officer</label>
    <select class="form-select js-choice" id="creatorSelect" size="1" name="created_by_id" required>
        <option value="">Select Creator...</option>
        @foreach ($users as $user)
            <option value="{{ $user->id }}" data-type="{{ $user->type }}">
                {{ $user->name }} ({{ ucfirst($user->type) }})
            </option>
        @endforeach
    </select>
    <input type="hidden" name="created_by_type" id="creatorType">
    </div>
 </div>

  <!-- Application Fee -->
  <div class="mb-3">
    <label for="application_fee">Application Fee</label>
    <input type="number" step="0.01" class="form-control" id="application_fee" name="application_fee" placeholder="Enter application fee amount">
  </div>

  <!-- Loan Amount -->
  <div class="mb-3">
    <label for="loan_amount">Loan Amount</label>
    <input type="number" step="0.01" class="form-control" id="loan_amount" name="loan_amount" placeholder="Enter loan amount">
  </div>

  <!-- Interest Rate -->
  <div class="mb-3">
    <label for="interest_rate">Interest Rate (%)</label>
    <input type="number" step="0.01" class="form-control" id="interest_rate" name="interest_rate" placeholder="Enter interest rate">
  </div>

  <!-- Loan Term -->
  <div class="mb-3">
    <label for="term">Loan Term</label>
    <select class="form-select" id="term" name="term">
      <option value="">Select Terms (Months)...</option>
      <option value="1">1</option>
      <option value="2">2</option>
      <option value="3">3</option>
    </select>
  </div>

  <!-- Start Date -->
  <div class="mb-3">
    <label for="start_date" class="form-label">Start Date</label>
    <input class="form-control datetimepicker" id="start_date" type="text" name="start_date" placeholder="d/m/y">
  </div>

  <!-- Amount to be Paid -->
  <div class="mb-3">
    <label for="amount_to_be_paid">Amount to be Paid</label>
    <input type="number" step="0.01" class="form-control" id="amount_to_be_paid" name="amount_to_be_paid" placeholder="Enter amount to be paid">
  </div>

  <div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <textarea class="form-control" id="description" name="description" rows="4" placeholder="Enter loan description or additional details"></textarea>
  </div>

        <button class="btn btn-primary" type="submit">Confirm</button>
      </form>
    </div>
</div>

<script>
    document.getElementById('creatorSelect').addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const creatorType = selectedOption.getAttribute('data-type');
        document.getElementById('creatorType').value = creatorType;
    });
</script>


@endsection


