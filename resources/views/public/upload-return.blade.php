<!DOCTYPE html>
<html>
<head>
    <title>Upload Signed Return Receipt</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Upload Signed Return Receipt</h1>
        <p class="mb-2">Transaction ID: <strong>{{ $transaction->transaction_id }}</strong></p>
        <p class="mb-6">Product: <strong>{{ $transaction->product->product_name }}</strong></p>

        <form action="{{ route('public.return.upload.handle', $transaction->return_upload_token) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="signed_receipt" class="block text-sm font-medium text-gray-700">Receipt File (PDF, JPG, PNG)</label>
                <input type="file" name="signed_receipt" id="signed_receipt" required class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>
            @error('signed_receipt')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Upload</button>
        </form>
    </div>
</body>
</html>