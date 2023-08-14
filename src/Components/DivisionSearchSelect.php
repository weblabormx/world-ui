<?php

namespace WeblaborMx\WorldUi\Components;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\View\Component;

class DivisionSearchSelect extends Component
{
    public $options;
    public $api;

    public function __construct(
        ?string $search = null,
        string|int|null $parent_id = null,
    ) {
        if (!$search) {
            return;
        }

        $this->api = "/search/{$search}/{$parent_id}?fields=id,name";

        $key = md5("worldui.native-select:{$this->api}");

        $this->options = Cache::remember($key, now()->addMinutes(1), fn () => $this->getOptions());

        if (is_null($this->options)) {
            Cache::forget($key);
        }
    }

    public function render()
    {
        return view('worldui::components.worldui-native-select');
    }

    protected function getOptions()
    {
        $data = null;

        try {
            $data = Http::withHeader('Authorization', 'Bearer ' . config('worldui.api_token'))
                ->timeout(3)
                ->get(config('worldui.endpoint') . $this->api, [])
                ->json() ?? [];
        } finally {
            return $data;
        }
    }
}
