<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rsvp;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    /**
     * Genera un documento Word (.docx) con los nombres de los invitados que
     * SÍ asisten, en orden alfabético y enumerados.
     */
    public function guests(): StreamedResponse
    {
        $names = Rsvp::where('attending', true)
            ->orderByRaw('LOWER(name) asc')
            ->pluck('name')
            ->map(fn (string $n) => trim($n))
            ->filter()
            ->values();

        $word = new PhpWord();
        $word->getSettings()->setThemeFontLang(new \PhpOffice\PhpWord\Style\Language(\PhpOffice\PhpWord\Style\Language::ES_ES));

        // Estilos
        $word->addTitleStyle(1, ['size' => 18, 'bold' => true, 'color' => '1f2937']);
        $word->addFontStyle('meta', ['size' => 10, 'italic' => true, 'color' => '6b7280']);
        $word->addFontStyle('item', ['size' => 12]);

        $section = $word->addSection([
            'marginTop' => 1200, 'marginBottom' => 1200,
            'marginLeft' => 1400, 'marginRight' => 1400,
        ]);

        $title = config('event.short_title', 'Lista de invitados');
        $section->addTitle($title, 1);
        $section->addText(
            'Invitados confirmados (asisten) · ' . $names->count() . ' persona(s) · '
                . now()->timezone(config('event.timezone'))->format('d/m/Y H:i'),
            'meta'
        );
        $section->addTextBreak(1);

        if ($names->isEmpty()) {
            $section->addText('Aún no hay confirmaciones de asistencia.', 'item');
        } else {
            foreach ($names as $i => $name) {
                $section->addText(($i + 1) . '.  ' . $name, 'item', ['spaceAfter' => 120]);
            }
        }

        $filename = 'invitados-confirmados-' . now()->format('Y-m-d') . '.docx';

        return response()->streamDownload(function () use ($word) {
            IOFactory::createWriter($word, 'Word2007')->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ]);
    }
}
