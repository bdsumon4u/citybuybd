@extends('employee.layout.template')
@section('body-content')
    <div class="br-pagetitle">
        <div>
            <h4>My Salary Advances</h4>
            <p class="mg-b-0">View your salary advance history</p>
        </div>
    </div>

    <div class="br-pagebody">
        <div class="br-section-wrapper pd-20">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Note</th>
                            <th>Approved By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($advances as $i => $advance)
                            <tr>
                                <td>{{ $advances->firstItem() + $i }}</td>
                                <td>{{ $advance->date->format('d M Y') }}</td>
                                <td>৳{{ number_format($advance->amount, 2) }}</td>
                                <td>{{ $advance->note ?? '-' }}</td>
                                <td>{{ $advance->approver->name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No salary advances found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $advances->links() }}
            </div>
        </div>
    </div>
@endsection
