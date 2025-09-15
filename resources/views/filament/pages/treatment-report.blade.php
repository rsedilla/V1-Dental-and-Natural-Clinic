<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Patient Treatment History</h3>
            <p class="text-gray-600 mb-4">Select a patient to view their complete treatment history, appointments, and payment records.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-blue-900 mb-2">Total Patients</h4>
                    <p class="text-2xl font-bold text-blue-700">{{ \App\Models\Patient::count() }}</p>
                </div>
                
                <div class="bg-green-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-green-900 mb-2">Total Treatments</h4>
                    <p class="text-2xl font-bold text-green-700">{{ \App\Models\Treatment::count() }}</p>
                </div>
                
                <div class="bg-purple-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-purple-900 mb-2">Total Revenue</h4>
                    <p class="text-2xl font-bold text-purple-700">₱{{ number_format(\App\Models\Payment::sum('amount'), 2) }}</p>
                </div>
            </div>
            
            <div class="mt-8">
                <p class="text-gray-600">This page will allow you to search and view detailed treatment reports for individual patients. The feature will include:</p>
                <ul class="list-disc list-inside mt-2 text-gray-600 space-y-1">
                    <li>Patient search and selection</li>
                    <li>Complete appointment history</li>
                    <li>Detailed treatment records</li>
                    <li>Payment history and status</li>
                    <li>Revenue sharing details</li>
                    <li>Export functionality</li>
                </ul>
            </div>
        </div>
    </div>
</x-filament-panels::page>
