<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot))
<h2>Invo</h2>
{{-- <img src="https://invo.test/img/logo.png" class="logo" alt="Laravel Logo"> --}}
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
