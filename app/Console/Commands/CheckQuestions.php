<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Question;
use App\Services\QuestionEngine\QuestionParser;
use App\Services\QuestionEngine\QuestionRenderer;

class CheckQuestions extends Command
{
    protected $signature = 'app:check-questions';
    protected $description = 'Check XML parsing and rendering of all questions';

    public function handle()
    {
        $parser = new QuestionParser();
        $renderer = new QuestionRenderer();

        $questions = Question::all();
        $this->info("Found " . $questions->count() . " questions.");

        $failed = 0;
        foreach ($questions as $q) {
            try {
                $parsed = $parser->parse($q->xml_content);
                $renderer->render($parsed);
                $this->line("Question ID {$q->id} [{$q->question_type}]: OK");
            } catch (\Exception $e) {
                $this->error("Question ID {$q->id} [{$q->question_type}] FAILED: " . $e->getMessage());
                $failed++;
            }
        }

        if ($failed > 0) {
            $this->error("{$failed} questions failed to parse/render.");
            return 1;
        }

        $this->info("All questions parsed and rendered successfully!");
        return 0;
    }
}
