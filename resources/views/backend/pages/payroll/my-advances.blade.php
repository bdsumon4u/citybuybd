@extends('backend.layout.template')
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
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Note</th>
                            <th>Approved By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($advances as $key => $adv)
                            <tr>
                                <td>{{ $advances->firstItem() + $key }}</td>
                                <td>{{ \Carbon\Carbon::parse($adv->date)->format('d M, Y') }}</td>
                                <td>৳{{ number_format($adv->amount, 2) }}</td>
                                <td>{{ $adv->note ?? '-' }}</td>
                                <td>{{ $adv->approver->name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No salary advances found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $advances->links() }}
            </div>
        </div>
    </div>
@endsection
