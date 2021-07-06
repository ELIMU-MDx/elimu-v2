<?php

namespace App\Http\Livewire;

use Domain\Rdml\DataTransferObjects\Target;
use Domain\Rdml\RdmlParser;
use Domain\Rdml\RdmlReader;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class DemoForm extends Component
{
    use WithFileUploads;

    public mixed $rdml = null;

    public array $targets = [];

    public function render(): View
    {
        return view('livewire.demo-form');
    }

    public function addTarget(string $name, string $fluor): void
    {
        $this->targets[] = [
            'target' => $name,
            'fluor' => $fluor,
            'pathogen' => '',
            'quantify' => false,
            'threshold' => '',
            'cutoff' => '',
            'cutoff_stddev' => '',
            'slope' => '',
            'intercept' => '',
            'reppetitions' => '',
        ];
    }

    public function updatedRdml(TemporaryUploadedFile $file): void
    {
        $this->validate([
            'rdml' => 'mimes:rdml,zip',
        ]);

        $rdml = app(RdmlParser::class)->extract(app(RdmlReader::class)->read($file));

        $this->targets = [];
        $rdml->targets->each(function (Target $target) {
            $this->addTarget($target->id, $target->dye->id);
        });
    }
}
