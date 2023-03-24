<?php

namespace App\Services\Livewire;

trait HasModal
{
    /**
     * Use this property as wire:model in your modal
     *
     * @var bool
     */
    public bool $modalStatus = false;

    /**
     * Show modal
     *
     * @return void
     */
    public function showModal(): void
    {
        $this->modalStatus = true;
    }

    public function hideModal(): void
    {
        $this->modalStatus = false;
    }
}
