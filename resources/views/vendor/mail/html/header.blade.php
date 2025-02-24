@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
@else
{{-- <img src="https://laravel.com/img/notification-logo.png" width="20" height="26" alt="">  --}}
{{ $slot }}
@endif
</a>
</td>
</tr>
