<div>
    <h1>{{ __('You are invited to :organization', ['organization' => $invitation->organization->name]) }}</h1>

    <p>{{ __('You are invited by :user', ['user' => $invitation->invitedBy->name]) }}</p>

    <p>{{ __('Click the button below to accept the invitation') }}</p>

    <table class="action" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td align="center">
                <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
                    <tr>
                        <td align="center">
                            <table border="0" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td>
                                        <a href="{{ route('organization.accept-invitation', ['token' => $invitation->token]) }}"
                                           class="button button-primary" target="_blank"
                                           rel="noopener">{{ __('Accept invitation') }}</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <p>{{ __('If you did not expect to receive an invitation, you can ignore this email') }}</p>
</div>
