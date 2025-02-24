<x-mail::message>
Hi {{ $email }}
 
Welcome to Real D2D! We're excited to invite you to join our platform, where real estate professionals, homeowners, and buyers connect seamlessly. 
As a member, you'll gain access to exclusive property listings, advanced tools for managing deals, and the opportunity to collaborate with your 
team and network with industry experts. Click the link below to accept your invitation and create your account today:
 
<x-mail::button :url="config('app.url') . '/register/member-invitation?token=' . $token . '&email=' . $email">
Accept Invitation
</x-mail::button>
 
Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
