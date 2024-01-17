<?php

namespace App\Filament\Pages\Tenancy;

use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Actions\Action;
use App\Models\Organization;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Forms\Components\TextInput;
use Illuminate\Validation\ValidationException;

class InviteToOrganization extends Page
{

    protected static ?string $navigationIcon = 'invite-user';
    protected static ?string $slug = 'invite';

    protected static ?string $navigationGroup = 'Organization';

    public static string $view = 'admin.tenancy.organization.invite';

    public ?Organization $organization = null;

    public ?array $data = [];

    public array $rules = [
        'email' => ['required', 'email'],
    ];

    public static function getLabel(): string
    {
        return __('Invite to Organization');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('email')->email()->required(),
            ])
            ->statePath('data');
    }

    /**
     * @throws ValidationException
     */
    public function invite(): void
    {
        $this->form->getState();

        Filament::getTenant()->invitations()->create($this->data);

        Notification::make()
            ->title(__('Invitation sent to :email.', ['email' => $this->data['email']]))
            ->success()
            ->send();

        $this->redirect($this->getSlug());
    }

    protected function getFormActions(): array
    {
        return [
            $this->getInviteFormAction(),
        ];
    }

    public function getInviteFormAction(): Action
    {
        return Action::make('invite')
            ->label(static::getLabel())
            ->submit('invite');
    }
}
