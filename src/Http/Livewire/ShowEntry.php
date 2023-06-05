<?php

namespace LaraZeus\Bolt\Http\Livewire;

use Filament\Forms;
use LaraZeus\Bolt\Models\Response;
use Livewire\Component;

class ShowEntry extends Component implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    public Response $response;

    public function mount($responseID)
    {
        $this->response = config('zeus-bolt.models.Response')::with('user')
            ->where('user_id', auth()->user()->id)
            ->where('id', $responseID)
            ->firstOrFail();
    }

    public function render()
    {
        seo()
            ->title(__('Show entry') . ' | ' . config('zeus-bolt.site_title', 'Laravel'))
            ->description(__('Show entry') . ' | ' . config('zeus-bolt.site_description', 'Laravel'))
            ->site(config('zeus-bolt.site_title', 'Laravel'))
            ->rawTag('favicon', '<link rel="icon" type="image/x-icon" href="' . asset('favicon/favicon.ico') . '">')
            ->rawTag('<meta name="theme-color" content="' . config('zeus-bolt.site_color') . '" />')
            ->withUrl()
            ->twitter();

        return view(app('bolt-theme') . '.show-entry')
            ->with('response', $this->response)
            ->layout(config('zeus-bolt.layout'));
    }
}
