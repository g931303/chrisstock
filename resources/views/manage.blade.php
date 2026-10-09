<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Manage Stock List</title>
  <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-12 col-md-8 col-lg-6">

        <div class="d-flex justify-content-between align-items-center mb-4">
          <h2 class="fw-bolder mb-0">Manage Stock List</h2>
          <div class="d-flex gap-2">
            <a href="{{ url('/') }}" class="btn btn-outline-primary">&larr; Back to Home</a>
            <form action="{{ route('logout') }}" method="post" class="d-inline">
              @csrf
              <button type="submit" class="btn btn-outline-secondary">Log Out</button>
            </form>
          </div>
        </div>

        @if(session('status'))
          <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        @if($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <div class="card border-0 shadow-sm mb-4">
          <div class="card-body">
            <h5 class="card-title mb-3">Add Stock Number</h5>
            <form action="{{ route('manage.add') }}" method="post" class="d-flex gap-2">
              @csrf
              <input type="text" name="stock_number" class="form-control" placeholder="e.g. 2330" maxlength="4" pattern="\d{4}" required>
              <button type="submit" class="btn btn-primary text-nowrap">Add</button>
            </form>
          </div>
        </div>

        <div class="card border-0 shadow-sm">
          <div class="card-body">
            <h5 class="card-title mb-3">Current Stocks</h5>
            <ul class="list-group">
              @forelse($stocks as $stock)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  {{ $stock }}
                  <form action="{{ route('manage.delete') }}" method="post" onsubmit="return confirm('Remove stock {{ $stock }}?');">
                    @csrf
                    <input type="hidden" name="stock_number" value="{{ $stock }}">
                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                  </form>
                </li>
              @empty
                <li class="list-group-item text-secondary">No stocks in the list yet.</li>
              @endforelse
            </ul>
          </div>
        </div>

      </div>
    </div>
  </div>

</body>
</html>
