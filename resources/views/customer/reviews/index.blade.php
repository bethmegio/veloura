@extends('layouts.app')

@section('content')
<div class="container">
    <h1>My Reviews</h1>
    
    <table class="table mt-3">
        <thead>
            <tr>
                <th>Gown</th>
                <th>Rating</th>
                <th>Comment</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reviews as $review)
            <tr>
                <td>{{ $review->gown->name }}</td>
                <td>{{ $review->rating }}/5</td>
                <td>{{ $review->comment }}</td>
                <td>{{ $review->created_at->format('M d, Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">No reviews found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    {{ $reviews->links() }}
</div>
@endsection