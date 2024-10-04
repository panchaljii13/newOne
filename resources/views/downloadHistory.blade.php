@extends('Layout')

@section('title', 'Download History')

@section('content')
<div class=" mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Download History</h3>
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Folder Name</th>
                        <th>Downloaded At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($downloadHistories as $history)
                        <tr>
                            <td>{{ $history->folder->name }}</td>
                            <td>{{ $history->downloaded_at->format('d M Y, h:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center">No download history available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
