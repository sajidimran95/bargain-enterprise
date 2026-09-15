<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        // Workspace tab iframes must never paint the full desktop chrome.
        if (request()->boolean('embed')
            || request()->header('X-Workspace-Embed') === '1'
            || request()->header('Sec-Fetch-Dest') === 'iframe') {
            return view('layouts.embed');
        }

        return view('layouts.app');
    }
}
