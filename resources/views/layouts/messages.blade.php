@if (Session('success'))
<div class="bg-emerald-200 text-emerald-700 text-center py-2" id="status_message">
    <p>{{ Session('success') }}</p>
</div>
@endif

@if (Session('error'))
<div class="bg-red-200 text-red-700 text-center py-2" id="status_message">
    <p>{{ Session('error') }}</p>
</div>
@endif
