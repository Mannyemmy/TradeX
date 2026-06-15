<?php

namespace App\Http\Livewire\Admin;

use App\Models\Settings;
use Livewire\Component;

class SoftwareModule extends Component
{
    public function render()
    {
        $settings = Settings::find(1);
        return view('livewire.admin.software-module', [
            'mod' => $settings ? ($settings->modules ?? []) : [],
        ]);
    }

    public function updateModule($module, $value)
    {
        $allowed = [
            'investment', 'cryptoswap', 'pre_ipo', 'trading',
            'copy_trading', 'bot_trading', 'signal', 'nft', 'loan', 'membership',
            'stocktrading',
        ];

        if (!in_array($module, $allowed)) {
            return redirect()->route('appsettingshow')->with('message', 'Invalid module');
        }

        $settings = Settings::find(1);
        $options = $settings->modules ?? [];
        $options[$module] = $value === 'true';
        $settings->modules = $options;
        $settings->save();

        return redirect()->route('appsettingshow')->with('success', 'Module updated successfully');
    }
}
