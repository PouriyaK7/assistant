<?php

namespace App\Services\Livewire;

trait ConfirmSubmit
{
    /**
     * Get confirm for component operations
     *
     * @param string $method The method is going to call after user confirmation
     * @param string|null $text Confirm modal title
     * @param $data -> Component method
     * @return void
     */
    public function confirmSubmit(string $method, string $text = null, $data = null): void
    {
        $this->dispatchBrowserEvent('show-confirm-dialog', [
            'title' => 'Are you sure? :)',
            'text' => $text ?? 'Do you really want to submit this form?',
            'type' => 'warning',
            'confirmButtonText' => 'Yes',
            'cancelButtonText' => 'No',
            'onConfirmed' => $method,
            'data' => $data,
        ]);
    }
}
