<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-t">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" href="{{ asset('images/logo.svg') }}" type="image/svg+xml">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-light-bg dark:bg-dark-bg">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-light-bg dark:bg-dark-bg border-b border-border-color">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="bg-light-bg dark:bg-dark-bg">
                {{ $slot }}
            </main>
        </div>
        
        <!-- Send Email Modal -->
        <div id="send-email-modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 hidden items-center justify-center">
            <div class="bg-light-surface dark:bg-dark-surface p-6 rounded-lg shadow-xl w-full max-w-lg">
                <h2 id="send-email-modal-title" class="text-xl font-bold mb-4 text-light-text dark:text-dark-text">Send Receipt</h2>
                <form id="send-email-form">
                    <input type="hidden" id="modal-transaction-id">
                    <input type="hidden" id="modal-receipt-type">

                    <div class="space-y-4">
                        <div class="mt-2">
                            <label for="send_to_partner" class="flex items-center">
                                <input id="send_to_partner" type="checkbox" name="send_to_partner" value="1" class="rounded border-gray-300 text-action shadow-sm focus:ring-action">
                                <span class="ms-2 text-sm text-light-text-muted dark:text-dark-text-muted">{{ __('Send to Partner') }}</span>
                            </label>
                        </div>
                        <div>
                            <label for="user-select" class="block text-sm font-medium text-light-text-muted dark:text-dark-text-muted">Select Registered Users</label>
                            <select id="user-select" multiple></select>
                        </div>
                        <div>
                            <label for="manual-emails" class="block text-sm font-medium text-light-text-muted dark:text-dark-text-muted">Or Add Emails Manually (comma-separated)</label>
                            <input type="text" id="manual-emails" placeholder="email1@example.com, email2@example.com" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-dark-surface dark:text-dark-text rounded-md shadow-sm">
                        </div>
                        <div>
                            <label for="email-subject" class="block text-sm font-medium text-light-text-muted dark:text-dark-text-muted">Subject</label>
                            <input type="text" id="email-subject" required class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-dark-surface dark:text-dark-text rounded-md shadow-sm">
                        </div>
                        <div>
                            <label for="email-body" class="block text-sm font-medium text-light-text-muted dark:text-dark-text-muted">Body</label>
                            <textarea id="email-body" rows="5" required class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-dark-surface dark:text-dark-text rounded-md shadow-sm"></textarea>
                            <p class="text-xs text-gray-500 mt-1">You can use basic HTML tags like &lt;p&gt;, &lt;br&gt;, &lt;strong&gt;.</p>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-2">
                        <button type="button" onclick="closeSendEmailModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white dark:bg-gray-600 dark:text-gray-200 border border-gray-300 dark:border-gray-500 rounded-md hover:bg-gray-50 dark:hover:bg-gray-500">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-action border border-transparent rounded-md hover:bg-blue-700">Send Email</button>
                    </div>
                </form>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
        <script>
            var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
            var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
                if(themeToggleLightIcon) themeToggleLightIcon.classList.remove('hidden');
            } else {
                document.documentElement.classList.remove('dark');
                if(themeToggleDarkIcon) themeToggleDarkIcon.classList.remove('hidden');
            }

            var themeToggleBtn = document.getElementById('theme-toggle');

            if(themeToggleBtn) {
                themeToggleBtn.addEventListener('click', function() {
                    themeToggleDarkIcon.classList.toggle('hidden');
                    themeToggleLightIcon.classList.toggle('hidden');
                    if (localStorage.getItem('color-theme')) {
                        if (localStorage.getItem('color-theme') === 'light') {
                            document.documentElement.classList.add('dark');
                            localStorage.setItem('color-theme', 'dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                            localStorage.setItem('color-theme', 'light');
                        }
                    } else {
                        if (document.documentElement.classList.contains('dark')) {
                            document.documentElement.classList.remove('dark');
                            localStorage.setItem('color-theme', 'light');
                        } else {
                            document.documentElement.classList.add('dark');
                            localStorage.setItem('color-theme', 'dark');
                        }
                    }
                });
            }

            let userTomSelect;

            function openSendEmailModal(transactionId, type) {
                const modal = document.getElementById('send-email-modal');
                const subjectInput = document.getElementById('email-subject');
                const bodyInput = document.getElementById('email-body');

                // Tampilkan modal dengan status loading
                document.getElementById('modal-transaction-id').value = transactionId;
                document.getElementById('modal-receipt-type').value = type;
                document.getElementById('send-email-modal-title').innerText = `Send ${type === 'loan' ? 'Loan' : 'Return'} Receipt`;
                subjectInput.value = 'Loading template...';
                bodyInput.value = 'Loading template...';
                modal.classList.remove('hidden');
                modal.classList.add('flex');

                // Ambil detail transaksi untuk mengisi template
                fetch(`{{ url('/transactions') }}/${transactionId}/details-for-email`)
                    .then(response => response.json())
                    .then(result => {
                        if (result.success && result.templates[type]) {
                            const template = result.templates[type];
                            subjectInput.value = template.subject;
                            bodyInput.value = template.body;
                        } else {
                            alert('Failed to load email template.');
                            closeSendEmailModal();
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching details:', error);
                        alert('An error occurred while loading details.');
                        closeSendEmailModal();
                    });

                // Inisialisasi TomSelect
                if (userTomSelect) {
                    userTomSelect.clear();
                    userTomSelect.clearOptions();
                } else {
                    userTomSelect = new TomSelect('#user-select', {
                        valueField: 'value',
                        labelField: 'text',
                        searchField: 'text',
                        placeholder: 'Search for a user...',
                        plugins: ['remove_button'],
                        load: function(query, callback) {
                            fetch(`{{ route('select.users') }}?q=${encodeURIComponent(query)}`)
                                .then(response => response.json())
                                .then(json => callback(json))
                                .catch(() => callback());
                        }
                    });
                }
                
                document.getElementById('manual-emails').value = '';
                document.getElementById('send_to_partner').checked = false;
            }

            function closeSendEmailModal() {
                const modal = document.getElementById('send-email-modal');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            document.getElementById('send-email-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const transactionId = document.getElementById('modal-transaction-id').value;
                const type = document.getElementById('modal-receipt-type').value;
                const userIds = userTomSelect.getValue();
                const manualEmails = document.getElementById('manual-emails').value;
                const sendToPartner = document.getElementById('send_to_partner').checked;
                const subject = document.getElementById('email-subject').value;
                const body = document.getElementById('email-body').value;

                const url = type === 'loan' 
                    ? `{{ url('/transactions') }}/${transactionId}/loan-receipt/send`
                    : `{{ url('/transactions') }}/${transactionId}/return-receipt/send`;

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        user_ids: userIds,
                        manual_emails: manualEmails,
                        send_to_partner: sendToPartner,
                        subject: subject,
                        body: body,
                    })
                })
                .then(response => response.json().then(data => ({ status: response.status, body: data })))
                .then(({ status, body }) => {
                    if (status >= 200 && status < 300) {
                        alert(body.message);
                        window.location.reload();
                    } else {
                        alert('Error: ' + (body.message || 'Something went wrong'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An unexpected error occurred.');
                });
            });
        </script>
        @stack('scripts')
    </body>
</html>
