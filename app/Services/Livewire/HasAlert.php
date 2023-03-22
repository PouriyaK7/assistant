<?php

namespace App\Services\Livewire;

trait HasAlert
{
    protected array $icons = [
        'success' => ['success', 'green'],
        'error' => ['error', 'red'],
        'warning' => ['warning', 'yellow'],
        'info' => ['info', 'blue'],
    ];

    /**
     * Show alert in component
     *
     * @param string $title
     * @param array $icon
     * @param string|null $iconColor
     * @return void
     */
    protected function showAlert(string $title, array $icon, string $iconColor = null): void
    {
        $this->dispatchBrowserEvent('updated', [
            'title' => $title,
            'icon' => $icon[0],
            'iconColor' => $iconColor ?? $icon[1],
        ]);
    }
}
