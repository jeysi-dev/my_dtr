<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DTR - Daily Time Record</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        input[type="time"]::-webkit-calendar-picker-indicator {
            cursor: pointer;
        }

        .dtr-table input {
            @apply text-center text-sm;
        }

        .dtr-table td {
            @apply border border-gray-300 px-1 py-1;
        }

        .dtr-table th {
            @apply border border-gray-300 px-2 py-2 bg-gray-100 text-sm font-semibold;
        }

        .holiday-row {
            @apply bg-yellow-50;
        }

        .weekend-row {
            @apply bg-gray-50;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        @yield('content')
    </div>

    <script>
        // Global helper functions
        function formatTime12Hour(time24) {
            if (!time24) return '';
            const [hours, minutes] = time24.split(':');
            const hour = parseInt(hours);
            const ampm = hour >= 12 ? 'PM' : 'AM';
            const hour12 = hour % 12 || 12;
            return `${hour12}:${minutes} ${ampm}`;
        }

        function parseTime12to24(time12) {
            if (!time12) return null;
            const match = time12.match(/(\d{1,2}):(\d{2})\s*(AM|PM)/i);
            if (!match) return null;
            let [, hours, minutes, ampm] = match;
            hours = parseInt(hours);
            if (ampm.toUpperCase() === 'PM' && hours !== 12) hours += 12;
            if (ampm.toUpperCase() === 'AM' && hours === 12) hours = 0;
            return `${hours.toString().padStart(2, '0')}:${minutes}`;
        }
    </script>

    @stack('scripts')
</body>

</html>
